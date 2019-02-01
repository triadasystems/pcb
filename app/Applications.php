<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        $consultas= Applications::select(
            'id',
            DB::raw('UPPER(name) AS name'),
            DB::raw('UPPER(alias) AS alias'),
            'active'
        )
        ->get()
        ->toArray();
        return $consultas;
    }
    public function store($nom,$alias)
    {
        $sql= new Applications;
        $sql->instance_id = 1;
        $sql->responsability_id=1;
        $sql->name = mb_strtoupper($nom);
        $sql->alias = mb_strtoupper($alias);
        $sql->active = 1;
        $sql->save();
    }
}
