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
            'collection_id' => 'required|exists:collections,id',
        ]);
    }
    public function collection()
    {
        return $this->belongsTo(collection::class);
    }
}
