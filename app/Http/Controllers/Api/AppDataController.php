<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppDataController extends Controller
{

    public function index(Request $request)
    {
        $notifications = Notification::latest()->paginate(50, ['*'], 'notifications', $request->page);
        $notification_count = $notifications->count();

        $appData = AppData::first();

        $appData['payment_url'] = env('APP_URL') . '/payment/';
        // $appData['slider_url'] = 'https://google.com';
        $appData['homepage_image_url'] = env('APP_URL') . $appData->homepage_image_url;
        $appData['min_transfer'] = env('MIN_TRANSFER');
        // $appData['result_history_webview_url'] = 'https://www.babajiisatta.com/result-chart.php';
        // $appData['result_history_webview_url'] = 'https://shreeshyamsatta.online/';
        $appData['result_history_webview_url'] = 'https://api.yogiclub777.com/charts';
        $appData['notification_count'] = $notification_count;
        $appData['rate_app_link'] = "https://google.com";

        $appData['video_link_iphone'] = "https://api.mahakalmatka.com/iphone.mp4";
        $appData['video_link_android'] = $appData['pusher_key'] = env('PUSHER_APP_KEY');
        $appData['pusher_cluster'] = env('PUSHER_APP_CLUSTER');
        $appData['base_domain'] = env('APP_URL');
        "https://api.mahakalmatka.com/android.mp4";
        $appData['min_upi_gateway'] = env('MIN_AMOUNT_BABA_FOR_UPI_GATEWAY', 200);


        $token = $request->bearerToken();
        $message = "Done";

        if (!blank($token)) {
            $token_user = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if ($token_user === NULL) {
                $data = compact('appData');
                return response()->success($message, $data);
            }

            $user = $token_user->tokenable;

            if (blank($user)) {
                $data = compact('appData');
                return response()->success($message, $data);
            }
            $withdrawDetails = $user->withdrawDetails;
            $data = compact('appData', 'user');
            return response()->success($message, $data);
        }
        $data = compact('appData');
        return response()->success($message, $data);
    }

    //get notifications
    public function getNotifications(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
        ]);
        $notifications = Notification::latest()->paginate(50, ['*'], 'notifications', $request->page);
        $notification_count = $notifications->count();
        return response()->success("Data Sent", compact('notifications', 'notification_count'));
    }
}
