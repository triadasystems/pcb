<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EncryptController extends Controller
{
    public $ip_address_client;

    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        // $this->middleware('userProfileInactivo');
        // $this->middleware('auth');
    }
    
    public function viewEncrypt() {
        // DB::table('logbook_movements')->insert([
        //     [
        //         'ip_address' => $this->ip_address_client, 
        //         'description' => 'Visualización de la herramienta de encriptación',
        //         'tipo' => 'vista',
        //         'id_user' => Auth::user()->id
        //     ]
        // ]);

        return view('encrypt.generaencrypt');
    }
    
    public function desEncrypt(Request $request) {
        // DB::table('logbook_movements')->insert([
        //     [
        //         'ip_address' => $this->ip_address_client, 
        //         'description' => 'Se ha realizado una encriptación',
        //         'tipo' => 'vista',
        //         'id_user' => Auth::user()->id
        //     ]
        // ]);

        echo Crypt::encryptString($request->input('text'));
        exit();
    }
}
