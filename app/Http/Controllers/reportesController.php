<?php

namespace App\Http\Controllers;

use App\Compareapplicationsconcilia;
use App\Comparelaboraconcilia;
use App\Compareapplicationsactive;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;

class reportesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporteAutomatizacion() {
        // Con subquery
        
        $reporte = Compareapplicationsconcilia::select('compare_applications_concilia.employee_number as idemp', 'compare_applications_concilia.name as nombre', 'compare_applications_concilia.lastname1 as apellidos', 'compare_applications_concilia.created as fcreado', 'applications.name as alias')
        ->whereNotIn('employee_number', function($query) {
            $query->select('employee_number')
            ->from(with(new Comparelaboraconcilia)->getTable());
        })
        ->Join('applications', 'applications.id', '=', 'compare_applications_concilia.application_id')
        ->get()->toArray();

        $resultado = array();
        $count = 0;
        foreach($reporte as $correo) {
            foreach($correo as $index => $value){
                switch ($index) {
                    case 'alias':
                        $value = utf8_encode($value);
                        break;
                }
                $resultado[$count][$index] = $value;
            }
            $count = $count+1;
        }
        
        $count = 0;
        
        // echo '<pre>';print_r($resultado);echo '</pre>';
        // die();
        return Datatables::of($resultado)->make(true);
        // return response()->json([
        //     $reporte
        // ]);
    }
    public function reporteBajas() 
    {

        $sql = "
            SELECT 
                compare_applications_active.employee_number as idemp, 
                compare_applications_active.name as nombre, 
                compare_applications_active.lastname1 as apellidos, 
                compare_applications_active.created as fcreado, 
                applications.name as alias,
                compare_labora_remove.motivo_baja
            FROM 
                compare_applications_active
            INNER JOIN 
                compare_labora_remove 
            ON 
                compare_labora_remove.employee_number = compare_applications_active.employee_number
            INNER JOIN 
                applications 
            ON 
                applications.id = compare_applications_active.application_id
            WHERE 
                compare_applications_active.employee_number = compare_labora_remove.employee_number
        ";
        
        $reporte = DB::select(DB::raw($sql));
        
        $resultado = array();
        $count = 0;
        foreach($reporte as $correo) {
            foreach($correo as $index => $value){
                switch ($index) {
                    case 'alias':
                        $value = utf8_encode($value);
                        break;
                }
                $resultado[$count][$index] = $value;
            }
            $count = $count+1;
        }
        
        $count = 0;

        return Datatables::of($resultado)->make(true);
        // return response()->json([
        //     $reporte
        // ]);
    }
    public function reporteAutomatizacionMail() {
        // Con subquery
        
        $reporte = Compareapplicationsconcilia::select('compare_applications_concilia.employee_number as idemp', 'compare_applications_concilia.name as nombre', 'compare_applications_concilia.lastname1 as apellidos', 'compare_applications_concilia.created as fcreado', 'applications.name as alias')
        ->whereNotIn('employee_number', function($query){
            $query->select('employee_number')
            ->from(with(new Comparelaboraconcilia)->getTable());
        })
        ->Join('applications', 'applications.id', '=', 'compare_applications_concilia.application_id')
        ->get()->toArray();

        $resultado = array();
        $count = 0;
        foreach($reporte as $correo) {
            foreach($correo as $index => $value){
                switch ($index) {
                    case 'alias':
                        $value = utf8_encode($value);
                        break;
                }
                $resultado[$count][$index] = $value;
            }
            $count = $count+1;
        }
        
        $count = 0;
        
        return $resultado;
    } 
     public function reporteBajasMail() {

        $sql = "
            SELECT 
                compare_applications_active.employee_number as idemp, 
                compare_applications_active.name as nombre, 
                compare_applications_active.lastname1 as apellidos, 
                compare_applications_active.created as fcreado, 
                applications.name as alias,
                compare_labora_remove.motivo_baja
            FROM 
                compare_applications_active
            INNER JOIN 
                compare_labora_remove 
            ON 
                compare_labora_remove.employee_number = compare_applications_active.employee_number
            INNER JOIN 
                applications 
            ON 
                applications.id = compare_applications_active.application_id
            WHERE 
                compare_applications_active.employee_number = compare_labora_remove.employee_number
        ";
        
        $reporte = DB::select(DB::raw($sql));
        
        $resultado = array();
        $count = 0;
        foreach($reporte as $correo) {
            foreach($correo as $index => $value){
                switch ($index) {
                    case 'alias':
                        $value = utf8_encode($value);
                        break;
                }
                $resultado[$count][$index] = $value;
            }
            $count = $count+1;
        }
        
        $count = 0;
        // echo '<pre>';print_r($resultado);echo '</pre>';die();

        return $resultado;
    }  
}
