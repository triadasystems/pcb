<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Profileusers;

use App\LogBookMovements;
use App\Applications;

class applicationController extends Controller
{
    public $ip_address_client;
    protected $requestProp;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
        // $this->middleware('writing', ['only' => ['store']]);
        // $this->middleware('upgrade', ['only' => ['update']]);
    }
    public function index()
    {
        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de aplicaciones',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view("aplicacion.listar");
    } 
    public function aplicacionData() {
        $querys = new Applications;
        
        return Datatables::of($querys->recuperarapps())->make(true);
    }
}
