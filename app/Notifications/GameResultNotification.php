<?php

namespace App\Notifications;

use App\Http\Controllers\Dashboard\NotificationController;
use App\Models\AppData;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Helpers\OneSignalHelper;

class GameResultNotification extends Notification
{
    use Queueable;

    protected $userFcms = [];
    protected $market;
    protected $result;

    public function __construct($market, $result, $userFcms)
    {
        $this->market = $market;
        $this->userFcms = $userFcms;
        $this->result = $result;
    }

    public function via()
    {
        return $this->toFcm();
    }


    public function toFcm()
    {
        // OneSignal Notification
        try {
            $message = "$this->market result is $this->result.";
            OneSignalHelper::allUsersNotification($message);
        } catch (\Throwable $th) {
        }
        
        // $notificationController = new NotificationController();
        // $client = $notificationController->getFirebaseClientUrl();
        // if ($client == NULL) {
        //     return;
        // }

        // try {
        //     $response = $client->post(env('FIREBASE_URL'), [
        //         'json' => [
        //             'message' => [
        //                 // 'token' => '$userToken',
        //                 'topic' => 'daily_messaging_all_users',
        //                 'notification' => [
        //                     'body' => "$this->market result is $this->result.",
        //                     'title' => 'Game Results',
        //                 ],
        //                 'data' => [
        //                     'message' => $this->result,
        //                     'title' => $this->market,
        //                 ],
        //             ],
        //         ],
        //     ]);
        //     $body = $response->getBody();
        // } catch (\Throwable $th) {
        // }
    }
}
