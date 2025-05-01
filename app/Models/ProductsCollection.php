<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsCollection extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'collection_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
    public static function validate($request)
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
            'collection_id' => 'required|exists:collections,id',
        ]);
    }
    


}
