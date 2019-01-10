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
        $proveedor = new Proveedores;
        $proveedor->name = mb_strtoupper($data["name"]);
        $proveedor->alias = mb_strtoupper($data["alias"]);
        $proveedor->description = $data["description"];
        $proveedor->high_date = date("Y-m-d H:m:i");

        if($proveedor->save()) {
            return true;
        }

        return false;
    }

    public function editarProveedores($data) {
        $proveedor = Proveedores::find($data["id"]);
        $proveedor->name = mb_strtoupper($data["name"]);
        $proveedor->alias = mb_strtoupper($data["alias"]);
        $proveedor->description = $data["description"];
        $proveedor->updated_at = date("Y-m-d H:m:i");

        if($proveedor->save()) {
            return true;
        }
        
        return false;
    }

    public function editarStatusProveedores($data) {
        $proveedor = Proveedores::find($data["id"]);
        $proveedor->status = $data["status"];

        if($data["status"] == "Inactivo") {
            $proveedor->low_date = date("Y-m-d H:m:i");
        } else {
            $proveedor->low_date = null;
        }

        if($proveedor->save()) {
            return true;
        }
        
        return false;
    }
}
