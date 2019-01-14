<?php

namespace App\Http\Controllers;

use App\ReportesTerceros;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

class TcsreportesController extends Controller
{
    public function reporteBajasDiarias() {
        $bajasDiarias = new ReportesTerceros;
        
        return view('tcsreportes.bajasdiarias');
    }

    public function reporteBajasDiariasData() {
        $bajasDiarias = new ReportesTerceros;
        
        return Datatables::of($bajasDiarias->bajasDiarias())->make(true);
    }

    public function reporteActivos() {
        $bajasDiarias = new ReportesTerceros;
        
        return view('tcsreportes.usuariosactivos');
    }

    public function reporteActivosData() {
        $activos = new ReportesTerceros;
        
        return Datatables::of($activos->activos())->make(true);
    }

    public function reporteTrazabilidad() {
        $bajasDiarias = new ReportesTerceros;
        
        return view('tcsreportes.usuariosactivos');
    }

    public function reporteTrazabilidadData() {
        $activos = new ReportesTerceros;
        
        return Datatables::of($activos->activos())->make(true);
    }
// Sin terminar aun
    public function reporteResponsables() {
        $bajasDiarias = new ReportesTerceros;
        
        return view('tcsreportes.responsables');
    }

    public function reporteResponsablesData() {
        $activos = new ReportesTerceros;
        
        return Datatables::of($activos->responsables())->make(true);
    }
}
