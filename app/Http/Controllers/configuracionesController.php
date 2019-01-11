<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\terceros;
use App\Profileusers;
use App\subfijo;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class configuracionesController extends Controller
{
    public $ip_address_client;
    protected $requestProp;

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
}
