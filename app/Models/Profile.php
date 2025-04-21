<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $primaryKey = null;  // No single primary key since we're using a composite key

    protected $fillable = [
        'image',
        'bio',
        'phone',   
        'linkedin',
        'profilable_type',
        'profilable_id'
    ];



    public function profilable()
    {
        return $this->morphTo();
    }
}
