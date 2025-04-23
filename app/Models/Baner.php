<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baner extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'link',
        'type',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
