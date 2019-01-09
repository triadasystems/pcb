<?php

namespace App\Http\Controllers;

use App\Proveedores;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class ProveedoresController extends Controller
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

        return view("proveedores.lista");
    }

    public function data()
    {
        $proveedores = new Proveedores;

        return Datatables::of($proveedores->proveedores())->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            "code" => "required|integer|unique:tcs_type_low",
            "type" => "required|regex:/^[A-Za-z0-9[:space:]]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new Proveedores;

        if($motivos->altaProveedores($request->post()) === true) {
            return Response::json(true);
        }

        return Response::json(false);
    }

    public function permisosProveedores() {
        if (session("msjError") === true) {
            return "middleUpgrade";
        }
    }

    public function update(Request $request) {
        $request->validate([
            "code" => "required|integer|unique:tcs_type_low",
            "type" => "required|regex:/^[A-Za-z0-9[:space:]]+$/|unique:tcs_type_low"
        ]);
        
        $motivos = new Proveedores;

        if($motivos->editarProveedores($request->post()) === true) {
            return Response::json(true);
        }

        return Response::json(false);
    }
}
