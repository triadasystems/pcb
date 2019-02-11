<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserTerceros extends Model
{
    protected $table = "tcs_users_sessions";

    protected $fillable = [
        'id', 'name', 'mail', 'noEmployee', 'create_at', 'update_at'
    ];

    protected $hidden = [
        'remember_token',
    ];

    public $timestamps = false;
}
