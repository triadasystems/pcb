<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class terceros extends Model
{
    protected $table = 'rdbms_qrys';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'application_id'
        ,'rdbms_id'
        ,'qry_read'
        ,'select_in'
    ];
    public static function listar_terceros()
    {
        $sql="SELECT `a`.`id_external` AS id, CONCAT(`a`.`name`,' ', a.`lastname1`,' ', `a`. `lastname2`) AS nombre, 
            `a`.`initial_date` AS f_inicial, `a`.`low_date` AS f_fin, `a`.`badge_number` AS gafete, 
            `a`.`email` AS correo,`a`.`authorizing_name` AS nom_autorizador, `a`.`authorizing_number` AS num_autorizador, 
            `a`.`responsible_name` AS nom_resposable, `a`.`responsible_number` AS num_resposable, `a`.`status` AS estatus,
            `b`.`name` AS empresa, `b`.`description` AS des_empresa
            FROM tcs_external_employees a
            INNER JOIN tcs_cat_suppliers b ON a.tcs_externo_proveedor=b.id
            WHERE `a`.`status`=1";
            $consultas = DB::select(DB::raw($sql));
            return $consultas;
    }
    public static function empresas()
    {
        $sql="SELECT id, `name` FROM tcs_cat_suppliers WHERE `status`='Activo'";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public static function aplicacion()
    {
        $sql="SELECT id, `name` FROM applications WHERE active = 1 ORDER BY `name` ASC";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public static function mesa()
    {
        $sql="SELECT id, `name` FROM tcs_cat_helpdesk WHERE `status`='Activo' ORDER BY `name` ASC";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    


}
