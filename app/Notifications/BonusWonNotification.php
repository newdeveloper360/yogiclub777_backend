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

class BonusWonNotification extends Notification
{
    use Queueable;
    protected $amount;
    protected $fcm;
    protected $playerId;

    /**
     * Create a new notification instance.
     */
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
            $message = "Congratulations. Your Won. ₹' . $this->amount   . ' in Bonus.";
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
        //                     'body' => 'Congratulations. Your Won. ₹' . $this->amount   . ' in Bonus.',
        //                     'title' => 'Bonus Won',
        //                 ],
        //                 // 'data' => [
        //                 //     'message' => '₹' . $this->amount,
        //                 //     'title' => 'Bonus Won',
        //                 // ],
        //             ],
        //         ],
        //     ]);
        //     $body = $response->getBody();
        // } catch (\Throwable $th) {
        // }
    }
}
