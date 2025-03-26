<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','link','user_id','committee_id'];


    public static function validate($request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'link' => 'required|url',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }


}
