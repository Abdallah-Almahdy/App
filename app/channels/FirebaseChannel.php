<?php
namespace App\channels;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Notifications\Notification;
use App\Notifications\AppNotification;
use InvalidArgumentException;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Illuminate\Http\JsonResponse;

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

            // If message is already a JsonResponse (error case), return it
            if ($message instanceof JsonResponse) {
                return $message;
            }

            return Firebase::messaging()->send($message);
        } catch (NotFound $e) {
            // Token is no longer valid in Firebase
            if (method_exists($notifiable, 'clearFirebaseToken')) {
                $notifiable->clearFirebaseToken();
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Firebase token is invalid or has expired',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

