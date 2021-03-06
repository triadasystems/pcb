<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MotivosBajas extends Model
{
    protected $table = 'tcs_type_low';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "status",
        "code",
        "type"
    ];

    public function motivosbajas() {
        $mBajas = MotivosBajas::select(
            "id",
            DB::raw("UPPER(status) as status"),
            "code",
            "type"
        )
        ->where("code", "<>", 0)->get()->toArray();

        return $mBajas;
    }

    public function altaMotivosBajas($data) {
        $motivos = new MotivosBajas;
        $motivos->code = $data["code"];
        $motivos->type = mb_strtoupper($data["type"]);

        if($motivos->save()) {
            return true;
        }

        return false;
    }

    public function editarMotivosBajas($data) {
        $motivos = MotivosBajas::find($data["id"]);
        // $motivos->code = $data["code"];
        $motivos->type = mb_strtoupper($data["type"]);
        $motivos->updated_at = date("Y-m-d H:m:i");

        if($motivos->save()) {
            return true;
        }
        
        return false;
    }

    public function editarStatusMotivoBaja($data) {
        $motivoBaja = MotivosBajas::find($data["id"]);
        $motivoBaja->status = $data["status"];

        if($motivoBaja->save()) {
            return true;
        }
        
        return false;
    }
}
