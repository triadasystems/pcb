<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Profileusers;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\DB;
class usuariosController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
        $this->middleware('upgrade', ['only' => ['update']]);
    }

    public function getIndex() {
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de usuarios',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();

        return view("users.lista")->with('perfiles', $perfiles);
    }

    public function anyData()
    {
        $usuarios = User::leftJoin('profiles_users AS pu', 'pu.id', '=', 'users.profiles_users_id')->select('users.num_employee', 'users.id', 'users.name', 'users.email', 'users.lastname', 'users.status', 'users.created_at', 'pu.id AS idP', 'pu.profilename')->where('pu.profilename', '!=', 'root')->get()->toArray();

        return Datatables::of($usuarios)->make(true);
    }

    public function cambiarrol(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            if(User::where("id", "=", $request->post("id"))->update(['profiles_users_id' => $request->post('rol')])) {
                DB::table('logbook_movements')->insert([
                    [
                        'ip_address' => $this->ip_address_client, 
                        'description' => 'Se ha realizado un cambio de rol/perfil',
                        'tipo' => 'modificacion',
                        'id_user' => Auth::user()->id
                    ]
                ]);

                echo "true";
                
                exit();
            }
            echo  "false";
            exit();
        } else {
            echo "middleUpgrade";
            exit();
        }
    }

    public function desactivar(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            switch ($request->post("tipo")) {
                case 'Activo':
                    if(User::where("id", "=", $request->post("id"))->update(['status' => 'Activo'])) {
                        echo "true";
                        
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la activación de un usuario',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
                
                case 'Inactivo':
                    if(User::where("id", "=", $request->post("id"))->update(['status' => 'Inactivo'])) {
                        echo "true";
                        
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la desactivación de un usuario',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
            }

            echo  "false";
            exit();
        } else {
            echo "middleUpgrade";
            exit();
        }
    }

    public function update(Request $request) {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'apellidos'  => 'required|string|max:255',
            'numEmpleado'    => 'required|numeric|integer|digits_between:1,10',
        ]);

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de un usuario',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        $update = User::find($request->post("id"));
        $update->name = $request->post("nombre");
        $update->lastname = $request->post("apellidos");
        $update->num_employee = $request->post("numEmpleado");

        if($update->save()) {
            return Response::json(true);
        }
        
        return Response::json(false);
    }
}
