<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
                
                if($nuevo->save()) {
                    unset($nuevo);
                    
                    $cambioStatus->status = 2;
                    $cambioStatus->save();
                }
            }

            return true;
        } catch(Exception $e) {
            return false;
        }
        
    }
}
