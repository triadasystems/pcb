<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogBookMovements;
use App\ReporteResponsable;

use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class SustitucionresponsablesController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');

    }

    public function responsables() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla Sustitución de Autorizadores/Responsables',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        
        return view('responsables.lista');
    }

    public function responsablesData() {
        $responsables = new ReporteResponsable;
        
        return Datatables::of($responsables->reporteResponsables())->make(true);
    }

    public function update() {}

    public function permisosSustitucion() {}
        
    public function cambioStatus() {}
}
