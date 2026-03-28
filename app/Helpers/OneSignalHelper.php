<?php

namespace App\Helpers;


use OneSignal;

class OneSignalHelper
{
    // Send Singla User Notification using OneSignal
    public static function singleUserNotification($message, $playerId){
        
        OneSignal::sendNotificationToUser(  // sendNotificationToAll
            $message,
            $playerId,
            $url = 'https://yogiclub777.com/canceled-history'
        );
    }

    // Send All Users Notification using OneSignal
    public static function allUsersNotification($message, $url='https://yogiclub777.com'){
        
        OneSignal::sendNotificationToAll(
            $message,
            $url = $url
        );
    }

}