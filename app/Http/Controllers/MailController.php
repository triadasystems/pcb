<?php

namespace App\Http\Controllers;

use App\mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;

class MailController extends Controller {
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['create']]);
        $this->middleware('upgrade', ['only' => ['edit']]);
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
                'description' => 'Visualización de la lista de e-mails',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view('mails.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('mails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "correo" => ["required","email"],
                //"automatizacion"=>"required",
                //"bajas"=>"required"            
        ]);
        Mail::create($request->all());
        
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el alta de un e-mail',
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect()->route("mails.index")->with(['confirmacion'=> 'registrado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(mail $mail) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function edit(mail $mail) {
        return view('mails.edit', compact('mail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mail $mail) {
        $request->validate([
            "correo" => ["required","email"]           
        ]);
        $mail->update($request->all());
        
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de un e-mail',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect()->route("mails.index")->with('edito', 'editado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(mail $mail) {
        //
    }

}
