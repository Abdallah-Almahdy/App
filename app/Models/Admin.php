<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens,Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'title',
        'fcm_token'
    ];
    protected $hidden = [
        'password',
        'is_super_admin'
    ];

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'admin_committees');
    }
    public function profile()
    {
        return $this->hasOne(AdminProfile::class, 'user_id');
    }


    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Route notifications for the Firebase channel.
     *
     * @return string|array
     */
    public function routeNotificationForFirebase()
    {
        // Return the FCM token or device token for the admin
        // You might want to store this in a separate column in your admins table
        return $this->fcm_token;
    }

     public static function getPublicIdFromUrl($url)
    {
        $urlPath = parse_url($url, PHP_URL_PATH); // /demo/image/upload/v1674321234/folder_name/my_image.jpg
        $parts = explode('/', $urlPath);

        // Remove version number (e.g., v1674321234)
        $versionIndex = array_search('upload', $parts) + 1;
        if (isset($parts[$versionIndex]) && str_starts_with($parts[$versionIndex], 'v')) {
            unset($parts[$versionIndex]);
        }

        // Get everything after 'upload/'
        $uploadIndex = array_search('upload', $parts);
        $publicParts = array_slice($parts, $uploadIndex + 1);

        // Remove extension
        $file = array_pop($publicParts);
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $publicParts[] = $fileName;

        return implode('/', $publicParts);
    }
}
