<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin_committe extends Model
{
    use HasFactory;


    protected $fillable = [

        'admin_id',
        'committee_id'
    ];

    public function admins()
    {
        return $this->belongsToMany(Admin::class);
    }
}
