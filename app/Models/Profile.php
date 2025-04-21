<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = ['profilable_type', 'profilable_id'];

    protected $fillable = [
        'image',
        'bio',
        'phone',
        'linkedin'
    ];

    public function profilable()
    {
        return $this->morphTo();
    }
}
