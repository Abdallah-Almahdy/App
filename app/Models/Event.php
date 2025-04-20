<?php

namespace App\Models;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        "date"
    ];



    public static function validate(Request $request)
    {
        return $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'date' => 'required|date',
        ]);
    }

    public function images()
    {

        return $this->hasMany(event_images::class);
    }

    public static function storeImages(Request $request, $event_id)
    {
        $request->validate(["images" => "required|array",
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        $images = $request->file('images');

        foreach ($images as $image) {
            $path = Cloudinary::upload($image->getRealPath())->getSecurePath();

            event_images::create([
                'image_path' => $path,
                'event_id' => $event_id
            ]);
        }
    }
}
