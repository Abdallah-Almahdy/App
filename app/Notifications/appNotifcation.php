<?php

namespace App\Notifications;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Notifications\Notification;

class appNotifcation extends Notification
{
    protected $title;
    protected $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable)
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        $deviceToken = $notifiable->routeNotificationFor('firebase');

        $notification = FirebaseNotification::create($this->title, $this->body);

        return CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);
    }
}
