<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class permiso extends Model {
    protected $table="permits_users";

    protected $fillable = [
        "profiles_users_id", "reading", "writing", "upgrade", "send_email", "execution"
    ];

    public $timestamps = false;

}
