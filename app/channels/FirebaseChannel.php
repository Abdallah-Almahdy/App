<?php
namespace App\Channels;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Notifications\Notification;
use App\Notifications\AppNotification;
use InvalidArgumentException;

class FirebaseChannel
{
    /**
     * Send the notification via Firebase Cloud Messaging.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function send($notifiable, Notification $notification): mixed
    {
        if (!($notification instanceof AppNotification)) {
            throw new InvalidArgumentException(
                'The notification must be an instance of AppNotification'
            );
        }

        if (!method_exists($notifiable, 'routeNotificationForFirebase')) {
            throw new InvalidArgumentException(
                'Notifiable entity must implement routeNotificationForFirebase() method'
            );
        }

        try {
            $message = $notification->toFirebase($notifiable);
            return Firebase::messaging()->send($message);
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }
}

