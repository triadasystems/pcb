<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mailSendModel extends Model
{
    protected $table = 'mails';
    protected $primaryKey = 'id';
    protected $fillable=[
        "correo", "automatizacion", "bajas"
        ];
    /*public function recuperaConcilia()
    {

    }*/
}
