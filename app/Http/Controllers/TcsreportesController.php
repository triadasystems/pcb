<?php

namespace App\Http\Controllers;

use App\ReportesTerceros;
use App\ReporteResponsable;
use App\LogBookMovements;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

class TcsreportesController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['store']]);
        $this->middleware('upgrade', ['only' => ['update']]);
    }

    public function reporteBajasDiarias() {      
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización del reporte de bajas diarias',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('tcsreportes.bajasdiarias');
    }

    public function reporteBajasDiariasData() {
        $bajasDiarias = new ReportesTerceros;
        
        return Datatables::of($bajasDiarias->bajasDiarias())->make(true);
    }

    public function reporteActivos() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización del reporte de Usuarios Activos',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('tcsreportes.usuariosactivos');
    }

    public function reporteActivosData() {
        $activos = new ReportesTerceros;
        
        return Datatables::of($activos->activos())->make(true);
    }

    public function reporteTrazabilidad() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización del reporte de trazabilidad',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('tcsreportes.trazabilidad');
    }

    public function reporteTrazabilidadData() {
        $trazabilidad = new ReportesTerceros;
        
        return Datatables::of($trazabilidad->trazabilidad())->make(true);
    }

    public function reporteResponsables() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización del reporte de Autorizador/Responsable',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        
        return view('tcsreportes.responsables');
    }

    public function reporteResponsablesData() {
        $responsables = new ReporteResponsable;
        
        return Datatables::of($responsables->reporteResponsables())->make(true);
    }
}
