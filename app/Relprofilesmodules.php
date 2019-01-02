<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relprofilesmodules extends Model
{
    protected $table = 'rel_profiles_modules';
    
    public $timestamps = false;

    protected $primaryKey = 'profiles_users_id';
   
    protected $fillable = ['profiles_users_id', 'module_users_id'];
}
