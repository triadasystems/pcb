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
            'nombre', 
            'numero', 
            DB::raw('
                case 
                    when SUM(tipo) = 1 then "Autorizador"
                    when SUM(tipo) = 2 then "Responsable"
                    when SUM(tipo) = 3 then "Autorizador/Responsable"
                end AS tipo
            ')
        )
        ->groupBy('nombre', 'numero')->get()->toArray();
    }
}
