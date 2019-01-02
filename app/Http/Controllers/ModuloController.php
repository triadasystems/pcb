<?php

namespace App\Http\Controllers;

use App\modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ModuloController extends Controller {
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
                'description' => 'Visualización de la lista de módulos',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view("modulos.index", compact('modulo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('modulos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            "modulename" => ["required", "regex:/^[A-Za-z0-9]+$/"],
            "description" => ["required", "regex:/^[À-ÿA-Za-z0-9[:space:].,]+$/"]   
        ]);
        Modulo::create($request->all());

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado el alta de un módulo',
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect()->route('modulos.index')->with('confirmacion', 'registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(modulo $modulo) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(modulo $modulo) {
        return view('modulos.edit', compact('modulo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, modulo $modulo) {
        $request->validate([
            "modulename" => ["required", "regex:/^[A-Za-z0-9]+$/"],
            "description" => ["required", "regex:/^[À-ÿA-Za-z0-9[:space:].,]+$/"]   
        ]);
        $modulo->update($request->all());

        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la modificación de un módulo',
                'tipo' => 'modificacion',
                'id_user' => Auth::user()->id
            ]
        ]);

        return redirect()->route("modulos.index")->with(['edito'=> 'editado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(modulo $modulo) {
        //
    }

}
