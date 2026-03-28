<?php

namespace App\Notifications;

use App\Helpers\OneSignalHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $fcm;
    protected $playerId;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $fcm, $playerId)
    {
        $this->message = $message;
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
            $message = $this->message;
            $playerId = $this->playerId;
            OneSignalHelper::singleUserNotification($message, $playerId);
        } catch (\Throwable $th) {
        }
    }



    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
