<?php

namespace App\Http\Controllers;

use App\Profileusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class ProfilesController extends Controller {

    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
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
                'description' => 'Visualización de la lista de perfiles',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view('profiles.index');
    }

    public function consulta() {
        $profile = Profileusers::where("profilename", "!=", "root")->get();

        return Datatables::of($profile)->make(true);
    }

    public function desactivar(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            switch ($request->post("tipo")) {
                case 'Activo':
                    if (Profileusers::where("id", "=", $request->post("id"))->update(['status' => 'Activo'])) {
                        echo "true";
    
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la activación de un perfil',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
    
                case 'Inactivo':
                    if (Profileusers::where("id", "=", $request->post("id"))->update(['status' => 'Inactivo'])) {
                        echo "true";
    
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la desactivación de un perfil',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
            }
    
            echo "false";
            exit();
        } else {
            echo "middleUpgrade";
            exit();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "profilename" => [
                "required",
                "unique:profiles_users,profilename",
                "regex:/^[A-Za-z0-9]+$/"
            ],
            "description" => ["required", "regex:/^[À-ÿA-Za-z0-9[:space:].,]+$/"]   
        ]);

        $aux=false;
        $verificar_existencia = DB::table('profiles_users')->where('profilename', $request->post('profilename'))->get();

        if (!isset($verificar_existencia[0])) {
            $aux = true;
        }

        if ($aux) {
            DB::table('profiles_users')->insert(
                    [
                        "profilename" => strtolower($request->post('profilename')),
                        "description" => $request->post('description')
            ]);
            
            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado el alta de un perfil',
                    'tipo' => 'alta',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route("index_perfil")->with(['confirmacion'=> 'registrado']);
        }
        
        return view('profiles.create')->withErrors('El perfil ya tiene privilegios');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $data = Profileusers::where("id", "=", $id)->get();

        return view('profiles.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $request->validate([
            "profilename" => [
                "required",
                "unique:profiles_users,profilename,$id",
                "regex:/^[A-Za-z0-9]+$/"
            ],
            "description" => ["required", "regex:/^[À-ÿA-Za-z0-9[:space:].,]+$/"]
        ]);

        DB::table('profiles_users')
            ->where('id', $id)
            ->update([
                'profilename' => strtolower($request->post('profilename')), 
                'description' => $request->post('description')
        ]);

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de un perfil',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            ]
        ]);
        
        return redirect()->route("index_perfil")->with(['edito'=> 'editado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
