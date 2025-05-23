<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member_committee extends Model
{
    use HasFactory;

    protected $table = 'member_committees';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'committee_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class, 'committee_id', 'id');
    }
}
