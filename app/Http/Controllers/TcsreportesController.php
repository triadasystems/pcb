<?php

namespace App\Http\Controllers;

use App\ReportesTerceros;
use App\ReporteResponsable;
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
    }

    public function reporteBajasDiarias() {      
        return view('tcsreportes.bajasdiarias');
    }

    public function reporteBajasDiariasData() {
        $bajasDiarias = new ReportesTerceros;
        
        return Datatables::of($bajasDiarias->bajasDiarias())->make(true);
    }

    public function reporteActivos() {
        return view('tcsreportes.usuariosactivos');
    }

    public function reporteActivosData() {
        $activos = new ReportesTerceros;
        
        return Datatables::of($activos->activos())->make(true);
    }

    public function reporteTrazabilidad() {
        return view('tcsreportes.trazabilidad');
    }

    public function reporteTrazabilidadData() {
        $trazabilidad = new ReportesTerceros;
        
        return Datatables::of($trazabilidad->trazabilidad())->make(true);
    }
// Sin terminar aun
    public function reporteResponsables() {
        $responsables = new ReporteResponsable;
        
        return view('tcsreportes.responsables');
    }

    public function reporteResponsablesData() {
        $responsables = new ReporteResponsable;
        
        return Datatables::of($responsables->reporteResponsables())->make(true);
    }
}
