<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rdbms extends Model
{
    protected $table = 'rdbms';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = ['id', 'rdbms_type', 'name', 'sox', 'hostname', 'id_address', 'port', 'status'];

    public function lista() {
        $conexiones = rdbms::selectRaw('
            id,
            status,
            rdbms_type AS noType,
            (
                CASE
                    WHEN rdbms_type = 1 THEN "ORACLE"
                    WHEN rdbms_type = 2 THEN "SQL Server"
                    WHEN rdbms_type = 3 THEN "ODBC/Paradig"
                    WHEN rdbms_type = 4 THEN "MySQL"
                    WHEN rdbms_type = 5 THEN "ODBC/Pixel"
                END
            ) AS rdbms_type, 
            name, 
            hostname, 
            (
                CASE
                    WHEN sox = 1 THEN "SI"
                    WHEN sox = 0 THEN "NO"
                    WHEN sox = NULL THEN "NO"
                END
            ) AS sox, 
            ip_address, 
            port
        ')
        ->where('rdbms_type', '!=', 0)
        ->get();
        
        $arrayConexiones = array();
        $count = 0;

        foreach($conexiones as $row) {
            $arrayConexiones[$count]["id"] = $row->id;
            $arrayConexiones[$count]["status"] = $row->status;
            $arrayConexiones[$count]["noType"] = $row->noType;
            $arrayConexiones[$count]["rdbms_type"] = $row->rdbms_type;
            $arrayConexiones[$count]["name"] = utf8_encode($row->name);
            $arrayConexiones[$count]["hostname"] = utf8_encode($row->hostname);
            $arrayConexiones[$count]["sox"] = $row->sox;
            $arrayConexiones[$count]["ip_address"] = $row->ip_address;
            $arrayConexiones[$count]["port"] = $row->port;
            $count = $count+1;
        }

        return $arrayConexiones;
    }

    public function listaById($id) {
        return rdbms::selectRaw('
            id,
            status,
            rdbms_type AS noType,
            (
                CASE
                    WHEN rdbms_type = 1 THEN "ORACLE"
                    WHEN rdbms_type = 2 THEN "SQL Server"
                    WHEN rdbms_type = 3 THEN "ODBC/Paradig"
                    WHEN rdbms_type = 4 THEN "MySQL"
                    WHEN rdbms_type = 5 THEN "ODBC/Pixel"
                END
            ) AS rdbms_type, 
            name, 
            hostname, 
            (
                CASE
                    WHEN sox = 1 THEN "SI"
                    WHEN sox = 0 THEN "NO"
                END
            ) AS sox, 
            ip_address, 
            port, 
            CAST(AES_DECRYPT(db_name, "inxdix_2018") AS CHAR(250)) db_name, 
            CAST(AES_DECRYPT(db_user, "inxdix_2018") AS CHAR(250)) db_user, 
            CAST(AES_DECRYPT(db_psw, "inxdix_2018") AS CHAR(250)) db_psw
        ')
        ->where('rdbms_type', '!=', 0)
        ->where('id', '=', $id)
        ->get()->toArray();
    }
}
