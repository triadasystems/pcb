<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Profileusers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LDAPController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $ldap = new LDAPController;
        $response = $ldap->conectionLDAP($request->post("email"), $request->post("password"));
        
        $ip_address_client = getIpAddress();// EVP ip para bitacora session
        
        if($response === true) {
            $userQry = User::where("email", "=", $request->post("email"));

            if($userQry->count() == 1){
                User::where("email", "=", $request->post("email"))->update(['password' => Hash::make($request->post("password"))]);
            }
            
            $restUser = $userQry->first();

            if($restUser != null) {
                $restUser = $restUser->toArray();
            } else {
                return view("auth.login", ["msjError" => "El usuario no existe."]);
            }

            if(is_array($restUser) && $restUser["status"] == 'Activo') {
                $profile = Profileusers::select("status")->find($restUser["profiles_users_id"]);

                if($profile["status"] == 'Activo') {
                    $this->validateLogin($request);
        
                    if ($this->hasTooManyLoginAttempts($request)) {
                        $this->fireLockoutEvent($request);
        
                        return $this->sendLockoutResponse($request);
                    }
        
                    if ($this->attemptLogin($request)) {
                        return $this->sendLoginResponse($request);
                    }
        
                    $this->incrementLoginAttempts($request);
        
                    return $this->sendFailedLoginResponse($request);
                } else {
                    DB::table('sessions')->insert([
                        [
                            'ip_address' => $ip_address_client, 
                            'description' => 'El perfil del '.$request->post("email").' se encuentra inactivo',
                            'tipo' => 'error'
                        ]
                    ]);
                    return view("auth.login", ["msjError" => "El perfil del usuario se encuentra inactivo."]);
                }
            } else {
                DB::table('sessions')->insert([
                    [
                        'ip_address' => $ip_address_client, 
                        'description' => 'El usuario '.$request->post("email").' se encuentra inactivo',
                        'tipo' => 'error'
                    ]
                ]);

                return view("auth.login", ["msjError" => "El usuario se encuentra inactivo."]);    
            }
        } else if($response == 0) {
            DB::table('sessions')->insert([
                [
                    'ip_address' => $ip_address_client, 
                    'description' => 'Error al intentar conectar al usuario '.$request->post("email").' al directorio activo',
                    'tipo' => 'error'
                ]
            ]);

            return view("auth.login", ["msjError" => "Error al intentar conectarse al directorio activo."]);
        } else {
            DB::table('sessions')->insert([
                [
                    'ip_address' => $ip_address_client, 
                    'description' => 'El usuario '.$request->post("email").' ingresado no existe en el directorio activo.',
                    'tipo' => 'error'
                ]
            ]);

            return view("auth.login", ["msjError" => "El usuario ingresado no existe en el directorio activo."]);
        }
    }

    public function listusers() {
        
    }

    /**
     * Envía la respuesta después de que el usuario se autentifique.
     * Elimina el resto de sesiones de este usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;

        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = Session::getId();
        
        if(Auth::user()->save()) {
            $ip_address_client = getIpAddress();
            DB::table('sessions')->insert([
                [
                    'ip_address' => $ip_address_client, 
                    'description' => 'Usuario logueado',
                    'tipo' => 'login',
                    'id_user' => Auth::user()->id
                ]
            ]);
        }
        
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }
}
