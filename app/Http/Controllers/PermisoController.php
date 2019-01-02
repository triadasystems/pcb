<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\permiso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class PermisoController extends Controller {

    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora

        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['create']]);
        $this->middleware('upgrade', ['only' => ['edit']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de permisos',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view('permisos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $usuarios = DB::table('profiles_users AS pu')
        ->select('pu.id', 'pu.profilename')
        ->leftJoin('permits_users', 'permits_users.profiles_users_id', '=', 'pu.id')
        ->whereNull('permits_users.id')->get();

        if(count($usuarios) > 0){
            return view('permisos.create', compact("usuarios"));    
        }
        return redirect('permisos')->with('vacio', 'registrado');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "reading" => "integer",
            "writing" => "integer",
            "upgrade" => "integer",
            "send_email" => "integer",
            "execution" => "integer",
            "profilename" => ["required", "integer"]
        ]);
        //Permiso::create($request->all());
        $aux = false;

        $verificar_existencia = DB::table('permits_users')->where('profiles_users_id', $request->post('profilename'))->get();
        if (!isset($verificar_existencia[0])) {
            $aux = true;
        }
        if ($aux) {
            if($request->post('reading') == null) {$reading = 0;} else {$reading = 1;}
            if($request->post('writing') == null) {$writing = 0;} else {$writing = 1;}
            if($request->post('upgrade') == null) {$upgrade = 0;} else {$upgrade = 1;}
            if($request->post('send_email') == null) {$send_email = 0;} else {$send_email = 1;}
            if($request->post('execution') == null) {$execution = 0;} else {$execution = 1;}
            
            $permisos = permiso::create([
                "profiles_users_id" => $request->post('profilename'),
                "reading" => $reading,
                "writing" => $writing,
                "upgrade" => $upgrade,
                "send_email" => $send_email,
                "execution" => $execution
            ]);
            
            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado el alta de un permisos aun perfil',
                    'tipo' => 'alta',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route("permisos.index")->with(['confirmacion' =>'registrado']);
        }

        return view('permisos.create', compact("usuarios"))->withErrors('El perfil ya tiene privilegios');
    }

    public function edit(permiso $permiso) {
        return view('permisos.edit', compact('permiso'));
    }

    public function update(Request $request, permiso $permiso) {
        DB::table('permits_users')
            ->where('id', $request->post('id'))
            ->update([
                'reading' => $request->post('reading'),
                'writing' => $request->post('writing'),
                'upgrade' => $request->post('upgrade'),
                'send_email' => $request->post('send_email'),
                'execution' => $request->post('execution')
        ]);
        
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de permisos de un perfil',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect()->route("permisos.index")->with(['edito'=> 'editado']);
    }

    public function desactivar(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            if(permiso::where("id", "=", $request->post("id"))->update([$request->post("tipo") => $request->post("act_ina")])) {
                echo "true";
                
                DB::table('logbook_movements')->insert([
                    [
                        'ip_address' => $this->ip_address_client, 
                        'description' => 'Se han realizado cambios de permisos a un perfil',
                        'tipo' => 'modificacion',
                        'id_user' => Auth::user()->id
                    ]
                ]);

                exit();
            }

            echo  "false";
            exit();
        } else {
            echo "middleUpgrade";
            exit();
        }
    }
}
