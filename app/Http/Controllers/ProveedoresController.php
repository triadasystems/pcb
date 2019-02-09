<?php

namespace App\Http\Controllers;

use App\Proveedores;
use App\LogBookMovements;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Response;

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
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de motivos de bajas',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view("proveedores.lista");
    }

    public function data()
    {
        $proveedores = new Proveedores;

        return Datatables::of($proveedores->proveedores())->make(true);
    }

    public function store(Request $request) {
        $request->validate([
            //"name" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_cat_suppliers",
            "name" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "alias" => "required|string|min:2|max:45|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_cat_suppliers",
            "description" => "required|string|min:5|max:255|regex:/^[A-Za-z0-9[:space:]\s\S]+$/"
        ]);
        
        $proveedor = new Proveedores;

        if($proveedor->altaProveedores($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la alta del proveedor '.$request->post("name"),
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        }

        return Response::json(false);
    }

    // public function permisosProveedores() {
    //     if (session("msjError") === true) {
    //         return "middleUpgrade";
    //     }
    // }

    public function update(Request $request) {
        $request->validate([
            //"name" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_cat_suppliers",
            "name" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            //"alias" => "required|string|min:2|max:45|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:tcs_cat_suppliers",
            "description" => "required|string|min:5|max:255|regex:/^[A-Za-z0-9[:space:]\s\S]+$/"
        ]);
        
        $proveedor = new Proveedores;

        if($proveedor->editarProveedores($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación del proveedor '.$request->post("name"),
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

        $proveedor = new Proveedores;

        if($proveedor->editarStatusProveedores($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el cambio de status del proveedor '.$request->post("name"),
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
