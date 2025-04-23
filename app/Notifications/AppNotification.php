<?php

namespace App\Notifications;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Exception\MessagingException;
use Illuminate\Http\JsonResponse;

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

    public function toFirebase($notifiable): CloudMessage|JsonResponse
    {
        try {
            $deviceToken = $notifiable->routeNotificationFor('firebase');

            if (!$deviceToken) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Device token not found'
                ], 404);
            }

            $notification = FirebaseNotification::create($this->title, $this->body);

            return CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification);

        } catch (MessagingException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid device token',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
