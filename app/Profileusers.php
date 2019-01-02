<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profileusers extends Model
{
    protected $table = 'profiles_users';
    protected $primaryKey = 'id';
    
    protected $fillable = ['id','profilename','description','status'];

    protected $hidden = [
        'created_at', 'update_at'
    ];

    // public function relProfilesModules() {
    //     return $this->hasMany('App\Relprofilesmodules', 'profiles_users_id', 'id');
    // }
}
