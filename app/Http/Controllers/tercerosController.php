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
        return view("terceros.create")->with('data',$data);
    }
    public function autocomplete(Request $request)
    {
        $term= $request->get('term', '');
        //print_r($term);
        //die();
        if ($request->type=='num_auto' || $request->type=='num_res' ) {
            $and=" AND `employee_number`  LIKE '%".$term."%'";
        }
        if ($request->type=='nom_auto' || $request->type=='nom_res') {
            $and=" AND `name` LIKE '%".$term."%'";
        }
        $sql="SELECT *FROM interface_labora ai
                WHERE consecutive =(SELECT max(consecutive) FROM interface_labora) $and";
        $consultas = DB::select(DB::raw($sql));        
        $data=array();
        foreach ($consultas as $val) {
            $data[]= array('numero'=>$val->employee_number, 'nombre'=>$val->name);
        }
        if (count($data))
        {
            return $data;
        }
        else
        {
            return ['numero'=>'','nombre' => ''];
        }
        
    }
    public function insertar(Request $request)
    {
        print_r($request->post());
    }
}
