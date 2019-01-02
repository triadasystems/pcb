<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Relprofilesmodules;

class RelacionpmController extends Controller {
    public $ip_address_client;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index() {
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de módulos/perfil',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view('relacionpm.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $perfiles = DB::table('profiles_users AS pu')->select('id', 'profilename')
        ->whereNotIn('pu.id', function($query){
            $query->select('profiles_users_id')
            ->from('rel_profiles_modules');
        })
        ->get();        

        $modulos = DB::table('modulos')->select('modulename', 'id')->where('status', "Activo")->get();
        
        if(count($perfiles) > 0) {
            return view('relacionpm.create', compact('perfiles'), compact('modulos'));
        } else {
            return redirect('relacionpm')->with('vacio', 'ok');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validate = array();
        $valores = array();

        $count = 0;

        foreach($request->post() as $key => $value) {
            if($key == "profiles_users_id") {
                $validate[$key] = ["required","integer"];
            } else if($key != "_token") {
                $validate[$key] = ["integer"];
            }
            
            if($key != "_token" && $key != "profiles_users_id") {
                $valores[$count]["profiles_users_id"] = $request->post("profiles_users_id");
                $valores[$count]["module_users_id"] = $request->post($key);
            }
            $count = $count+1;
        }
        
        $request->validate($validate);

        foreach($valores as $row) {
            Relprofilesmodules::create([
                'profiles_users_id' => $row['profiles_users_id'],
                'module_users_id' => $row['module_users_id']
            ]);
        }

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el alta de una relación entre módulos y perfil',
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect('relacionpm')->with('confirmacion', 'ok');
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
        $modulos = DB::table('modulos')->select('modulename', 'id')->where('status', "Activo")->get();
        $relPM = Relprofilesmodules::select('module_users_id')->where('profiles_users_id', '=', $id)->get()->toArray();
        
        $rPM = array();
    
        foreach($relPM as $key => $value) {
            foreach($value as $k => $v) {
                $rPM[$v] = $v;
            }
        }

        return view('relacionpm.edit')->with(['id' => $id, 'dataUpdate' => $rPM, 'modulos' => $modulos]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validate = array();
        $valores = array();

        $count = 0;

        foreach($request->post() as $key => $value) {
            if($key != "_token" && $key != "_method") {
                $validate[$key] = ["integer"];
            }
            
            if($key != "_token" && $key != "_method") {
                $valores[$count]["module_users_id"] = $request->post($key);
            }
            $count = $count+1;
        }
        
        $request->validate($validate);

        $Relprofilesmodules = Relprofilesmodules::where('profiles_users_id', '=', $id);
        $Relprofilesmodules->delete();

        foreach($valores as $row) {
            Relprofilesmodules::create([
                'profiles_users_id' => $id,
                'module_users_id' => $row['module_users_id']
            ]);
        }
        
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de una relación entre módulos y perfil',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect('relacionpm')->with('actualizo', 'ok');
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
