<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\permiso;

class PermisosSendMails
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
        if(isset(Auth::user()->id)) {
            $validacion = User::find(Auth::user()->id)->relProfile()->select('id')->first();
            if($permiso = permiso::where('profiles_users_id', '=', $validacion->id)->first()) {
                if(count($permiso->toArray()) > 0) {
                    if($permiso->upgrade == 1) {
                        return $next($request);
                    } else {
                        return redirect('/home')->with('msjError', 'No cuenta con permisos para realizar la operación');
                    }
                } else {
                    return redirect('/home')->with('msjError', 'No cuenta con permisos para realizar la operación');
                }
            } else {
                return redirect('/home')->with('msjError', 'Ha surgido un problema, favor reportarlo en caso de que éste persista');
            }
        }
    }
}
