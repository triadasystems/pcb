<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class requestFus extends Model
{
    protected $table="tcs_request_fus";

    protected $fillable = [
        'id',
        'id_generate_fus',
        'description',
        'type',
        'created_at',
        'tcs_external_employees_id',
        'tcs_cat_helpdesk_id',
        'tcs_type_low_id',
        'tcs_number_responsable_authorizer',
        'real_low_date',
        'users_id',
        'fus_physical',
        'initial_date',
        'low_date',
        'status_fus'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function altaFus($tipo, $descripcion, $data) {
        if(isset($data["id"])) {
            $id = $data["id"];
        } elseif(isset($data["idTercero"])) {
            $id = $data["idTercero"];
        }

        $fus = new requestFus;
        $fus->id_generate_fus = strtotime(date("Y-m-d H:i:s"));
        $fus->description = $descripcion;
        $fus->type = $tipo;
        $fus->tcs_external_employees_id = $id;

        if ($fus->save()) {
            return $fus->id;
        }

        return false;
    }
}
