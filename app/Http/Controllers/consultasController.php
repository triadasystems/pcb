<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class consultasController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function consulta_todo_permisos() {
        $permiso = DB::table('permits_users')
                ->join('profiles_users', 'permits_users.profiles_users_id', '=', 'profiles_users.id')
                ->select('permits_users.*', 'profiles_users.profilename')
                ->where("profiles_users.profilename", "!=", "root")
                ->get();

        for ($aux = 0; $aux < count($permiso); $aux++) {
            if ((int) $permiso[$aux]->reading == 0) {
                $permiso[$aux]->reading = "Inactivo";
            } else {
                $permiso[$aux]->reading = "Activo";
            }
            if ((int) $permiso[$aux]->writing == 0) {
                $permiso[$aux]->writing = "Inactivo";
            } else {
                $permiso[$aux]->writing = "Activo";
            }
            if ((int) $permiso[$aux]->upgrade == 0) {
                $permiso[$aux]->upgrade = "Inactivo";
            } else {
                $permiso[$aux]->upgrade = "Activo";
            }
            if ((int) $permiso[$aux]->send_email == 0) {
                $permiso[$aux]->send_email = "Inactivo";
            } else {
                $permiso[$aux]->send_email = "Activo";
            }
            if ((int) $permiso[$aux]->execution == 0) {
                $permiso[$aux]->execution = "Inactivo";
            } else {
                $permiso[$aux]->execution = "Activo";
            }
        }

        return Datatables::of($permiso)->make(true);
    }

    public function consulta_todo_mails() {
        $mail = DB::table('mails')->get();

        for ($aux = 0; $aux < count($mail); $aux++) {
            if ((int) $mail[$aux]->automatizacion == 0) {
                $mail[$aux]->automatizacion = "Inactivo";
            } else {
                $mail[$aux]->automatizacion = "Activo";
            }
            if ((int) $mail[$aux]->bajas == 0) {
                $mail[$aux]->bajas = "Inactivo";
            } else {
                $mail[$aux]->bajas = "Activo";
            }
        }
        return Datatables::of($mail)->make(true);
    }

    public function consulta_todo_relacionpm() {
        $relacionmp = DB::table('rel_profiles_modules')->get();
        $salida = array();
        error_reporting(0);
        foreach ($relacionmp as $arreglo) {
            $modulo = DB::table('modulos')->select('modulename')->where("id", $arreglo->module_users_id)->get()[0]->modulename;
            $nombre = DB::table('profiles_users')->select('profilename')->where("id", $arreglo->profiles_users_id)->get()[0]->profilename;
            if (!isset($salida[$nombre])) {
                $salida[$nombre]->nombre = $nombre;
                $salida[$nombre]->id = $arreglo->profiles_users_id;
                $salida[$nombre]->info = "";
            }
            $salida[$nombre]->info .= " $modulo,";
        }
        return Datatables::of($salida)->make(true);
    }

    public function consulta_todo_modulos() {
        $mail = DB::table('modulos')->get();
        //$mail[0]->id=count($mail)-1;

        return Datatables::of($mail)->make(true);
    }

}
