<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'authorizing_name',
        'authorizing_number',
        'responsible_name',
        'responsible_number',
        'created_at',
        'status',
        'tcs_fus_ext_hist',
        'tcs_applications_ids'
    ];
    
    protected $hidden = [
        'tcs_subfijo_id', 'tcs_externo_proveedor',
    ];

    public function trazabilidad($union = null) {

        $reporteTerceros = new ReportesTerceros;

        return $trazabilidad = tercerosHistorico::join('tcs_request_fus', 'tcs_request_fus.id', '=', 'tcs_external_employees_hist.tcs_fus_ext_hist')
        ->join('tcs_type_low', 'tcs_type_low.id', '=', 'tcs_request_fus.tcs_type_low_id')
        ->select(
            'tcs_external_employees_hist.badge_number', 
            'tcs_external_employees_hist.email',
            'tcs_external_employees_hist.name',
            'tcs_external_employees_hist.lastname1',
            'tcs_external_employees_hist.lastname2',
            'tcs_external_employees_hist.initial_date',
            'tcs_external_employees_hist.low_date',
            'tcs_external_employees_hist.authorizing_name',
            'tcs_external_employees_hist.authorizing_number',
            'tcs_external_employees_hist.status AS tcs_status',
            'tcs_type_low.type AS typelow',
            'tcs_request_fus.created_at AS low_date_fus',
            'tcs_request_fus.real_low_date'
        )
        ->union($union)
        ->get()
        ->toArray();
    }
}
