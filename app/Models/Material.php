<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Material extends Model
{
    use HasFactory;


    protected $fillable = [
        "name",
        "semester",
        "department",
        "link"
    ];


    public static function validate(Request $request){

        return $request->validate([
        "name" => "required|string",
        "semester" => "required|string",
        "department" => "required|string",
        "link" => "required|string"
        ]);
    }
}

