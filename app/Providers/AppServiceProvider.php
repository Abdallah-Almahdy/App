<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Channels\FirebaseChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
     */
    public function boot(): void
    {
        // Handle Firebase credentials from environment variable
        if ($credentials = config('firebase.projects.app.credentials')) {
            $credentialsJson = base64_decode($credentials);

            if (json_decode($credentialsJson) === null) {
                throw new \InvalidArgumentException('Invalid Firebase credentials JSON');
            }

            // Create storage directory if it doesn't exist
            $storageDir = storage_path('app/firebase');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $tempFilePath = storage_path('app/firebase/firebase-credentials.json');
            file_put_contents($tempFilePath, $credentialsJson);
            chmod($tempFilePath, 0600); // Set proper permissions for credentials file

            // Set the environment variable to use the JSON file
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $tempFilePath);

            // Update the Firebase configuration
            config([
                'firebase.projects.app.credentials' => $tempFilePath,
                'firebase.credentials' => $tempFilePath
            ]);
        }

        Notification::extend('firebase', function ($app) {
            return new FirebaseChannel();
        });
    }
}
