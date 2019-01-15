<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\setting;
use App\Profileusers;
use App\subfijo;
use App\terceros;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class settingsController extends Controller
{
    public $ip_address_client;
    protected $requestProp;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
    }
    public function index()
    {
        $sub = terceros::recuperar_subfijo();
        $set = setting::settings();
        $subfijo = $sub[0]->id;// subfijo de la tabla
        //$perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();
        //return view("terceros.lista")->with(['perfiles'->$perfiles]);
        $data = array();
        $data['sub'] = $sub;
        $data['sett'] = $set;
        
        return view("settings.index")->with('data',$data);
    }
    public function updatesub(Request $request)
    {
        $datos = $request->post();
        $tipo = $request->post("tipo");
        
        if ($tipo==1) {
            $request->validate([
                "subfijo_nuevo"  => "required|numeric|gte:old_sub"
            ]);

            $old = $request->post("old_sub");
            $new = $request->post("subfijo_nuevo");
            if ($old < $new) {
                try {
                    $sub['subfijo'] = $new;
                    $dato = subfijo::nuevo($sub);
                    return redirect()->route('settings')->with('confirmacion', 'registrado');
                } catch (Exception $e) {
                    report($e);
                }
            }
        } else if ($tipo == 2) {
            // echo '<pre>';print_r($datos);echo '</pre>';
            // die();
            $validations = array();
            
            foreach ($datos["config"] as $index => $value) {

                // foreach ($value as $row) {
                    $validation = '';

                    switch ($value["type_input_html"]) {
                        case 'text':
                        $validation = 'required';
                        break;
                        case 'number':
                        $validation = 'required|numeric';
                        break;
                    }

                    $validations['config.*.'.$value["name"]] = $validation;
                // }
            }
            // echo '<pre>';print_r($request->post());echo '</pre>';
            // echo '<pre>';print_r($validations);echo '</pre>';
            // die();
            $this->validate($request, $validations);
            // $request->validate($validations);
            
            // $id = $request->post('id_dias');
            // $dias_new = $request->post('dias');
            // $set = setting::settings($id);
            // $dias_old = $set[0]->settings;
            // $data["updated_at"] = $fecha= date('Y-m-d');
            // $data["description"]        = (utf8_encode($request->post("descripcion")) !='') ? utf8_encode(strtoupper($request->post("descripcion"))) : NULL;
            // $data["settings"]           = ($request->post("dias") !='') ? strtoupper($request->post("dias")) : NULL;
            
            // if ($dias_new != $dias_old) {
            //     try {
            //         $set=setting::updsettings($data,$id);
            //         return redirect()->route('settings')->with('confirmacion', 'actualizado');
            //     } catch (Exception $e) {
            //         report($e);
            //     }
    
            // } else if ($dias_new == $dias_old) {
            //     return redirect()->route('settings')->with('igual', 'igual');
            // }
        }
    }
}
