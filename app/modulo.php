<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modulo extends Model
{
    protected $table = 'modulos';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "modulename",
        "status",
        "description"
    ];
}
