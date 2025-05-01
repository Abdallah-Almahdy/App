<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collectionOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'collection_id',
    ];
    public static function validate($request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'collection_id' => 'required|exists:collections,id',
        ]);
    }
    public function collection()
    {
        return $this->belongsTo(collection::class);
    }
}
