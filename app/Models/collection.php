<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'total',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_collections');
    }

    public static function validate($request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'total' => 'nullable|integer',
            'products_id' => 'required|array',
            'products_id.*' => 'required|exists:products,id',
        ]);
    }



}
