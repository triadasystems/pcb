<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\terceros;
use App\Profileusers;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class tercerosController extends Controller
{
    public $ip_address_client;
    protected $requestProp;

    public function __construct()
    {
        //$this->ip_address_client=getIpAddress();
        //$this->middleware('auth');
    }
    public function index()
    {
        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();
        //return view("terceros.lista")->with(['perfiles'->$perfiles]);
        return view("terceros.lista");
    }
    public function anyData()
    {
        $querys = terceros::listar_terceros();
        return Datatables::of($querys)->make(true);
    }
    public function create()
    {
        
        $emp    = terceros::empresas();
        $mesa   = terceros::mesa();
        $app    = terceros::aplicacion();
        $data['empresa']    =   $emp;
        $data['mesa']       =   $mesa;
        $data['app']        =   $app;
        return view("terceros.create")->with('data',$data);
    }
    public function autocomplete(Request $request)
    {
        $term= $request->get('term', '');
        if ($request->type=='num_auto' || $request->type=='num_res' ) {
            $and=" AND `employee_number`  LIKE '".$term."%'";
        }
        if ($request->type=='nom_auto' || $request->type=='nom_res') {
            $and=" AND `name` LIKE '%".$term."%'";
        }
        $sql="SELECT *FROM interface_labora ai
                WHERE consecutive =(SELECT max(consecutive) FROM interface_labora) $and";
        $consultas = DB::select(DB::raw($sql));        
        $data=array();
        foreach ($consultas as $val) {
            $data[]= array('numero'=>$val->employee_number, 'nombre'=>$val->name);
        }
        if (count($data))
        {
            return $data;
        }
        else if($data==null)
        {
            return $data[] = array('response'=>'No se encontro el registro');
        }    
    }
    public function insertar(Request $request)//'email' => 'sometimes|required|'regex:/^.+@.+$/i' a_paterno
    {
        $this->requestProp = $request->post();
        $request->validate([
            "mesa"      => "required",
            "name"      => "required|regex:/^[A-Za-z0-9[:space:]\s\S]+$/", 
            "a_paterno" => "required|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "a_materno" => "sometimes|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "email"     => "required|regex:/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/|unique:tcs_external_employees",
            "fecha_ini" => "required|date",
            "fecha_fin" => ["required","date","after:fecha_ini",function($attribute, $fecha_fin, $fail)
            {
                $fecha=$this->requestProp['fecha_ini'];
                $valores = explode('-', $fecha);
                $anio=$valores[0]+1;
                $comp=$anio."-".$valores[1]."-".$valores[2];
                if ($fecha_fin > $comp) 
                 {
                    $fail('La fecha final es mayor a un año desde la fecha de inicio');
                 }
            }],
            "empresa"   => "required",
            "nom_auto"  => "required|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "num_auto"  => "required|numeric",
            "num_res"   => "required|numeric",
            "nom_res"   => "required|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
            "destino"   => "required"
        ]);
        $consecutivo = $this->generar_consecutivo();
        $sub=terceros::recuperar_subfijo();
        $subfijo=$sub[0]->id;// subfijo de la tabla
        $destino = $request->post("destino");

        $data=array();
        $data["id_external"]            = $consecutivo[0]->id;
        $data["name"]                   = ($request->post("name") != '' ) ? strtoupper($request->post("name")) : NULL;        
        $data["lastname1"]              = ($request->post("a_paterno") !='') ? strtoupper($request->post("a_paterno")) : NULL;
        $data["lastname2"]              = ($request->post("a_materno") !='') ? strtoupper($request->post("a_materno")) : NULL;
        $data["initial_date"]           = ($request->post("fecha_ini") !='') ? strtoupper($request->post("fecha_ini")) : NULL;
        $data["low_date"]               = ($request->post("fecha_fin") !='') ? strtoupper($request->post("fecha_fin")) : NULL;
        $data["badge_number"]           = ($request->post("gafete") !='') ? strtoupper($request->post("gafete")) : NULL;
        $data["email"]                  = ($request->post("email") !='') ? strtoupper($request->post("email")) : NULL;
        $data["authorizing_name"]       = ($request->post("nom_auto") !='') ? strtoupper($request->post("nom_auto")) : NULL;
        $data["authorizing_number"]     = ($request->post("num_auto") !='') ? strtoupper($request->post("num_auto")) : NULL;
        $data["responsible_name"]       = ($request->post("nom_res") !='') ? strtoupper($request->post("nom_res")) : NULL;
        $data["responsible_number"]     = ($request->post("num_res") !='') ? strtoupper($request->post("num_res")) : NULL;
        $data["tcs_subfijo_id"]         = $subfijo;
        $data["tcs_externo_proveedor"]  = ($request->post("empresa") !='') ? strtoupper($request->post("empresa")) : NULL;

        $fus=array();
        $fus["id_generate_fus"]                     = strtotime(date("Y-m-d H:i:s"));
        $fus["description"]                         = "Alta de tercero";
        $fus["type"]                                = 1;
        $fus["tcs_cat_helpdesk_id"]                 = ($request->post("mesa") !='') ? strtoupper($request->post("mesa")) : NULL;
        $fus["tcs_number_responsable_authorizer"]   = ($request->post("num_auto") !='') ? strtoupper($request->post("num_auto")) : NULL;
        $fus["users_id"]                            = Auth::user()->id;
        $fus["fus_physical"]                        = ($request->post("fus") !='') ? strtoupper($request->post("fus")) : NULL;
        try
        {
            terceros::new_row($data);
            $dat=$consecutivo[0]->id;
            $querys = terceros::listar_terceros($dat);
            $id=$querys[0]->ident;
            foreach ($destino as $value) 
            {
                $apps["tcs_external_employees_id"] = $id;
                $apps["applications_id"] = $value;
                terceros::new_row_app($apps);     
            }
            $fus["tcs_external_employees_id"]           = $id;
            terceros::new_row_fus($fus);
            
        } 
        catch (Exception $e) 
        {
            report($e);
        }
        echo "por fin   quedo";
    }
    public function generar_consecutivo()
    {
        $sub=terceros::recuperar_subfijo();
        $subfijo=$sub[0]->subfijo;// subfijo de la tabla
        $id_external_max=$subfijo."999999";//tope maximo del subfijo de la tabla
        $seq="seq_ext_emp";
        $id_external=terceros::nextval($seq);
        $id_externo_cons=$id_external[0]->id;//consecutivo del id del externo
        $sub_conse=substr($id_externo_cons,0,2);
        if ($subfijo == $sub_conse && $id_externo_cons < $id_external_max)//cuando el numero de la secuencia es consecutivo
        {
            return $id_external;
        }
        elseif($id_externo_cons < $id_external_max && $sub_conse < $subfijo)// cuando actualizaron el catalogo de subfijos
        {
            $act_seq=terceros::actualizar_sequence($subfijo);
            $id_external=terceros::nextval($seq);
            $id_externo_cons_n=$id_external[0]->id;//consecutivo del id del externo
            $id_external= $id_externo_cons_n;
        }
        elseif($id_externo_cons==$id_external_max)//cuando llega al numero maximo del consecutivo
        {
            $subfijo_new=$subfijo+1;
            $act_seq=terceros::actualizar_sequence($subfijo_new);
            $act_seq=terceros::actualiza_sub($subfijo_new);     
            return $id_external;
        }
    }
}
