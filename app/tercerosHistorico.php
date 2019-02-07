<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\AutorizadorResponsable;
// use App\ReportesTerceros;

class tercerosHistorico extends Model
{
    protected $table="tcs_external_employees_hist";

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
        'created_at',
        'status',
        'tcs_fus_ext_hist',
        'tcs_applications_ids',
        'authorizing_name',
        'authorizing_number',
        'responsible_name',
        'responsible_number'
    ];
    
    protected $hidden = [
        'tcs_subfijo_id', 'tcs_externo_proveedor',
    ];

    public $timestamps = false;

    public function trazabilidad(/*$union = null*/) {
        // $reporteTerceros = new ReportesTerceros;

        return $trazabilidad = tercerosHistorico::join(
            'tcs_request_fus', 
            'tcs_request_fus.id', '=', 'tcs_external_employees_hist.tcs_fus_ext_hist'
        )
        ->leftJoin(
            'tcs_type_low', 'tcs_type_low.id', '=', 'tcs_request_fus.tcs_type_low_id'
        )
        ->select(
            'tcs_external_employees_hist.id_external',
            'tcs_external_employees_hist.badge_number', 
            'tcs_external_employees_hist.email',
            'tcs_external_employees_hist.name',
            'tcs_external_employees_hist.lastname1',
            'tcs_external_employees_hist.lastname2',
            DB::raw("DATE_FORMAT(tcs_external_employees_hist.initial_date, '%d-%m-%Y') AS initial_date"),
            DB::raw("DATE_FORMAT(tcs_external_employees_hist.low_date, '%d-%m-%Y') AS low_date"),
            'tcs_external_employees_hist.authorizing_name',
            'tcs_external_employees_hist.authorizing_number',
            'tcs_external_employees_hist.status AS tcs_status',
            'tcs_type_low.type AS typelow',
            DB::raw("DATE_FORMAT(tcs_request_fus.created_at, '%d-%m-%Y %H:%i:%s') AS low_date_fus"),
            DB::raw('UPPER(tcs_request_fus.description) as description'),
            DB::raw("DATE_FORMAT(tcs_request_fus.real_low_date, '%d-%m-%Y %H:%i:%s') AS real_low_date"),
            DB::raw('CONCAT(tcs_external_employees_hist.authorizing_name, " | ", tcs_external_employees_hist.authorizing_number) AS autorizador'),
            DB::raw('CONCAT(tcs_external_employees_hist.responsible_name, " | ", tcs_external_employees_hist.responsible_number) AS responsable')
        )
        // ->union($union)
        ->get()
        ->toArray();
    }

    public function sustitucionHistorico($data, $idFus) {
        $historicoTerceros = new tercerosHistorico;
        $historicoTerceros->id_external = $data["id_external"];
        $historicoTerceros->name = $data["name"];
        $historicoTerceros->lastname1 = $data["lastname1"];
        $historicoTerceros->lastname2 = $data["lastname2"];
        $historicoTerceros->initial_date = $data["initial_date"];
        $historicoTerceros->low_date = $data["low_date"];
        $historicoTerceros->badge_number = $data["badge_number"];
        $historicoTerceros->email = $data["email"];
        $historicoTerceros->created_at = $data["created_at"];
        $historicoTerceros->status = $data["status"];
        $historicoTerceros->tcs_fus_ext_hist = $idFus;
        $historicoTerceros->tcs_applications_ids = $data["tcs_applications_ids"];
        $historicoTerceros->tcs_subfijo_id = $data["tcs_subfijo_id"];
        $historicoTerceros->tcs_externo_proveedor = $data["tcs_externo_proveedor"];

        $historicoTerceros->authorizing_name = $data["authorizing_name"];
        $historicoTerceros->authorizing_number = $data["authorizing_number"];
        $historicoTerceros->responsible_name = $data["responsible_name"];
        $historicoTerceros->responsible_number = $data["responsible_number"];
        
        $historicoTerceros->save();
    }
}
