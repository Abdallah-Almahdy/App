<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Expr\FuncCall;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Committees()
    {
        return $this->belongsToMany(Committee::class, 'member_committees');
    }

    public function routeNotificationForFirebase()
    {
        return $this->fcm_token; // or however you store the FCM token
    }

    public function clearFirebaseToken()
    {
        $this->fcm_token = null;
        $this->save();
    }

    public function profile()
    {
        return $this->hasOne(userProfile::class, 'user_id');
    }
}
