<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
    ];

    public static function validate(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);
    }
}
