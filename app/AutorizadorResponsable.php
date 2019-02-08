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
                        ->get()
                        ->toArray();
                        
                        $authorizing_name = $dataR["nombre"];
                        $authorizing_number = $dataR["numEmpleado"];
                        $responsible_name = $contrarioaldelaPosicion[0]["name"];
                        $responsible_number = $contrarioaldelaPosicion[0]["number"];
                        break;
                    
                    case 2:
                        $contrarioaldelaPosicion = AutorizadorResponsable::where("type", "=", 1)
                        ->where("status", "=", 1)
                        ->where("tcs_request_fus_id", "=", $cambioStatus->tcs_request_fus_id)
                        ->get()
                        ->toArray();
                        
                        $authorizing_name = $contrarioaldelaPosicion[0]["name"];
                        $authorizing_number = $contrarioaldelaPosicion[0]["number"];
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

                    $historicoTercero = new tercerosHistorico;
                    $historicoTercero->sustitucionHistorico($dataHistorico, $cambioStatus->tcs_request_fus_id);
                }
            }

            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function listar($id)
    {
        $consultas = AutorizadorResponsable::select(
            'tcs_request_fus.id_generate_fus AS fus',
            'tcs_autorizador_responsable.name AS nombre ',
            'tcs_autorizador_responsable.number as numero',
            DB::raw('CONCAT(tcs_autorizador_responsable.name," | ",tcs_autorizador_responsable.number) AS datos_fus'),
            DB::raw('if(tcs_autorizador_responsable.type=1, "Autorizador", "Responsable") AS tipo'),
            'tcs_request_fus.description AS descripcion')
        ->join('tcs_request_fus','tcs_autorizador_responsable.tcs_request_fus_id','=','tcs_request_fus.id')
        ->where('tcs_autorizador_responsable.status','=','1')
        ->where('tcs_request_fus.tcs_external_employees_id','=',$id)
        ->get()->toArray(); 
        return $consultas; 
    }
}
