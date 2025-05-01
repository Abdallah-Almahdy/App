<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'link',
        'price',
    ];
    public function collection()
    {
        return $this->belongsToMany(collection::class, 'products_collections');
    }

    public static function validate($request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'link' => 'nullable|url',
            'price' => 'required|numeric',

        ]);
    }


}
