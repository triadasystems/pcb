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
        $this->middleware('writing', ['only' => ['store']]);
        $this->middleware('upgrade', ['only' => ['update']]);
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
        $querys = Applications::recuperarapps();
        $resultado = array();
        $count = 0;
        foreach ($querys as $val)
        {
            foreach ($val as $key => $value)
            {
                switch($key)
                {
                    case 'name':
                        $value=utf8_encode($value);
                        break;
                    case 'alias':
                        $value=utf8_encode($value);
                        break;

                }
                $resultado[$count][$key] = $value;
            }
            $count = $count+1;
        }
        $count = 0;
        
        
        return Datatables::of($resultado)->make(true);
    }
    public function desactivar(Request $request)
    {
        if(permisosUpgradeAjax() !== false)
        {
            switch ($request->post("tipo"))
            {
                case '1':
                      if(Applications::where("id","=", $request->post("id"))->update(['active' => '1']))
                    {
                        echo "true";
                        $data = array(
                            'ip_address' => $this->ip_address_client, 
                            'description' => 'Se ha realizado la activación de una aplicación.',
                            'tipo' => 'modificacion',
                            'id_user' => Auth::user()->id
                        );
                        $bitacora = new LogBookMovements;
                        $bitacora->guardarBitacora($data);
                        exit();
                    }
                    break;
                case '2':
                if(Applications::where("id","=", $request->post("id"))->update(['active' => '2']))
                {
                    echo "true";
                    $data = array(
                        'ip_address' => $this->ip_address_client, 
                        'description' => 'Se ha realizado la desactivación de una aplicación.',
                        'tipo' => 'modificacion',
                        'id_user' => Auth::user()->id
                    );
                    $bitacora = new LogBookMovements;
                    $bitacora->guardarBitacora($data);
                    exit();
                }
                break;
            }
            echo  "false";
            exit();
        } 
        else 
        {
            echo "middleUpgrade";
            exit();
        }
    }
    public function alta()
    {
        return view("aplicacion.alta");
    }
    public function create(Request $request)
    {
        $request->validate([
            "nombre"    =>  "required|max:45|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "alias"     =>  "required|max:45|regex:/^[A-Za-z0-9[:space:]\s\S]+$/|unique:applications"
        ]);
        $nom=$request->post("nombre");
        $alias=$request->post("alias");
        try 
        {
            $app= new Applications;
            $app->store($nom,$alias);
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el alta de una aplicación',
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            );
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);
    
            return redirect()->route('laplicacion')->with('confirmacion','data');
        } 
        catch (Exception $e) 
        {
            report($e);
        }   
    }
}
