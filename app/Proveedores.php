<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $table = 'tcs_cat_suppliers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "name",
        "alias",
        "description",
        "status",
        "high_date",
        "low_date",
        "created_at",
    ];

    public function proveedores() {
        $proveedores = Proveedores::get()->toArray();

        return $proveedores;
    }

    public function altaProveedores($data) {
        $motivos = new Proveedores;
        $motivos->code = $data["code"];
        $motivos->type = strtoupper($data["type"]);

        if($motivos->save()) {
            return true;
        }

        return false;
    }

    public function editarProveedores($data) {
        $motivos = Proveedores::find($data["id"]);
        $motivos->code = $data["code"];
        $motivos->type = strtoupper($data["type"]);
        $motivos->updated_at = strtoupper($data["type"]);

        if($motivos->save()) {
            return true;
        }
        
        return false;
    }
}
