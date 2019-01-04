<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\terceros;
use App\Profileusers;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class tercerosController extends Controller
{
    public $ip_address_client;
    public function __construct()
    {
        //$this->ip_address_client=getIpAddress();
        //$this->middleware('auth');
    }
    public function index()
    {
        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();
        //return view("terceros.lista")->with(['perfiles'->$perfiles]);
        return view("terceros.lista");
    }
    public function anyData()
    {
        $querys = terceros::listar_terceros();
        return Datatables::of($querys)->make(true);
    }
    public function create()
    {
        
        $emp    = terceros::empresas();
        $mesa   = terceros::mesa();
        $app    = terceros::aplicacion();
        $data['empresa']    =   $emp;
        $data['mesa']       =   $mesa;
        $data['app']        =   $app;
        //print_r($data);
        //die();
        return view("terceros.create")->with('empresa',$emp);
        //return view("terceros.create");
    }
    public function insertar()
    {

    }
}
