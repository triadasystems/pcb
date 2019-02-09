<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ApplicationsEmployee;
use App\requestFus;
use App\terceros;


class AutorizadorResponsable extends Model
{
    protected $table = "tcs_autorizador_responsable";

    protected $fillable = [
        'id',
        'name',
        'number',
        'type',
        'status',
        'tcs_request_fus_id',
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function sustitucion($dataR) {
        $sustitucion = AutorizadorResponsable::where('number', '=', $dataR["numEmpleadoActual"])->get()->toArray();

        try {
            foreach ($sustitucion as $key => $value) {
                $cambioStatus = AutorizadorResponsable::find($value["id"]);
                
                $nuevo = new AutorizadorResponsable;
                $nuevo->name = $dataR["nombre"];
                $nuevo->number = $dataR["numEmpleado"];
                $nuevo->type = $cambioStatus->type;
                $nuevo->tcs_request_fus_id = $cambioStatus->tcs_request_fus_id;
                
                $applicationsEmployee = new ApplicationsEmployee;
                $aplicacionesDelTercero = "";
                
                $fus = requestFus::find($cambioStatus->tcs_request_fus_id);

                $aplicaciones = $applicationsEmployee->applicationEmployeeById($fus->tcs_external_employees_id);
                
                foreach($aplicaciones as $row) {
                    $aplicacionesDelTercero .= $row["applications_id"].",";
                }

                $aplicacionesDelTercero = substr($aplicacionesDelTercero, 0, -1);
               
                switch ($cambioStatus->type) {
                    case 1:
                        $contrarioaldelaPosicion = AutorizadorResponsable::where("type", "=", 2)
                        ->where("status", "=", 1)
                        ->where("tcs_request_fus_id", "=", $cambioStatus->tcs_request_fus_id)
                        ->first()
                        ->toArray();
                        
                        $authorizing_name = $dataR["nombre"];
                        $authorizing_number = $dataR["numEmpleado"];
                        $responsible_name = $contrarioaldelaPosicion["name"];
                        $responsible_number = $contrarioaldelaPosicion["number"];
                        break;
                    
                    case 2:
                        $contrarioaldelaPosicion = AutorizadorResponsable::where("type", "=", 1)
                        ->where("status", "=", 1)
                        ->where("tcs_request_fus_id", "=", $cambioStatus->tcs_request_fus_id)
                        ->first()
                        ->toArray();
                        
                        $authorizing_name = $contrarioaldelaPosicion["name"];
                        $authorizing_number = $contrarioaldelaPosicion["number"];
                        $responsible_name = $dataR["nombre"];
                        $responsible_number = $dataR["numEmpleado"];
                        break;
                }


                $tercero = terceros::find($fus->tcs_external_employees_id);
                
                $dataHistorico = array(
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
                    "tcs_fus_ext_hist" => $cambioStatus->tcs_request_fus_id,
                    "tcs_applications_ids" => $aplicacionesDelTercero,
                    "tcs_subfijo_id" => $tercero->tcs_subfijo_id,
                    "tcs_externo_proveedor" => $tercero->tcs_externo_proveedor,

                    "authorizing_name" => $authorizing_name,
                    "authorizing_number" => $authorizing_number,
                    "responsible_name" => $responsible_name,
                    "responsible_number" => $responsible_number
                );

                if($nuevo->save()) {
                    unset($nuevo);
                    
                    $cambioStatus->status = 2;
                    $cambioStatus->save();

                    $dataR["id"] = $fus->tcs_external_employees_id;

                    $fus = new requestFus;
                    $id = $fus->altaFus(2, "Cambio de autorizador y/o responsable", $dataR);
            
                    if($id !== false) {
                        $historicoTercero = new tercerosHistorico;
                        $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                    }
                }
            }

            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function sustitucionIndividual($dataR) {
        $autoA = explode(" - ", $dataR["autoA"]);
        $respA = explode(" - ", $dataR["respA"]);        
        
        $sustitucion = AutorizadorResponsable::where('number', '=', $autoA[1])->where('tcs_request_fus_id', '=', $dataR["idfus"])->where('status', '=', 1)->where('type', '=', 1)->first();
        $sustitucionR = AutorizadorResponsable::where('number', '=', $respA[1])->where('tcs_request_fus_id', '=', $dataR["idfus"])->where('status', '=', 1)->where('type', '=', 2)->first();

        $fields = array(
            "status" => 2
        );
        
        try {
            // $aCambiar = $sustitucion->first()->toArray();
            $nuevo = new AutorizadorResponsable;
            $nuevo->name = $dataR["nombre"];
            $nuevo->number = $dataR["numEmpleado"];
            $nuevo->type = 1;
            $nuevo->tcs_request_fus_id = $dataR["idfus"];

            $nuevo2 = new AutorizadorResponsable;
            $nuevo2->name = $dataR["nombreR"];
            $nuevo2->number = $dataR["numEmpleadoR"];
            $nuevo2->type = 2;
            $nuevo2->tcs_request_fus_id = $dataR["idfus"];
            
            $applicationsEmployee = new ApplicationsEmployee;
            $aplicacionesDelTercero = "";
            
            $fus = requestFus::find($dataR["idfus"]);

            $aplicaciones = $applicationsEmployee->applicationEmployeeById($fus->tcs_external_employees_id);
            
            foreach($aplicaciones as $row) {
                $aplicacionesDelTercero .= $row["applications_id"].",";
            }

            $aplicacionesDelTercero = substr($aplicacionesDelTercero, 0, -1);
            
            $tercero = terceros::find($fus->tcs_external_employees_id);
                
            $dataHistorico = array(
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
                "tcs_fus_ext_hist" => $dataR["idfus"],
                "tcs_applications_ids" => $aplicacionesDelTercero,
                "tcs_subfijo_id" => $tercero->tcs_subfijo_id,
                "tcs_externo_proveedor" => $tercero->tcs_externo_proveedor,

                "authorizing_name" => $dataR["nombre"],
                "authorizing_number" => $dataR["numEmpleado"],
                "responsible_name" => $dataR["nombreR"],
                "responsible_number" => $dataR["numEmpleadoR"]
            );

            if($nuevo->save() && $nuevo2->save()) {
                unset($nuevo);
                unset($nuevo2);
                
                $sustitucion->update($fields);                
                $sustitucionR->update($fields);

                $dataR["id"] = $fus->tcs_external_employees_id;
                $fus = new requestFus;
                $id = $fus->altaFus(2, "Cambio de autorizador y/o responsable", $dataR);
        
                if($id !== false) {
                    $historicoTercero = new tercerosHistorico;
                    $historicoTercero->sustitucionHistorico($dataHistorico, $id);
                }
            }

            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function listar($id)
    {
        $consultas = requestFus::select(
            'tcs_request_fus.id as idfus',
            'tcs_request_fus.fus_physical as fus_fisico',
            'tcs_request_fus.id_generate_fus as fus',
            'tcs_request_fus.description as descripcion',
            DB::raw('
                (
                    SELECT
                        CONCAT(name, " - ", number)
                    FROM
                        tcs_autorizador_responsable
                    WHERE
                        type = 1
                    AND
                        status = 1
                    AND
                        tcs_request_fus_id = tcs_request_fus.id
                ) as autorizador
            '),
            DB::raw(
                '(
                    SELECT
                        CONCAT(name, " - ", number)
                    FROM
                        tcs_autorizador_responsable
                    WHERE
                        type = 2
                    AND
                        status = 1
                    AND
                        tcs_request_fus_id = tcs_request_fus.id
                ) as responsable'
            )
        )
        ->where('tcs_request_fus.tcs_external_employees_id','=',$id)
        ->where('tcs_request_fus.tcs_external_employees_id','=',$id)
        ->where('tcs_request_fus.type','=',1)
        ->get()
        ->toArray(); 

        // $consultas = AutorizadorResponsable::select(
        //     'tcs_request_fus.id AS idfus',
        //     'tcs_request_fus.fus_physical AS fus_fisico',
        //     'tcs_request_fus.id_generate_fus AS fus',
        //     'tcs_autorizador_responsable.id AS idRespActual',
        //     'tcs_autorizador_responsable.name AS nombre',
        //     'tcs_autorizador_responsable.number AS numero',
        //     'tcs_autorizador_responsable.type AS tipoNum',
        //     DB::raw('CONCAT(tcs_autorizador_responsable.name," | ",tcs_autorizador_responsable.number) AS datos_fus'),
        //     DB::raw('if(tcs_autorizador_responsable.type=1, "Autorizador", "Responsable") AS tipo'),
        //     'tcs_request_fus.description AS descripcion')
        // ->join('tcs_request_fus','tcs_autorizador_responsable.tcs_request_fus_id','=','tcs_request_fus.id')
        // ->where('tcs_autorizador_responsable.status','=','1')
        // ->where('tcs_request_fus.tcs_external_employees_id','=',$id)
        // ->get()
        // ->toArray(); 
        
        return $consultas;
    }
}