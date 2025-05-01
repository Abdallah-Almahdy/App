<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'product_id',
    ];
    public static function validate($request)
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
    }
    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
