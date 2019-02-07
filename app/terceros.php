<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\requestFus;
use App\ApplicationsEmployee;
use App\tercerosHistorico;
use App\InterfaceLabora;
use App\Comparelaboraconcilia;

class terceros extends Model
{
    public $term;

    protected $table="tcs_external_employees";

    protected $fillable = [
        'id',
        'id_external',
        'name',
        'lastname1',
        'lastname2',
        'initial_date',
        'low_date',
        'badge_number',
        'email',
        'created_at',
        'status',
        'tcs_subfijo_id', 
        'tcs_externo_proveedor',
    ];

    protected $hidden = [];

    public $timestamps = false;
    
    public $datt;

    public function listar_terceros($dat = 0) {
        $this->datt = $dat;
        
        $consultas = terceros::select(
            'tcs_external_employees.id as ident',
            'tcs_external_employees.id_external AS id',
            'tcs_external_employees.name', 
            'tcs_external_employees.lastname1', 
            'tcs_external_employees.lastname2',
            DB::raw('CONCAT(tcs_external_employees.name, " ", tcs_external_employees.lastname1, " ", tcs_external_employees.lastname2, " | ", tcs_external_employees.badge_number) AS datos_tercero'),
            'tcs_external_employees.initial_date AS f_inicial',
            'tcs_external_employees.low_date AS f_fin',
            'tcs_external_employees.badge_number AS gafete',
            'tcs_external_employees.email AS correo',
            'tcs_external_employees.status AS estatus',
            'tcs_cat_suppliers.name AS empresa',
            'tcs_cat_suppliers.description AS des_empresa'
        )
        ->join('tcs_cat_suppliers', 'tcs_external_employees.tcs_externo_proveedor', '=', 'tcs_cat_suppliers.id')
        ->where(function ($query) {
            if ($this->datt != 0) {
                $query->where('tcs_external_employees.id_external', '=', $this->datt);
            }
        })     
        ->get()->toArray();
        return $consultas;
    }

    public static function empresas() {
        $sql="SELECT id, `name` FROM tcs_cat_suppliers WHERE `status`='Activo'";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }

