<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivosBajas extends Model
{
    protected $table = 'tcs_type_low';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "code",
        "type"
    ];

    public function motivosbajas() {
        $mBajas = MotivosBajas::get()->toArray();

        return $mBajas;
    }

    public function altaMotivosBajas($data) {
        $motivos = new MotivosBajas;
        $motivos->code = $data["code"];
        $motivos->type = strtoupper($data["type"]);

        if($motivos->save()) {
            return true;
        }

        return false;
    }

    public function editarMotivosBajas($data) {
        $motivos = MotivosBajas::find($data["id"]);
        $motivos->code = $data["code"];
        $motivos->type = strtoupper($data["type"]);
        $motivos->updated_at = strtoupper($data["type"]);

        if($motivos->save()) {
            return true;
        }
        
        return false;
    }
}
