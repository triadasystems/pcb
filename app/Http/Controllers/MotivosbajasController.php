<?php

namespace App\Http\Controllers;

use App\MotivosBajas;
use App\LogBookMovements;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Response;

class MotivosbajasController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['store']]);
        $this->middleware('upgrade', ['only' => ['update']]);
    }

    public function index() {
        
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de motivos de bajas',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view("motivosbajas.lista");
    }

    public function data()
    {
        $mBajas = new MotivosBajas;

        return Datatables::of($mBajas->motivosbajas())->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            "code" => "required|min:1|max:2147483647|digits_between:1,10|numeric|unique:tcs_type_low",
            "type" => "required|string|min:3|max:150|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new MotivosBajas;

        if($motivos->altaMotivosBajas($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la alta del motivo de baja con código '.$request->post("code"),
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        }

        return Response::json(false);
    }

    public function permisosMotivosBajas() {
        if (session("msjError") === true) {
            return "middleUpgrade";
        }
    }

    public function update(Request $request) {
        $request->validate([
            "code" => "required|min:1|max:2147483647|digits_between:1,10|numeric|unique:tcs_type_low",
            "type" => "required|string|min:3|max:150|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new MotivosBajas;

        if($motivos->editarMotivosBajas($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación del motivo de baja con códgio '.$request->post("code"),
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        }

        return Response::json(false);
    }

    public function cambioStatus(Request $request) {
        $request->validate([
            "id" => "required",
            "status" => "required"
        ]);

        $MotivosBajas = new MotivosBajas;

        if($MotivosBajas->editarStatusMotivoBaja($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el cambio de status del tipo de baja '.$request->post("type"),
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        }

        return Response::json(false);
    }
}
