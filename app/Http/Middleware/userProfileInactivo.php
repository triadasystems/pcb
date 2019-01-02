<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profileusers;

class userProfileInactivo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip_address_client = getIpAddress();// EVP ip para bitacora session

        if(isset(Auth::user()->id)) {
            $validacion = User::select("status", "profiles_users_id")->where("id", "=", Auth::user()->id)->first();
            if($validacion->status == "Activo") {
                $profile = Profileusers::select("status")->where("id", "=", $validacion->profiles_users_id)->first();
                if($profile->status == 'Activo') {
                    return $next($request);
                } else {
                    DB::table('sessions')->insert([
                        [
                            'ip_address' => $ip_address_client, 
                            'description' => 'Logout de usuario por desactivación de  perfil',
                            'tipo' => 'logout',
                            'id_user' => Auth::user()->id
                        ]
                    ]);

                    Auth::logout();
                    return redirect()->route('login')->with('msjWarning', 'El perfil de tu usuario a sido desactivado.');
                }
            } else {
                DB::table('sessions')->insert([
                    [
                        'ip_address' => $ip_address_client, 
                        'description' => 'Logout de usuario por desactivación',
                        'tipo' => 'logout',
                        'id_user' => Auth::user()->id
                    ]
                ]);

                Auth::logout();
                return redirect()->route('login')->with('msjWarning', 'Tú usuario a sido desactivado.');
            }
        } else {
            DB::table('sessions')->insert([
                [
                    'ip_address' => $ip_address_client, 
                    'description' => 'Logout de usuario, al parecer se ha usado la misma cuenta en otro equipo',
                    'tipo' => 'logout'
                ]
            ]);
            Auth::logout();
            return redirect()->route('login')->with('msjWarning', 'Al parecer alguien más se ha logueado a su cuenta.');
        }
    }
}
