<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
// use App\Http\Controllers\LDAPController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public $ip_address_client;

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'keyemp'    => 'required|numeric|integer|digits_between:1,10',
            'email'     => 'required|string|email|max:255|unique:users',
            'tipo'      => 'required',
            // 'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el alta de un usuario',
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            ]
        ]);

        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'num_employee' => $data['keyemp'],
            'email' => $data['email'],
            'profiles_users_id' => $data['profile'],
            'tipo' => $data['tipo']
            // 'password' => Hash::make($data['password']),
        ]);
    }
}
