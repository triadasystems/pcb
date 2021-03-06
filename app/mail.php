<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mail extends Model
{
    protected $fillable = [
        "correo", 
        "automatizacion", 
        "bajas", 
        "tcs_terceros_baja", 
        "tcs_terceros_baja_auth_resp"
    ];

    public function updateMail($data) {
        $mail = mail::find($data["id"]);
        $mail->correo = $data["correo"];

        if(isset($data["automatizacion"])) {
            $mail->automatizacion = $data["automatizacion"];
        } else {
            $mail->automatizacion = 0;
        }

        if(isset($data["bajas"])) {
            $mail->bajas = $data["bajas"];
        } else {
            $mail->bajas = 0;
        }

        if(isset($data["tcs_terceros_baja"])) {
            $mail->tcs_terceros_baja = $data["tcs_terceros_baja"];
        } else {
            $mail->tcs_terceros_baja = 0;
        }

        if(isset($data["tcs_terceros_baja_auth_resp"])) {
            $mail->tcs_terceros_baja_auth_resp = $data["tcs_terceros_baja_auth_resp"];
        } else {
            $mail->tcs_terceros_baja_auth_resp = 0;
        }

        if($mail->save()) {
            return true;
        }

        return false;
    }

    public function listaMailsPermisos() {
        return mail::select(
            'id',
            'correo',
            DB::raw('CASE WHEN automatizacion = 1 THEN "Activo" WHEN automatizacion = 0 THEN "Inactivo" WHEN automatizacion = "" THEN "Inactivo" END AS automatizacion'),
            DB::raw('CASE WHEN bajas = 1 THEN "Activo" WHEN bajas = 0 THEN "Inactivo" WHEN automatizacion = "" THEN "Inactivo" END AS bajas'),
            DB::raw('CASE WHEN tcs_terceros_baja = 1 THEN "Activo" WHEN tcs_terceros_baja = 0 THEN "Inactivo" WHEN automatizacion = "" THEN "Inactivo" END AS tcs_terceros_baja'),
            DB::raw('CASE WHEN tcs_terceros_baja_auth_resp = 1 THEN "Activo" WHEN tcs_terceros_baja_auth_resp = 0 THEN "Inactivo" WHEN automatizacion = "" THEN "Inactivo" END AS tcs_terceros_baja_auth_resp')
        )
        ->get()
        ->toArray();
    }
}
