<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'bio',
        'phone',
        'linkedin',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

}
