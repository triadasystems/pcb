<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mail extends Model
{
    protected $fillable=[
        "correo", "automatizacion", "bajas", "tcs_terceros_baja"
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

        if($mail->save()) {
            return true;
        }

        return false;
    }
}
