<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    protected $table = 'applications';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'instance_id',
        'responsability_id',
        'name',
        'alias',
        'active'
    ];
    public function recuperarapps()
    {
        $consultas= Applications::where("active","=","1")
        ->get()
        ->toArray();
        // print_r($consultas);
        // die();
        return $consultas;
    }
}
