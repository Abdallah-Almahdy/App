<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class committee_sessions extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "date",
        'user_id',
        'committee_id',
        'link'
    ];

    public static function validate($request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'date' => 'required|date',
            'link' => 'required|url',
        ]);


    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function user()
    {
        return $this->belongsTo(admin::class);
    }
}
