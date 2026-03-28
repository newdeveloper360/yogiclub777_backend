<?php

namespace App\Notifications;

use App\Http\Controllers\Dashboard\NotificationController;
use App\Models\AppData;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Helpers\OneSignalHelper;

class GameWinNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $fcm;
    protected $playerId;

    public function __construct($amount, $fcm, $playerId)
    {
        $this->amount = $amount;
        $this->fcm = $fcm;
        $this->playerId = $playerId;
    }

    public function via()
    {
        return $this->toFcm();
    }


    public function toFcm()
    {
        // OneSignal Notification
        try {
            $message = "You won game and amount is ₹' . $this->amount";
            $playerId = $this->playerId;
            OneSignalHelper::singleUserNotification($message, $playerId);
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
        //                 'token' => "$this->fcm",
        //                 // 'topic' => 'daily_messaging_all_users',
        //                 'notification' => [
        //                     'body' => 'You won game and amount is ₹' . $this->amount,
        //                     'title' => 'Game Win',
        //                 ],
        //                 // 'data' => [
        //                 //     'message' => '₹' . $this->amount,
        //                 //     'title' => 'GAME WIN',
        //                 // ],
        //             ],
        //         ],
        //     ]);
        //     $body = $response->getBody();
        // } catch (\Throwable $th) {
        // }
    }
}
