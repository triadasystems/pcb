<?php

namespace App\Http\Controllers;

use App\MesaControl;
use App\LogBookMovements;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Response;

class MesacontrolController extends Controller
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
            'description' => 'Visualización de la lista de mesas de control',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        
        return view("mesacontrol.lista");
    }
    
    public function data() {
        $proveedores = new MesaControl;
        return Datatables::of($proveedores->proveedores())->make(true);
    }


}
