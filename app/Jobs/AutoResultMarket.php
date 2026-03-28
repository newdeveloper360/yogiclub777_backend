<?php

namespace App\Jobs;

use App\Models\Market;
use App\Models\MarketResult;
use App\Services\MarketResultSetService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use stdClass;

class AutoResultMarket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $marketResultSetService = new MarketResultSetService();
        $client = new Client();
        $url = env('API_URL');

        try {
            $response = $client->request('POST', $url);
            $dbMarkets = Market::where("auto_result", 1)->get();
            if ($response->getStatusCode() == 200) {
                $result = json_decode($response->getBody()->getContents());

                if (!$result->error) {
                    $date_today = Carbon::today()->toDateString();
                    $date_yesterday = Carbon::yesterday()->toDateString();
                    $markets = $result->data;

                    foreach ($markets as $market) {
                        if ($market->type == "matka") {
                            foreach ($dbMarkets as $dbMarket) {
                                if ($dbMarket->api_key_name == $market->key) {

                                    if ($dbMarket->previous_day_check) {

                                        if ($market->last_result_date == $date_yesterday) {
                                            $chk_result = MarketResult::where('market_id', $dbMarket->id)->where('result_date', $date_yesterday)->first();
                                            if ($market->matka_3 != null && $chk_result->close_pana === NULL) {
                                                Log::info('GENERAL: auto result api result set close: ' . $dbMarket->name . ' rseult is ' . $market->matka_3);
                                                $request = new stdClass();
                                                $request->date = $date_yesterday;
                                                $request->market = $dbMarket->id;
                                                $request->session = "close";
                                                $request->pana = $market->matka_3;
                                                $request->digit = substr($market->matka_4, 0, 1);
                                                $marketResultSetService->setResult($request, true);
                                            }
                                        } elseif ($market->last_result_date == $date_today) {
                                            $chk_result = MarketResult::where('market_id', $dbMarket->id)->where('result_date', $date_today)->first();

                                            if ($market->matka_1 != null && ($chk_result === NULL || $chk_result->open_pana === NULL)) {
                                                Log::info('GENERAL: auto result api result set open: ' . $dbMarket->name . ' rseult is ' . $market->matka_1);
                                                $request = new stdClass();
                                                $request->date = $date_today;
                                                $request->market = $dbMarket->id;
                                                $request->session = "open";
                                                $request->pana = $market->matka_1;
                                                $request->digit = substr($market->matka_2, 0, 1);
                                                $marketResultSetService->setResult($request, true);
                                            }
                                        }
                                    }


                                    if ($market->last_result_date == $date_today && !$dbMarket->previous_day_check) {

                                        $chk_result = MarketResult::where('market_id', $dbMarket->id)->where('result_date', $date_today)->first();

                                        if ($market->matka_1 != null && ($chk_result === NULL || $chk_result->open_pana === NULL)) {
                                            Log::info('GENERAL: auto result api result set open: ' . $dbMarket->name . ' rseult is ' . $market->matka_1);
                                            $request = new stdClass();
                                            $request->date = $date_today;
                                            $request->market = $dbMarket->id;
                                            $request->session = "open";
                                            $request->pana = $market->matka_1;
                                            $request->digit = substr($market->matka_2, 0, 1);
                                            $marketResultSetService->setResult($request, true);
                                        }
                                        if ($market->matka_3 != null && ($chk_result === NULL || $chk_result->close_pana === NULL)) {
                                            Log::info('GENERAL: auto result api result set close: ' . $dbMarket->name) . ' rseult is ' . $market->matka_3;
                                            $request = new stdClass();
                                            $request->market = $dbMarket->id;
                                            $request->date = $date_today;
                                            $request->session = "close";
                                            $request->pana = $market->matka_3;
                                            $request->digit = substr($market->matka_2, -1);
                                            $marketResultSetService->setResult($request, true);
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    Log::info('auto result api error: ' . $result->message);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
