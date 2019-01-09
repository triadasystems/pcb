<?php

namespace App\Http\Controllers;

use App\MotivosBajas;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
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
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de motivos de bajas',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view("motivosbajas.lista");
    }

    public function data()
    {
        $mBajas = new MotivosBajas;

        return Datatables::of($mBajas->motivosbajas())->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            "code" => "required|integer|unique:tcs_type_low",
            "type" => "required|regex:/^[A-Za-z0-9[:space:]]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new MotivosBajas;

        if($motivos->altaMotivosBajas($request->post()) === true) {
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
            "code" => "required|integer|unique:tcs_type_low",
            "type" => "required|regex:/^[A-Za-z0-9[:space:]]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new MotivosBajas;

        if($motivos->editarMotivosBajas($request->post()) === true) {
            return Response::json(true);
        }

        return Response::json(false);
    }
}
