<?php

namespace App\Notifications;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification
{
    protected $title;
    protected $body;

    public function __construct(string $title, string $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable): array
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable): CloudMessage
    {
        $deviceToken = $notifiable->routeNotificationFor('firebase');

        $notification = FirebaseNotification::create($this->title, $this->body);

        return CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);
    }
}
