<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\tercerosHistorico;

class ReportesTerceros extends Model
{
    protected $table="tcs_external_employees";

    protected $fillable = [
        'id',
        'id_external',
        'name',
        'lastname1',
        'lastname2',
        'initial_date',
        'low_date',
        'badge_number',
        'email',
        'authorizing_name',
        'authorizing_number',
        'responsible_name',
        'responsible_number',
        'created_at',
        'status',
    ];

    protected $hidden = [
        'tcs_subfijo_id', 'tcs_externo_proveedor',
    ];

    public $timestamps = false;

    public function bajasDiarias() {
        return $bajasDiarias = ReportesTerceros::join('tcs_request_fus', 'tcs_request_fus.tcs_external_employees_id', '=', 'tcs_external_employees.id')
        ->join('tcs_type_low', 'tcs_type_low.id', '=', 'tcs_request_fus.tcs_type_low_id')
        ->select(
            'tcs_external_employees.id_external',
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            'tcs_external_employees.initial_date',
            'tcs_external_employees.low_date',
            'tcs_external_employees.authorizing_name',
            'tcs_external_employees.authorizing_number',
            'tcs_external_employees.status AS tcs_status',
            'tcs_type_low.type AS typelow',
            'tcs_request_fus.created_at AS low_date_fus',
            'tcs_request_fus.real_low_date',
            DB::raw('CONCAT(tcs_external_employees.name, " ", tcs_external_employees.lastname1, " ", tcs_external_employees.lastname2) AS datos_tercero'),
            DB::raw('CONCAT(tcs_external_employees.authorizing_name, " | ", tcs_external_employees.authorizing_number) AS autorizador'),
            DB::raw('CONCAT(tcs_external_employees.responsible_name, " | ", tcs_external_employees.responsible_number) AS responsable')
        )
        ->where("tcs_external_employees.status", "=", 2)
        ->where("tcs_request_fus.type", "=", 3)
        ->get()
        ->toArray();
    }

    public function activos() {
        return $activos = ReportesTerceros::join('tcs_request_fus', 'tcs_request_fus.tcs_external_employees_id', '=', 'tcs_external_employees.id')
        ->select(
            'tcs_request_fus.id_generate_fus',
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            'tcs_external_employees.initial_date',
            'tcs_external_employees.low_date',
            'tcs_external_employees.authorizing_name',
            'tcs_external_employees.authorizing_number',
            'tcs_external_employees.responsible_name',
            'tcs_external_employees.responsible_number',
            'tcs_external_employees.status AS tcs_status',
            DB::raw('CONCAT(tcs_external_employees.name, " ", tcs_external_employees.lastname1, " ", tcs_external_employees.lastname2) AS datos_tercero'),
            DB::raw('CONCAT(tcs_external_employees.authorizing_name, " | ", tcs_external_employees.authorizing_number) AS autorizador'),
            DB::raw('CONCAT(tcs_external_employees.responsible_name, " | ", tcs_external_employees.responsible_number) AS responsable')
        )
        ->where("tcs_external_employees.status", "=", 1)
        ->where("tcs_request_fus.type", "=", 1)
        ->get()
        ->toArray();
    }

    public function responsables() {
        return $responsables = ReportesTerceros::select('')
        ->get()
        ->toArray();
    }

    public function trazabilidad() {
        $trazabilidadAlta = ReportesTerceros::join(
            'tcs_request_fus', 
            'tcs_request_fus.tcs_external_employees_id', 
            '=', 
            'tcs_external_employees.id'
        )
        ->select(
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            'tcs_external_employees.initial_date',
            'tcs_external_employees.low_date',
            'tcs_external_employees.authorizing_name',
            'tcs_external_employees.authorizing_number',
            'tcs_external_employees.status AS tcs_status',
            'tcs_request_fus.tcs_type_low_id AS typelow',
            'tcs_request_fus.created_at AS low_date_fus',
            'tcs_request_fus.real_low_date',
            DB::raw('CONCAT(tcs_external_employees.authorizing_name, " | ", tcs_external_employees.authorizing_number) AS autorizador')
        )->where("tcs_request_fus.type", "=", 1);

        $trazabilidad = new tercerosHistorico;
        
        return $trazabilidad->trazabilidad($trazabilidadAlta);
    }
}
