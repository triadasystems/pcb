<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\tercerosHistorico;
use App\requestFus;

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
        $bajasDiarias = ReportesTerceros::join('tcs_request_fus', 'tcs_request_fus.tcs_external_employees_id', '=', 'tcs_external_employees.id')
        ->join('tcs_type_low', 'tcs_type_low.id', '=', 'tcs_request_fus.tcs_type_low_id')
        ->select(
            'tcs_external_employees.id',
            'tcs_external_employees.id_external',
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            'tcs_external_employees.initial_date',
            'tcs_external_employees.low_date',
            'tcs_external_employees.status AS tcs_status',
            'tcs_type_low.type AS typelow',
            DB::raw("DATE_FORMAT(tcs_request_fus.created_at, '%d-%m-%Y %H:%i:%s') AS low_date_fus"),
            DB::raw("DATE_FORMAT(tcs_request_fus.real_low_date, '%d-%m-%Y %H:%i:%s') AS real_low_date"),
            DB::raw('CONCAT(tcs_external_employees.name, " ", tcs_external_employees.lastname1, " ", tcs_external_employees.lastname2) AS datos_tercero')
        )
        ->where("tcs_external_employees.status", "=", 2)
        ->where("tcs_request_fus.type", "=", 3)
        ->get()
        ->toArray();

        $response = array();

        foreach($bajasDiarias as $key => $value) {
            $auto_resp = requestFus::select(
                'name',
                'number',
                'tcs_autorizador_responsable.type AS tipo'
            )
            ->join(
                "tcs_autorizador_responsable",
                "tcs_autorizador_responsable.tcs_request_fus_id", 
                "=",
                "tcs_request_fus.id"
            )
            ->where("tcs_external_employees_id", "=", $value["id"])
            ->where("tcs_request_fus.type", "=", 1)
            ->where("tcs_autorizador_responsable.status", "=", 1)
            ->distinct()
            ->get()
            ->toArray();
            
            foreach($auto_resp as $ind => $val) {
                switch ($val["tipo"]) {
                    case 1:
                        if(!isset($value["autorizador"])) {
                            $value["autorizador"] = $val["name"]." | ".$val["number"].",";
                        } else {
                            $value["autorizador"] .= $val["name"]." | ".$val["number"].",";
                        }
                    
                        break;    
                    case 2:
                        if(!isset($value["responsable"])) {
                            $value["responsable"] = $val["name"]." | ".$val["number"].",";
                        } else {
                            $value["responsable"] .= $val["name"]." | ".$val["number"].",";
                        }
                        
                        break;
                }
            }
            
            $value["autorizador"] = substr($value["autorizador"], 0, -1);
            $value["responsable"] = substr($value["responsable"], 0, -1);
            
            $response[] = $value;
        }

        return $response;
    }

    public function activos() {
        $activos = ReportesTerceros::join('tcs_request_fus', 'tcs_request_fus.tcs_external_employees_id', '=', 'tcs_external_employees.id')
        ->select(
            'tcs_external_employees.id',
            'tcs_request_fus.id_generate_fus',
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            DB::raw("DATE_FORMAT(tcs_external_employees.initial_date, '%d-%m-%Y %H:%i:%s') AS initial_date"),
            DB::raw("DATE_FORMAT(tcs_external_employees.low_date, '%d-%m-%Y %H:%i:%s') AS low_date"),
            'tcs_external_employees.status AS tcs_status',
            DB::raw('CONCAT(tcs_external_employees.name, " ", tcs_external_employees.lastname1, " ", tcs_external_employees.lastname2) AS datos_tercero')
        )
        ->where("tcs_external_employees.status", "=", 1)
        ->where("tcs_request_fus.type", "=", 1)
        ->get()
        ->toArray();

        $response = array();

        foreach($activos as $key => $value) {
            $auto_resp = requestFus::select(
                'name',
                'number',
                'tcs_autorizador_responsable.type AS tipo'
            )
            ->join(
                "tcs_autorizador_responsable",
                "tcs_autorizador_responsable.tcs_request_fus_id", 
                "=",
                "tcs_request_fus.id"
            )
            ->where("tcs_external_employees_id", "=", $value["id"])
            ->where("tcs_request_fus.type", "=", 1)
            ->where("tcs_autorizador_responsable.status", "=", 1)
            ->distinct()
            ->get()
            ->toArray();
            
            foreach($auto_resp as $ind => $val) {
                switch ($val["tipo"]) {
                    case 1:
                        if(!isset($value["autorizador"])) {
                            $value["autorizador"] = $val["name"]." | ".$val["number"].",";
                        } else {
                            $value["autorizador"] .= $val["name"]." | ".$val["number"].",";
                        }
                    
                        break;    
                    case 2:
                        if(!isset($value["responsable"])) {
                            $value["responsable"] = $val["name"]." | ".$val["number"].",";
                        } else {
                            $value["responsable"] .= $val["name"]." | ".$val["number"].",";
                        }
                        
                        break;
                }
            }
            
            $value["autorizador"] = substr($value["autorizador"], 0, -1);
            $value["responsable"] = substr($value["responsable"], 0, -1);
            
            $response[] = $value;
        }

        return $response;
    }

    public function responsables() {
        return $responsables = ReportesTerceros::get()
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
            'tcs_external_employees.id_external', 
            'tcs_external_employees.badge_number', 
            'tcs_external_employees.email',
            'tcs_external_employees.name',
            'tcs_external_employees.lastname1',
            'tcs_external_employees.lastname2',
            DB::raw("DATE_FORMAT(tcs_external_employees.initial_date, '%d-%m-%Y %H:%i:%s') AS initial_date"),
            DB::raw("DATE_FORMAT(tcs_external_employees.low_date, '%d-%m-%Y %H:%i:%s') AS low_date"),
            'tcs_external_employees.authorizing_name',
            'tcs_external_employees.authorizing_number',
            'tcs_external_employees.status AS tcs_status',
            'tcs_request_fus.tcs_type_low_id AS typelow',
            'tcs_request_fus.created_at AS low_date_fus',
            DB::raw('UPPER(tcs_request_fus.description) as description'),
            DB::raw("DATE_FORMAT(tcs_request_fus.real_low_date, '%d-%m-%Y %H:%i:%s') AS real_low_date"),
            DB::raw('CONCAT(tcs_external_employees.authorizing_name, " | ", tcs_external_employees.authorizing_number) AS autorizador'),
            DB::raw('CONCAT(tcs_external_employees.responsible_name, " | ", tcs_external_employees.responsible_number) AS responsable')
        )->where("tcs_request_fus.type", "=", 1);

        $trazabilidad = new tercerosHistorico;
        
        return $trazabilidad->trazabilidad($trazabilidadAlta);
    }
}
