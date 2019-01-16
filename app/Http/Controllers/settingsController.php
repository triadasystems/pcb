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
            $validations = array();
            $count = 1;
            foreach ($datos["config"] as $index => $value) {
                $validation = '';

                switch ($value["type_input_html"]) {
                    case 'text':
                        $validation = 'required';
                        break;
                    case 'number':
                        $validation = 'required|numeric';
                        break;
                    default:
                        $validation = '';
                        break;
                }

                // $validations["config"][$count] = array($value["name"] => $validation);
                $validations["config.".$count.".".$value["name"]] = $validation;
                $count = $count+1;
            }
            
            $this->validate($request, $validations);

            foreach ($datos["config"] as $index => $value) {
                $setting = setting::find($value["id"]);
                $setting->settings = $value[$value["name"]];

                $setting->save();
            }

            return redirect()->route('settings')->with('confirmacion', 'actualizado');
        }
    }
}
