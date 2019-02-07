<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\requestFus;

class autorizador_responsable extends Model
{
    protected $table="tcs_autorizador_responsable";
    protected $fillable=[
        'id',
        'name',
        'number',
        'type',
        'status',
        'tcs_request_fus_id'
    ];
    
    protected $hidden = [];

    public $timestamps = false;

    public function listar($id)
    {
        $consultas = autorizador_responsable::select(
            'tcs_request_fus.id_generate_fus AS fus',
            'tcs_autorizador_responsable.name AS nombre ',
            'tcs_autorizador_responsable.number as numero',
            DB::raw('CONCAT(tcs_autorizador_responsable.name," | ",tcs_autorizador_responsable.number) AS datos_fus'),
            DB::raw('if(tcs_autorizador_responsable.type=1, "Autorizador", "Responsable") AS tipo'),
            'tcs_request_fus.description AS descripcion')
        ->join('tcs_request_fus','tcs_autorizador_responsable.tcs_request_fus_id','=','tcs_request_fus.id')
        ->where('tcs_autorizador_responsable.status','=','1')
        ->where('tcs_request_fus.tcs_external_employees_id','=',$id)
        ->get()->toArray(); 
        return $consultas; 
    }
}
?>