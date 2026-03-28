<?php

namespace App\Jobs;

use App\Models\DesawarMarket;
use App\Models\DesawarResult;
use App\Services\DesawarResultSetService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use stdClass;

class AutoResultDesawarMarket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $desawarResultSetService = new DesawarResultSetService();
        $dbMarkets = DesawarMarket::where("auto_result", TRUE)->get();
        $date = Carbon::today()->toDateString();

        $client = new Client();
        $url = env('API_URL');
        try {
            $response = $client->request('POST', $url);


            if ($response->getStatusCode() == 200) {

                $result = json_decode($response->getBody()->getContents());
                if (!$result->error) {

                    $markets = $result->data;
                    foreach ($markets as $market) {

                        if ($market->type == "satta") {
                            foreach ($dbMarkets as $dbMarket) {


                                if ($dbMarket->api_key_name == $market->key && $market->last_result_date == $date) {

                                    $chk_result = DesawarResult::where('desawar_market_id', $dbMarket->id)->where('result_date', $date)->first();

                                    if ($chk_result === NULL) {
                                        Log::info('DESAWAR: auto result api result set: ' . $dbMarket->name);

                                        $digit = $market->satta_result_today;
                                        $request = new stdClass();
                                        $request->date = $date;
                                        $request->market = $dbMarket->id;
                                        $request->digit = $digit;
                                        $desawarResultSetService->setResult($request, true);
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
