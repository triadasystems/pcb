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
    public static function recuperarapps()
    {
        $consultas= Applications::get()
        ->toArray();
        return $consultas;
    }
    public function store($nom,$alias)
    {
        $sql= new Applications;
        $sql->instance_id = 1;
        $sql->responsability_id=1;
        $sql->name = $nom;
        $sql->alias = $alias;
        $sql->active = 1;
        $sql->save();
    }
}
