<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mail extends Model
{
    protected $fillable=[
        "correo", "automatizacion", "bajas"
        ];
}
