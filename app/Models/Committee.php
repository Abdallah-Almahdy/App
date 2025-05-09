<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','img'];

    public static function validate($request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }


    public function sessions(){
        return $this->hasMany(committee_sessions::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function admins()
    {
        return $this->belongsToMany(Admin::class,'admin_committees');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'member_committees')
                    ->withPivot('status');
    }



}
