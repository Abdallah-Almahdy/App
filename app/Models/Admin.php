<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'name',
        'email',
        'password',
        'title'
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
        return $this->morphOne(Profile::class, 'profilable');
    }

}
