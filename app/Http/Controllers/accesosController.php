<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\permiso;
use App\modulo;
use App\Relprofilesmodules;

class accesosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function validatePermits($id) {
        $validacion = User::find($id)->relProfile()->select('id')->first();
        if($permiso = permiso::where('profiles_users_id', '=', $validacion->id)->first()) {
            if(count($permiso->toArray()) > 0) {
                return response()->json([
                    $permiso->toArray()
                ]);
            } else {
                return response()->json([
                    'failed'
                ]);
            }
        } else {
            return response()->json([
                'failed'
            ]);
        }
    }

    public function validateModules($id, $modulo) {
        $validacion = User::find($id)->relProfile()->select('id')->first();
        
        $relProfilesModules = Relprofilesmodules::select('modulename')->Join('modulos', 'modulos.id', '=', 'rel_profiles_modules.module_users_id')->where('profiles_users_id', '=', $validacion->id)->where('modulos.modulename', '=', $modulo)->first();
        
        $response = '';

        if(isset($relProfilesModules->modulename)) {
            if($modulo == $relProfilesModules->modulename) {
                $response = 'success';
            } else {
                $response = 'failed';
            }
        } else {
            $response = 'failed';
        }
        return response()->json([
            $response
        ]);
    }
}
