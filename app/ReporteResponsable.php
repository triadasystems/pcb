<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReporteResponsable extends Model
{
    protected $table="reporte_responsables";

    protected $fillable = [
        'nombre',
        'numero',
        'tipo'
    ];
    
    protected $hidden = [];

    public $timestamps = false;

    public function reporteResponsables() {
        return $responsables = ReporteResponsable::select(
            DB::raw("UPPER(nombre) as nombre"), 
            'numero', 
            DB::raw('
                case 
                    when SUM(tipo) = 1 then "AUTORIZADOR"
                    when SUM(tipo) = 2 then "RESPONSABLE"
                    when SUM(tipo) = 3 then "AUTORIZADOR/RESPONSABLE"
                end AS tipo
            ')
        )
        ->groupBy('nombre', 'numero')->get()->toArray();
    }
}
