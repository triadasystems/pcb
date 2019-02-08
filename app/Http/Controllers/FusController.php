<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Profileusers;
use App\LogBookMovements;
use App\AutorizadorResponsable;

use App\terceros;
use App\Comparelaboraconcilia;
use App\tercerosHistorico;
use App\requestFus;

use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FusController extends Controller
{
    public $ip_address_client;
    protected $requestProp;
    
    public function __construct()
    {
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['store']]);
        $this->middleware('upgrade', ['only' => ['update']]);
    }
    public function index($id)
    {
        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de FUS',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        return view("fus.lista_fus")->with('id',$id);
    }
    public function anyData($id)
    {
        $querys = new AutorizadorResponsable;
        
        return Datatables::of($querys->listar($id))->make(true);
    }
    public function agregar($id)
    {
        $app    = terceros::aplicacion();
        $data['app']        =   $app;
        $data['tercero']    =   $id;
        return view("fus.alta_fus")->with('data',$data);
    }
    public function insertar(Request $request)
    {
        ini_set('date.timezone','America/Mexico_City');
        $querys = new terceros;
        $this->requestProp = $request->post();
        $f=$request->post("fecha_fin");
        $request->validate([
            //"fus"       => "numeric|min:1|max:2147483647|digits_between:0,10",
            "fecha_ini" => ["required","date_format:d-m-Y",function($attribute, $fecha_ini, $fail)
            {
                $fecha_actual = date("d-m-Y");
                if ($fecha_ini > $fecha_actual)
                {
                    $fail('La fecha inicial debe ser menor o igual a la fecha de captura');
                }
            }],
            "fecha_fin" => ["required","date_format:d-m-Y","after:fecha_ini",function($attribute, $fecha_fin, $fail)
            {
                $fecha_actual = date("d-m-Y");
                $fecha = $this->requestProp['fecha_ini'];
                $valores = explode('-', $fecha);
                $anio = $valores[2]+1;
                $comp = $valores[0]."-".$valores[1]."-".$anio;
                if (strtotime($fecha_fin) > strtotime($comp)) {
                    $fail('La fecha final es mayor a un año desde la fecha de inicio');
                }
                if (strtotime($fecha_fin) < strtotime($fecha_actual)) {
                    $fail('La fecha final debe ser mayor a la fecha de captura');
                }
            }],
            "nom_auto"  => "required|max:255|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "num_auto"  => ["required", "min:1", "max:2147483647", "digits_between:1,10", "numeric", function($attribute, $value, $fail){
                if($value == $this->requestProp["num_res"]) {
                    $fail("El número de empleado del autorizador no debe coincider con el del responsable");
                }
                $interfaceLabora = new Comparelaboraconcilia;
                $resutl = $interfaceLabora->employeeByNumber($value);
                
                if(count($resutl) == 0) {
                    $fail("El número de empleado no existe");
                }
            }],
            "num_res"   => ["required", "min:1", "max:2147483647", "digits_between:1,10", "numeric", function($attribute, $value, $fail){
                if($value == $this->requestProp["num_auto"]) {
                    $fail("El número de empleado del responsable no debe coincider con el del autorizador");
                }
                $interfaceLabora = new Comparelaboraconcilia;
                $resutl = $interfaceLabora->employeeByNumber($value);
                
                if(count($resutl) == 0) {
                    $fail("El número de empleado no existe");
                }
            }],
            "nom_res"   => "required|max:255|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "destino"   => "required"
        ]);
        $destino = $request->post("destino");
        $id_generate_fus = strtotime(date("Y-m-d H:i:s"));
        $fus = array();
        
        $fus["id_generate_fus"]                     = $id_generate_fus;
        $fus["description"]                         = "Alta de FUS";
        $fus["type"]                                = 1;
        $fus["tcs_cat_helpdesk_id"]                 = 1;
        $fus["tcs_number_responsable_authorizer"]   = ($request->post("num_auto") !='') ? strtoupper($request->post("num_auto")) : NULL;
        $fus["users_id"]                            = Auth::user()->id;
        $fus["fus_physical"]                        = ($request->post("fus") !='') ? strtoupper($request->post("fus")) : NULL;
        $fus["initial_date"]                        = ($request->post("fecha_ini") !='') ? date('Y-m-d',strtotime($request->post("fecha_ini"))) : NULL;
        $fus["low_date"]                            = ($request->post("fecha_fin") !='') ? date('Y-m-d',strtotime($request->post("fecha_fin"))) : NULL;
        $fus["tcs_external_employees_id"]           = ($request->post("tercero") !='') ? strtoupper($request->post("tercero")) : NULL;
        $id_t=$request->post("tercero");
        $b = new terceros;
        $c=$b->fecha_fin($id_t);
        $ff_t= $c[0]['f_fin'];
        $ff_f= date('Y-m-d',strtotime($request->post("fecha_fin")));    
        
        try {
            $a = new requestFus;
            
            $id_fus = $a->create_fus($fus);
            


            foreach ($destino as $value)
            {
                $apps["tcs_external_employees_id"] = ($request->post("tercero") !='') ? strtoupper($request->post("tercero")) : NULL;
                $apps["applications_id"] = $value;
                $apps["tcs_request_fus_id"] = $id_fus;
                terceros::new_row_app($apps);     
            }
            $auto_save = new AutorizadorResponsable;
            $auto_save->name                =   ($request->post("nom_auto") !='') ? strtoupper($request->post("nom_auto")) : NULL;
            $auto_save->number              =   ($request->post("num_auto") !='') ? strtoupper($request->post("num_auto")) : NULL;
            $auto_save->type                =   1;
            $auto_save->tcs_request_fus_id  =   $id_fus;
            $auto_save->save();

            $resp_save = new AutorizadorResponsable;
            $resp_save->name                =   ($request->post("nom_res") !='') ? strtoupper($request->post("nom_res")) : NULL;
            $resp_save->number              =   ($request->post("num_res") !='') ? strtoupper($request->post("num_res")) : NULL;
            $resp_save->type                =   2;
            $resp_save->tcs_request_fus_id  =   $id_fus;
            $resp_save->save();
            if (strtotime($ff_f) > strtotime($ff_t))
            {    
                $val=terceros::find($request->post("tercero"));       
                $val->low_date  =   $ff_f;
                $val->save();
            }

            $listApps = "";
            foreach ($destino as $value) {
                $listApps .= $value.",";
            }
            $listApps = substr($listApps, 0, -1);
            
            $tercero = terceros::find($request->post("tercero"));
            $data = array(
                "id_external" => $tercero->id_external,
                "name" => $tercero->name,
                "lastname1" => $tercero->lastname1,
                "lastname2" => $tercero->lastname2,
                "initial_date" => $tercero->initial_date,
                "low_date" => $tercero->low_date,
                "badge_number" => $tercero->badge_number,
                "email" => $tercero->email,
                "created_at" => $tercero->created_at,
                "status" => $tercero->status,
                "tcs_fus_ext_hist" => $id_fus,
                "tcs_applications_ids" => $listApps,
                "tcs_subfijo_id" => $tercero->tcs_subfijo_id,
                "tcs_externo_proveedor" => $tercero->tcs_externo_proveedor,

                "authorizing_name" => strtoupper($request->post("nom_auto")),
                "authorizing_number" => $request->post("num_auto"),
                "responsible_name" => strtoupper($request->post("nom_res")),
                "responsible_number" => $request->post("num_res")
            );

            $historico = new tercerosHistorico;
            $historico->sustitucionHistorico($data, $id_fus);

            $bit = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la alta de un FUS con ID: '.$id_generate_fus,
                'tipo' => 'alta',
                'id_user' => Auth::user()->id
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($bit);
            return redirect()->route('fuslista',$request->post("tercero"))->with('confirmacion','1');

        } 
        catch (Exception $e) 
        {
            report($e);
            echo "error";
        }
        
    }
}
?>