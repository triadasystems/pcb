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
            'tipo'
        )
        ->get()
        ->toArray();
    }
}
