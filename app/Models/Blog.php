<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Blog extends Model
{

    protected $fillable = [

        "title",
        "description",
    ];
    use HasFactory;


    public static function validate(Request $request){

    return $request->validate([
    "title" => "required|string",
    "description" => "required|string",
    ]);



    }
}
