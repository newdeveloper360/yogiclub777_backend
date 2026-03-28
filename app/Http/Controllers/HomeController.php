<?php

namespace App\Http\Controllers;

use App\Models\AppData;
use App\Models\DepositHistory;
use App\Models\DesawarMarket;
use App\Models\GameType;
use App\Models\Market;
use App\Models\SliderImage;
use App\Models\StartLineMarket;
use App\Models\User;
use App\Models\WithdrawHistory;
use App\Notifications\WithdrawRequestAcceptNotification;
use Google\Service\Docs\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function test()
    {
        return 'hi';
    }

    public function privacy()
    {
        return view('webapp.privacy');
    }


    public function verifyUTR($id, $utr, $amount)
    {
        $website = User::where('id', $id)->first();
        if ($website === NULL) {
            $response = [
                "error" => true,
                "message" => "Invalid User"
            ];
            return response()->json($response);
        }

        $payment = DepositHistory::where('utr', $utr)->first();
        if ($payment !== NULL) {
            $response = [
                "error" => true,
                "message" => "UTR already submitted, Please wait for Admin Confirmation or Enter new UTR"
            ];
            return response()->json($response);
        }

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $id;
        $submit_utr->utr = $utr;
        $submit_utr->amount = $amount;
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->save();

        $response = [
            "error" => false,
            "message" => "UTR submitted"
        ];
        return response()->json($response);
    }

    public function payment($user_id, $amount)
    {
        $appData = AppData::first();
        $user = User::find($user_id);
        return view('webapp.payment', compact('user', 'amount', 'appData'));
    }

    public function charts()
    {
        $appData = AppData::first();
        $desawarMarkets = DesawarMarket::with('results')->where('disable_game', false)->latest()->get();

        if ($appData->enable_desawar_only) {
            return view(
                'webapp.charts',
                compact('desawarMarkets', 'appData')
            );
        }

        $startLineMarkets = StartLineMarket::with('results')->where('disable_game', false)->latest()->get();
        $markets = Market::with('results')->where('disable_game', false)->latest()->get();
        return view(
            'webapp.charts',
            compact('startLineMarkets', 'markets', 'desawarMarkets', 'appData')
        );
    }

    private function checkPlayStoreApp($packageName)
    {
        $url = "https://play.google.com/store/apps/details?id={$packageName}";

        $response = Http::get($url);

        if ($response->status() === 200) {
            return true;  // App exists
        } elseif ($response->status() === 404) {
            return false; // App does not exist
        } else {
            return null;  // Some other issue (network error, etc.)
        }
    }

    public function pay()
    {
        return view('webapp.pay');
    }

    public function index()
    {
        $packageName = 'com.mhvmedia.anycallc';
        $exists = $this->checkPlayStoreApp($packageName);

        // if ($exists === true) {
        //     echo "App exists on Google Play Store.";
        // } elseif ($exists === false) {
        //     echo "App does not exist on Google Play Store.";
        // } else {
        //     echo "Unable to check app status.";
        // }
        // return;

        //redirect to yogiclub777.com
        return redirect('https://yogiclub777.com');

        $appData = AppData::first();
        $sliderImages = SliderImage::all();
        $gameTypes = GameType::all();
        $desawarMarkets = DesawarMarket::with('results')->where('disable_game', false)
            ->orderBy('open_time', 'asc')
            ->get();

        if ($appData->enable_desawar_only) {
            return view(
                'webapp.index',
                compact('gameTypes', 'desawarMarkets', 'sliderImages', 'appData')
            );
        }

        $startLineMarkets = StartLineMarket::with('results')
            ->orderBy('open_time', 'asc')
            ->get();

        //markets order by open time
        $markets = Market::with('results')->where('disable_game', false)
            ->orderBy('open_time', 'asc')
            ->get();
        return view(
            'webapp.index',
            compact('gameTypes', 'startLineMarkets', 'markets', 'desawarMarkets', 'sliderImages', 'appData')
        );
    }

    public function chart()
    {
        return view('webapp.chart');
    }

    public function download()
    {
        $headers = [
            'Content-Description' => 'File Download',
            'Content-Type' => 'application/vnd.android.package-archive',
        ];
        return response()->download(public_path('game.apk'), 'game.apk', $headers);
    }
}
