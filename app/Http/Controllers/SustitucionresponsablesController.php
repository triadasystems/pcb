<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogBookMovements;
use App\terceros;
use App\ReporteResponsable;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class SustitucionresponsablesController extends Controller
{
    public $ip_address_client;

    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
        $this->middleware('upgrade', ['only' => ['update']]);
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

    public function autocomplete(Request $request) {
        $autocompleteAutResp = new terceros;

        $term = $request->get('term', '');

        return $autocompleteAutResp->autocompleteAutResp($term, $request);
    }

    public function update(Request $request) {
        $request->validate([
            "nombre"  => "required|max:255|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "numEmpleado"  => "required|min:1|max:2147483647|digits_between:1,10|numeric",
        ]);
        
        $sustitucion = new terceros;
        
        if($sustitucion->sustitucion($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado sustitución de un Autorizador/Responsable',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        }

        return Response::json(false);
    }

    public function permisosSustitucion() {}        
    public function cambioStatus() {}
}
