<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class event_images extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'image_path',
        ];

        protected $hidden = ["event_id", "created_at", "updated_at"];

    public function event(){
        return $this->belongsTo(event::class,'event_id');
    }
}


