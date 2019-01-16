<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogBookMovements extends Model
{
    protected $table = 'logbook_movements';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        "id",
        "ip_address",
        "description",
        "tipo",
        "created_at",
        "id_user",
    ];

    public function guardarBitacora($data) {
        $bitacora = new LogBookMovements;
        $bitacora->ip_address = $data["ip_address"];
        $bitacora->description = $data["description"];
        $bitacora->tipo = $data["tipo"];
        $bitacora->id_user = $data["id_user"];

        $bitacora->save();
    }
}