    public static function aplicacion() {
        $sql="SELECT id, `name` FROM applications WHERE active = 1 ORDER BY `name` ASC";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public static function mesa()
    {
        $sql="SELECT id, `name` FROM tcs_cat_helpdesk WHERE `status`='Activo' ORDER BY `name` ASC";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public function recuperar_subfijo()
    {
        $sql="SELECT id, subfijo 
        FROM tcs_subfijo
        WHERE id=(SELECT max(id) FROM tcs_subfijo)";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public function nextval($seq)
    {
        $sql="SELECT nextval('".$seq."') as id";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    } 
    public function actualizar_sequence($subfijo)
    {
        $sql="UPDATE sequence
                SET
                min_value = ".$subfijo."000000,
                cur_value = ".$subfijo."000001
                WHERE name = 'seq_ext_emp'";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public function actualiza_sub($subfijo_new)
    {
        $sql="INSERT INTO `tcs_subfijo`(`subfijo`) VALUES ($subfijo_new)";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public static function new_row($data)
    {
        if ($n= terceros::create($data))
        {
            return $n->id;
        }
        return false;
    }
    public static function new_row_app($data)
    {
        DB::table('tcs_applications_employee')->insert($data);
    }
    
    public function recuperar_idTercero()
    {
        $consultas = terceros::where("id_external", function($subquery){
            $subquery->selectRaw('max(id_external)')->from('tcs_external_employees');
        })
        ->get()
        ->toArray();

        return $consultas;
    }
    
    public function cambioAutoResp($data) {
        $tercero = terceros::find($data["idTercero"]);

        $applicationsEmployee = new ApplicationsEmployee;
        $aplicacionesDelTercero = "";
        
        foreach($applicationsEmployee->applicationEmployeeById($data["idTercero"]) as $row) {
            $aplicacionesDelTercero .= $row["applications_id"].",";
        }

        $dataHistorico = array(
            "id_external" => $tercero->id_external,
            "name" => $tercero->name,
            "lastname1" => $tercero->lastname1,
            "lastname2" => $tercero->lastname2,
            "initial_date" => $tercero->initial_date,
            "low_date" => $tercero->low_date,
            "badge_number" => $tercero->badge_number,
            "email" => $tercero->email,
            "authorizing_name" => $data["nomAuto"],
            "authorizing_number" => $data["numAuto"],
            "responsible_name" => $data["nomResp"],
            "responsible_number" => $data["numResp"],
            "created_at" => $tercero->created_at,
            "status" => $tercero->status,
            "tcs_fus_ext_hist" => $tercero->tcs_fus_ext_hist,
            "tcs_applications_ids" => substr($aplicacionesDelTercero, 0, -1),
            "tcs_subfijo_id" => $tercero->tcs_subfijo_id,
            "tcs_externo_proveedor" => $tercero->tcs_externo_proveedor
        );                    

        $fus = new requestFus;
        $id = $fus->altaFus(2, "Cambio de autorizador y/o responsable", $data);

        if($id !== false) {
            $historicoTercero = new tercerosHistorico;
            $historicoTercero->sustitucionHistorico($dataHistorico, $id);
        }

        $tercero->authorizing_name = $data["nomAuto"];
        $tercero->authorizing_number = $data["numAuto"];
        $tercero->responsible_name = $data["nomResp"];
        $tercero->responsible_number = $data["numResp"];

        if($tercero->save()) {
            return true;
        }

        return false;
    }
    public function sustitucion($dataR) {
        
        switch ($dataR["tipo"]) {
            case "AUTORIZADOR":
                $sustitucion = terceros::where('authorizing_number', '=', $dataR["numEmpleadoActual"]);
                $fields = array(
                    "authorizing_name" => strtoupper($dataR["nombre"]),
                    "authorizing_number" => $dataR["numEmpleado"]
                );
                
                foreach ($sustitucion->get()->toArray() as $key => $value) {
                    $applicationsEmployee = new ApplicationsEmployee;
                    $aplicacionesDelTercero = "";
                    $data = array("id" => $value["id"]);
                    
                    foreach($applicationsEmployee->applicationEmployeeById($value["id"]) as $row) {
                        $aplicacionesDelTercero .= $row["applications_id"].",";
                    }

                    $tcs_fus_ext_hist = null;

                    if(isset($value["tcs_fus_ext_hist"])) {
                        $tcs_fus_ext_hist = $value["tcs_fus_ext_hist"];
                    }

                    $dataHistorico = array(
                        "id_external" => $value["id_external"],
                        "name" => $value["name"],
                        "lastname1" => $value["lastname1"],
                        "lastname2" => $value["lastname2"],
                        "initial_date" => $value["initial_date"],
                        "low_date" => $value["low_date"],
                        "badge_number" => $value["badge_number"],
                        "email" => $value["email"],
                        "authorizing_name" => strtoupper($dataR["nombre"]),
                        "authorizing_number" => $dataR["numEmpleado"],
                        "responsible_name" => $value["responsible_name"],
                        "responsible_number" => $value["responsible_number"],
                        "created_at" => $value["created_at"],
                        "status" => $value["status"],
                        "tcs_fus_ext_hist" => $tcs_fus_ext_hist,
                        "tcs_applications_ids" => substr($aplicacionesDelTercero, 0, -1),
                        "tcs_subfijo_id" => $value["tcs_subfijo_id"],
                        "tcs_externo_proveedor" => $value["tcs_externo_proveedor"]
                    );                    

                    $fus = new requestFus;
                    $id = $fus->altaFus(2, "Cambio de Autorizador", $data);

                    if($id !== false) {
                        $historicoTercero = new tercerosHistorico;
                        $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                    }
                }
                
                if($sustitucion->update($fields)) {
                    return true;
                }
                break;
            case "RESPONSABLE":
                $sustitucion = terceros::where('responsible_number', '=', $dataR["numEmpleadoActual"]);
                $fields = array(
                    "responsible_name" => strtoupper($dataR["nombre"]),
                    "responsible_number" => $dataR["numEmpleado"]
                );

                foreach ($sustitucion->get()->toArray() as $key => $value) {
                    $applicationsEmployee = new ApplicationsEmployee;
                    $aplicacionesDelTercero = "";
                    $data = array("id" => $value["id"]);
                    
                    foreach($applicationsEmployee->applicationEmployeeById($value["id"]) as $row) {
                        $aplicacionesDelTercero .= $row["applications_id"].",";
                    }

                    $tcs_fus_ext_hist = null;

                    if(isset($value["tcs_fus_ext_hist"])) {
                        $tcs_fus_ext_hist = $value["tcs_fus_ext_hist"];
                    }

                    $dataHistorico = array(
                        "id_external" => $value["id_external"],
                        "name" => $value["name"],
                        "lastname1" => $value["lastname1"],
                        "lastname2" => $value["lastname2"],
                        "initial_date" => $value["initial_date"],
                        "low_date" => $value["low_date"],
                        "badge_number" => $value["badge_number"],
                        "email" => $value["email"],
                        "authorizing_name" => $value["authorizing_name"],
                        "authorizing_number" => $value["authorizing_number"],
                        "responsible_name" => strtoupper($dataR["nombre"]),
                        "responsible_number" => $dataR["numEmpleado"],
                        "created_at" => $value["created_at"],
                        "status" => $value["status"],
                        "tcs_fus_ext_hist" => $tcs_fus_ext_hist,
                        "tcs_applications_ids" => substr($aplicacionesDelTercero, 0, -1),
                        "tcs_subfijo_id" => $value["tcs_subfijo_id"],
                        "tcs_externo_proveedor" => $value["tcs_externo_proveedor"]
                    );                    

                    $fus = new requestFus;
                    $id = $fus->altaFus(2, "Cambio de Autorizador", $data);

                    if($id !== false) {
                        $historicoTercero = new tercerosHistorico;
                        $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                    }
                }

                if($sustitucion->update($fields)) {
                    return true;
                }
                break;
            case "AUTORIZADOR/RESPONSABLE":
                $sustitucionA = terceros::where('authorizing_number', '=', $dataR["numEmpleadoActual"]);
                
                foreach ($sustitucionA->get()->toArray() as $key => $value) {
                    $applicationsEmployee = new ApplicationsEmployee;
                    $aplicacionesDelTercero = "";
                    $data = array("id" => $value["id"]);
                    
                    foreach($applicationsEmployee->applicationEmployeeById($value["id"]) as $row) {
                        $aplicacionesDelTercero .= $row["applications_id"].",";
                    }

                    $tcs_fus_ext_hist = null;

                    if(isset($value["tcs_fus_ext_hist"])) {
                        $tcs_fus_ext_hist = $value["tcs_fus_ext_hist"];
                    }

                    $dataHistorico = array(
                        "id_external" => $value["id_external"],
                        "name" => $value["name"],
                        "lastname1" => $value["lastname1"],
                        "lastname2" => $value["lastname2"],
                        "initial_date" => $value["initial_date"],
                        "low_date" => $value["low_date"],
                        "badge_number" => $value["badge_number"],
                        "email" => $value["email"],
                        "authorizing_name" => strtoupper($dataR["nombre"]),
                        "authorizing_number" => $dataR["numEmpleado"],
                        "responsible_name" => $value["responsible_name"],
                        "responsible_number" => $value["responsible_number"],
                        "created_at" => $value["created_at"],
                        "status" => $value["status"],
                        "tcs_fus_ext_hist" => $tcs_fus_ext_hist,
                        "tcs_applications_ids" => substr($aplicacionesDelTercero, 0, -1),
                        "tcs_subfijo_id" => $value["tcs_subfijo_id"],
                        "tcs_externo_proveedor" => $value["tcs_externo_proveedor"]
                    );                    

                    $fus = new requestFus;
                    $id = $fus->altaFus(2, "Cambio de Autorizador", $data);

                    if($id !== false) {
                        $historicoTercero = new tercerosHistorico;
                        $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                    }
                }

                $fieldsA = array(
                    "authorizing_name" => strtoupper($dataR["nombre"]),
                    "authorizing_number" => $dataR["numEmpleado"]
                );

                $sustitucionR = terceros::where('responsible_number', '=', $dataR["numEmpleadoActual"]);

                foreach ($sustitucionR->get()->toArray() as $key => $value) {
                    $applicationsEmployee = new ApplicationsEmployee;
                    $aplicacionesDelTercero = "";
                    $data = array("id" => $value["id"]);
                    
                    foreach($applicationsEmployee->applicationEmployeeById($value["id"]) as $row) {
                        $aplicacionesDelTercero .= $row["applications_id"].",";
                    }

                    $tcs_fus_ext_hist = null;

                    if(isset($value["tcs_fus_ext_hist"])) {
                        $tcs_fus_ext_hist = $value["tcs_fus_ext_hist"];
                    }

                    $dataHistorico = array(
                        "id_external" => $value["id_external"],
                        "name" => $value["name"],
                        "lastname1" => $value["lastname1"],
                        "lastname2" => $value["lastname2"],
                        "initial_date" => $value["initial_date"],
                        "low_date" => $value["low_date"],
                        "badge_number" => $value["badge_number"],
                        "email" => $value["email"],
                        "authorizing_name" => $value["authorizing_name"],
                        "authorizing_number" => $value["authorizing_number"],
                        "responsible_name" => strtoupper($dataR["nombre"]),
                        "responsible_number" => $dataR["numEmpleado"],
                        "created_at" => $value["created_at"],
                        "status" => $value["status"],
                        "tcs_fus_ext_hist" => $tcs_fus_ext_hist,
                        "tcs_applications_ids" => substr($aplicacionesDelTercero, 0, -1),
                        "tcs_subfijo_id" => $value["tcs_subfijo_id"],
                        "tcs_externo_proveedor" => $value["tcs_externo_proveedor"]
                    );                    

                    $fus = new requestFus;
                    $id = $fus->altaFus(2, "Cambio de Autorizador", $data);

                    if($id !== false) {
                        $historicoTercero = new tercerosHistorico;
                        $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                    }
                }

                $fieldsR = array(
                    "responsible_name" => strtoupper($dataR["nombre"]),
                    "responsible_number" => $dataR["numEmpleado"]
                );
                
                if($sustitucionA->update($fieldsA) && $sustitucionR->update($fieldsR)) {
                    return true;
                }
                break;
        }
        return false;
    }

    public function autocompleteAutResp($term, $request) {
        if(empty($term)) {
            return $data[] = array(
                'response' => 'No se encontró el registro'
            );
        }
        $this->term = $term;
        $consultas = Comparelaboraconcilia::where(function ($query) {
            $query->where("employee_number", "LIKE", $this->term."%")
                  ->where("origen_id", "<>", 999)
                  ->orWhere("name", "LIKE", "%".$this->term."%");
        })->limit(5)->get();

        $data = array();
        
        foreach ($consultas as $val) {
            $data[]= array(
                'numero' => $val->employee_number, 
                'nombre' => str_replace("/", " ", $val->name)
            );
        }
        
        if (count($data)) {
            return $data;
        } else if($data == null) {
            return $data[] = array(
                'response' => 'No se encontró el registro'
            );
        }
    }
}
