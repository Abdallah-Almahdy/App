<?php
namespace App\Channels;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Notifications\Notification;
use App\Notifications\appNotifcation;

class FirebaseChannel
{
    /**
     * @param mixed $notifiable
     * @param appNotifcation|Notification $notification
     */
    public function send($notifiable, $notification)
    {
        if (!($notification instanceof appNotifcation)) {
            throw new \RuntimeException('Notification must be an instance of appNotifcation');
        }

        $message = $notification->toFirebase($notifiable);
        return Firebase::messaging()->send($message);
    }
}

