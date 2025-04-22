<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [

        "title",
        "description",
        "user_id",
        'image'
    ];



    public static function validate(Request $request){

    return $request->validate([
    "title" => "required|string",
    "description" => "required|string",
    'image' => 'required|image|mimes:jpeg,png,jpg,gif',
    ]);
    }


    public function user(){

        return $this->belongsTo(Admin::class);
    }



}
