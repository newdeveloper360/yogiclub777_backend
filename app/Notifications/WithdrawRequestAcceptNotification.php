<?php

namespace App\Notifications;

use App\Http\Controllers\Dashboard\NotificationController;
use App\Models\AppData;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Helpers\OneSignalHelper;

class WithdrawRequestAcceptNotification extends Notification
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
            $message = "Your withdrawal of ₹' . $this->amount . ' has been Approved";
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
        //                     'body' => 'Your withdrawal of ₹' . $this->amount . ' has been Approved',
        //                     'title' => 'Withdraw Request Accepted',
        //                 ],
        //                 // 'data' => [
        //                 //     'message' => '₹' . $this->amount,
        //                 //     'title' => 'Withdraw Approved',
        //                 // ],
        //             ],
        //         ],
        //     ]);
        //     $body = $response->getBody();
        // } catch (\Throwable $th) {
        // }
    }
}
