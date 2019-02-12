<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Rdbmsqrys extends Model
{
    protected $table = 'rdbms_qrys';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'application_id'
        ,'rdbms_id'
        ,'qry_read'
        ,'select_in'
    ];
    public static function recupera_query($dato, $cant, $tipo = 1)
    {
        switch($tipo)
        {
            case 1:
                $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.select_in in (1,4,".$dato.") AND rq.status='Activo' AND r.status='Activo' ORDER BY rq.id ASC limit ".$cant.", 1";
                break;
            case 2:
                $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.select_in in (1,4,".$dato.") AND rq.status='Activo' AND r.status='Activo' ORDER BY rq.id ASC";
                break;
        }

        $consultas = DB::select(DB::raw($sql));

        if($tipo == 2) {
            $consultas = count($consultas);
        }
        return $consultas;
    }
    public static function ejecuta_consulta($con2, $sql, $typeDB, $conexionName) {

        switch($typeDB) {
            case 1:
                $stid = oci_parse($con2,$sql);
                if (!$stid) {
                    $err = oci_error($con2);
                    if(isset($err["code"]) && !empty($err["code"])) {
                        
                        $responseError = array();
                        
                        $responseError["error"][$conexionName]["number"] = $err["code"];
                        $responseError["error"][$conexionName]["msj"] = $err["message"];
                        
                        return $responseError;
                    }
                }
                //Realizar la lógica de la consulta
                if ($conexionName == 'SET ODBC') {
                    oci_set_prefetch($stid, 10000);
                }

                if (!$r = @oci_execute($stid)) {
                    $err = oci_error($con2);
                    if(isset($err["code"]) && !empty($err["code"])) {

                        $responseError = array();
                        
                        $responseError["error"][$conexionName]["number"] = $err["code"];
                        $responseError["error"][$conexionName]["msj"] = $err["message"];
                        
                        return $responseError;
                    }
                }
                $data = array();
                while (($row = @oci_fetch_assoc($stid)) != false) {
                    $data[] = $row;
                }

                oci_free_statement($stid);
                oci_close($con2);
                
                break;
            case 2:
                $stmt = @sqlsrv_query($con2, $sql);
                
                if ($stmt === false) {
                    $errors = sqlsrv_errors();
                    if($errors != null) {
                        $responseError = array();
                        foreach($errors as $error) {
                            $responseError["error"][$conexionName]["number"] = $error['code'];
                            $responseError["error"][$conexionName]["msj"] = $error['message'];
                        }
                        
                        return $responseError;
                    }
                }
                $data = array();

                while ($obj = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $data[]=$obj;
                }
                sqlsrv_close($con2);
                break;
            case 3:
                if(!$resultado = @odbc_exec($con2, $sql)) {
                    $responseError = array();
                    $responseError["error"][$conexionName]["number"] = odbc_error();
                    $responseError["error"][$conexionName]["msj"] = odbc_errormsg();
                    
                    return $responseError;
                }

                $data = array();

                while ($obj = odbc_fetch_array($resultado)) {
                    $data[]=$obj; 
                }
                break;
            case 4:
                if(!$result = @mysqli_query($con2,$sql)) {
                    $responseError = array();
                    // $responseError["error"][$conexionName]["number"] = odbc_error();
                    $responseError["error"][$conexionName]["msj"] = mysqli_error($con2);
                    
                    return $responseError;
                }

                $data = array();

                while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    $data[]=$row;
                }
                break;
            case 5:
                if(!$resultado = @odbc_exec($con2, $sql)) {
                    $responseError = array();
                    $responseError["error"][$conexionName]["number"] = odbc_error();
                    $responseError["error"][$conexionName]["msj"] = odbc_errormsg();
                    
                    return $responseError;
                }

                $data = array();

                while ($obj = @odbc_fetch_array($resultado)) {
                    $data[]=$obj; 
                }
                break;
        }
        
        if(!isset($data)) {
            $responseError = array();
            $responseError["error"][$conexionName]["number"] = 300686;
            $responseError["error"][$conexionName]["msj"] = "Data se encuentra vacío";
            
            return $responseError;            
        }

        return $data;
    }
    public static function inserta_application($select_in, $datos, $typeDB) {
        /*switch($typeDB) {
            case 1:*/
        if($select_in == 1 || $select_in == 2 || $select_in == 3) {
            $datos['number_employe'] = intval(preg_replace('/[^0-9]+/', '', $datos['number_employe']), 10);
            $fullname = explode("|", $datos['fullname']);
            
            if(count($fullname) == 2) {
                $datos['fullname'] = $fullname[1];
            } else {
                $datos['fullname'] = $fullname[0];
            }           
        }
        
        if ($select_in == 1) {
            $sql = "
            INSERT INTO interface_application(
                employee_number, 
                name, 
                lastname1,
                lastname2, 
                application_id, 
                consecutive,
                operation
            ) VALUES (
                ".$datos['number_employe'].",
                '".$datos['username']."',
                '".$datos['fullname']."',
                SUBSTRING('".$datos['descripcion']."',1,100),
                '".$datos['aplicacion']."',
                '".$datos['consecutive']."',
                '".$datos['operation']."'
            );";
        } else if ($select_in == 2) {
            $sql = "
                INSERT INTO interface_labora(
                    employee_number, 
                    name, 
                    lastname1, 
                    origen_id ,
                    consecutive, 
                    operation
                ) VALUES (
                    ".$datos['number_employe'] . ",
                    '" . $datos['fullname'] . "',
                    '" . $datos['fullname'] . "',
                    '" . $datos['aplicacion'] . "',
                    '" . $datos['consecutive'] . "',
                    '" . $datos['operation'] . "'
                )";
        } else if ($select_in == 3) {
            $sql = "
                INSERT INTO interface_labora(
                    employee_number, 
                    name, 
                    lastname1, 
                    origen_id ,
                    consecutive, 
                    operation,
                    fecha_baja,
                    motivo_baja
                )  VALUES (
                    ".$datos['number_employe'] . ",
                    '" . $datos['fullname'] . "',
                    '" . $datos['fullname'] . "',
                    '" . $datos['aplicacion'] . "',
                    '" . $datos['consecutive'] . "',
                    '" . $datos['operation'] . "',
                    '" . $datos['fecha_baja'] . "',
                    '" . $datos['tipo_baja'] . "'
                )
            ";
        } else if ($select_in == 4) {
            $sql = "
                INSERT INTO activedirectory_users(
                    username, 
                    aplication_id, 
                    consecutive,
                    operation
                ) VALUES (
                    '" . $datos['username']. "',
                    '" . $datos['aplicacion']."',
                    '".$datos['consecutive']."',
                    '" .$datos['operation']."'
                )";             
        }

        $resultado = DB::select(DB::raw($sql));
    }

    public static function zero_app($consecutive)
    {
        $sql = "INSERT INTO 
                compare_applications_error(
                    employee_number, 
                    `name`, 
                    lastname1, 
                    lastname2,
                    created,
                    application_id,
                    consecutive, 
                    operation
                )
            SELECT 
                employee_number,
                `name`,
                lastname1,
                lastname2,
                created,
                application_id, 
                consecutive, 
                operation  
            FROM 
                interface_application
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number = 0";
            
        $consultas = DB::select(DB::raw($sql));
    }
    public static function bueno_app($consecutive)
    {
        $sql = "INSERT INTO 
                compare_applications(
                    employee_number, 
                    name, 
                    lastname1, 
                    lastname2,
                    created,
                    application_id,
                    consecutive, 
                    operation
                )
            SELECT 
                employee_number,
                name,
                lastname1,
                lastname2,
                created,
                application_id,
                consecutive, 
                operation  
            FROM 
                interface_application
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number > 0
            AND lastname2 NOT LIKE '%999OP'
        ";
        $consultas = DB::select(DB::raw($sql));
    }
    public static function app_activo($consecutive)
    {
        $sql = "
            INSERT INTO 
                compare_applications_active (
                    `employee_number`, 
                    `name`, 
                    `lastname1`, 
                    `lastname2`,
                    created,
                    `application_id`,
                    consecutive, 
                    operation
                )
            SELECT 
                employee_number,
                name,
                lastname1,
                lastname2,
                created,
                application_id, 
                consecutive, 
                operation  
            FROM 
                interface_application
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number > 0
        ";
        
        $consultas = DB::select(DB::raw($sql));
    }

    public static function app_concilia($consecutive)
    {
        $sql = "
            INSERT INTO 
                compare_applications_concilia(
                    employee_number, 
                    name, 
                    lastname1, 
                    lastname2,
                    created,
                    application_id,
                    consecutive, 
                    operation
                )
            SELECT 
                employee_number,
                name,
                lastname1,
                lastname2,
                created, 
                application_id, 
                consecutive, 
                operation 
            FROM 
                compare_applications
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number > 0
        ";
        $consultas = DB::select(DB::raw($sql));
    }

    public static function zero_labora($consecutive)
    {
        $sql = "
            INSERT INTO 
                compare_labora_error(
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created,
                    origen_id,
                    consecutive, 
                    operation,
                    fecha_baja,
                    motivo_baja
                ) 
            SELECT 
                employee_number,
                name,
                lastname1,
                lastname2,
                created, 
                origen_id, 
                consecutive, 
                operation,
                fecha_baja,
                motivo_baja 
            FROM 
                interface_labora
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number  = 0;
        ";
        $consultas = DB::select(DB::raw($sql));
    }

    public static function bueno_labora($consecutive)
    {
        $sql = "
            INSERT INTO 
                compare_labora(
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created,
                    origen_id,
                    consecutive, 
                    operation,
                    fecha_baja,
                    motivo_baja
                ) 
                SELECT 
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created,
                    origen_id,
                    consecutive,
                    operation,
                    fecha_baja,
                    motivo_baja 
                FROM 
                    interface_labora
                WHERE 
                    date(created) = date(sysdate()) 
                AND 
                    consecutive = $consecutive
                AND
                    employee_number > 0
        ";
        $consultas = DB::select(DB::raw($sql));
    }

    public static function libera_remove()
    {
        $limpia = "DELETE FROM compare_labora_remove";
        $borro = DB::select(DB::raw($limpia));
        if ($borro) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function labora_remove($consecutive)
    {

        $sql = "
            INSERT INTO 
                compare_labora_remove(
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created,
                    origen_id,
                    consecutive, 
                    operation,
                    fecha_baja,
                    motivo_baja
                ) 
                SELECT 
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created, 
                    origen_id, 
                    consecutive, 
                    operation,
                    fecha_baja,
                    motivo_baja 
                FROM 
                    interface_labora
                WHERE 
                    date(created) = date(sysdate()) 
                AND 
                    consecutive = $consecutive
                AND
                    employee_number  > 0
        ";
        $consultas = DB::select(DB::raw($sql));
    }

    public static function libera_labora()
    {
        $limpia = "DELETE FROM compare_labora_concilia";
        $borro = DB::select(DB::raw($limpia));
        if ($borro) 
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public static function libera_application()
    {
        $limpia = "DELETE FROM compare_applications_concilia";
        $borro = DB::select(DB::raw($limpia));
        if ($borro)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public static function libera_app_active()
    {
        $limpia = "DELETE FROM compare_applications_active";
        $borro = DB::select(DB::raw($limpia));
        if($borro)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function labora_concilia($consecutive)
    {
        $sql = "
            INSERT INTO 
                compare_labora_concilia(
                    employee_number,
                    name,
                    lastname1,
                    lastname2,
                    created,
                    origen_id,
                    consecutive, 
                    operation
                ) 
            SELECT
                employee_number,
                name,
                lastname1,
                lastname2,
                created, 
                origen_id, 
                consecutive, 
                operation 
            FROM 
                interface_labora
            WHERE 
                date(created) = date(sysdate()) 
            AND 
                consecutive = $consecutive
            AND 
                employee_number > 0";
            $consultas = DB::select(DB::raw($sql));
        if ($consultas) {
            return 1;
        }
    }
    public static function conexion2($dato)
    {
        $db2 = null;
        $llave = 'inxdix_2018';
        $query = "
            SELECT name, rdbms_type, sox, hostname, ip_address, port
            , CAST(AES_DECRYPT(db_name, '$llave') AS CHAR(250)) DB_NAME
            , CAST(AES_DECRYPT(db_instance, '$llave') AS CHAR(250)) DB_INSTANCE
            , CAST(AES_DECRYPT(db_user, '$llave') AS CHAR(250)) DB_USER
            , CAST(AES_DECRYPT(db_psw, '$llave') AS CHAR(250)) DB_PSW
            FROM rdbms
            WHERE id=$dato
        ";
        $datos = DB::select(DB::raw($query));
        $tbd = $datos[0]->rdbms_type;
        $host = $datos[0]->hostname;
        $ip = $datos[0]->ip_address;
        $prt = $datos[0]->port;
        $usr = $datos[0]->DB_USER;
        $psw = $datos[0]->DB_PSW;
        $name = $datos[0]->DB_NAME;
        $conexionName = $datos[0]->name;
        // print_r($datos);
        // die();

        switch ($tbd)
        {
            case 1: //conexion oracle
                $db2 = "";
                $con = $ip.":".$prt."/".$name;
                
                if (!$db2 = @oci_connect($usr, $psw, $con)) {
                    $responseError = array();
                    $err = oci_error();
                    $responseError["error"][$conexionName]["number"] = $err["code"];
                    $responseError["error"][$conexionName]["msj"] = $err["message"];
                    
                    return $responseError;
                }
                break;
            case 2://conexion sqlserverSQLEXPRESS
                $serverName = $ip . "\\" . $name . ", " . $prt;
                $connectionInfo = array("Database" => $name, "UID" => $usr, "PWD" => $psw);    
                $db2 = @sqlsrv_connect($serverName, $connectionInfo);
                if($db2 === false) {
                    // Manejo de Errores
                    $errors = sqlsrv_errors();
                    if($errors != null) {
                        $responseError = array();
                        foreach($errors as $error) {
                            $responseError["error"][$conexionName]["number"] = $error['code'];
                            $responseError["error"][$conexionName]["msj"] = $error['message'];
                        }
                        
                        return $responseError;
                    }
                }
                break;
            case 3: // conexion ODBC
                $db2="";
                $nombre = $name;
                $server = $ip;
                $user = $usr;
                $pass = $psw;
                //echo $server."---".$user."---".$pass;    //exit();
                $db2 = @odbc_connect($server, $user, $pass);
                if(!$db2) {
                    $responseError = array();
                    $responseError["error"][$conexionName]["number"] = odbc_error();
                    $responseError["error"][$conexionName]["msj"] = odbc_errormsg();
                    
                    return $responseError;
                }
                break;
            case 4:                
                $con = $ip.":".$prt ;
                               
                /* verificar la conexión */
                if(!$db2 = @mysqli_connect($con, $usr, $psw, $name)) {
                    $responseError = array();
                    $responseError["error"][$conexionName]["number"] = mysqli_connect_errno();
                    $responseError["error"][$conexionName]["msj"] = mysqli_connect_error();
                    
                    return $responseError;
                }
                
                break;
            case 5: // conexion ODBC para Pixel
                // $db2 = @odbc_connect("Driver={SQL Anywhere 12};"
                // . "UID=$usr;"
                // . "PWD=$psw;"
                // . "Host=$ip;"
                // . "DatabaseName=$db_name;", '', '');

                if(!$db2 = @odbc_connect("Driver={SQL Anywhere 12};"."UID=$usr;". "PWD=$psw;". "Host=$ip;". "DatabaseName=$db_name;", '', '')) 
                {
                    $responseError = array();
                    $responseError["error"][$conexionName]["number"] = odbc_error();
                    $responseError["error"][$conexionName]["msj"] = odbc_errormsg();
                    
                    return $responseError;
                }
                break;
        
        }
        return $db2;
    }
    public static function take_dates($consecutivo = "", $operacion = "")
    {
        $sql = "
            SELECT CASE WHEN emp.username IS NULL then 0 ELSE IF (CONVERT(emp.extensionAttribute15, SIGNED) != 0, emp.extensionAttribute15, 
            IF (emp.extensionAttribute15 REGEXP('[0-9]'),CONVERT(SUBSTR(emp.extensionAttribute15 FROM 2) , SIGNED),0)) 
            END AS EMP_KEYEMP, usr.username AS USU_NOMUSU, emp.lastname1,emp.lastname2, usr.aplication_id,usr.consecutive,operation
            FROM activedirectory_users usr
            LEFT JOIN activedirectory_employees emp ON usr.username=emp.username
            WHERE usr.consecutive = (SELECT MAX(consecutive) FROM activedirectory_users)
        ";
        
        $resultado = DB::select(DB::raw($sql));
        $x = 0;
        $salida = array();
        
        foreach ($resultado as $corrida) {
            if ($corrida->consecutive == $consecutivo) {
                $salida[$x] = $corrida;
                $x++;
            }
        }

        return $salida;
    }

    public static function give_dates($datos)
    {
        $consulta = "";
        $count = 0;
        
        if(count($datos) > 0) {
            
            foreach ($datos as $dato) {
                if (!is_numeric($dato->EMP_KEYEMP)) {
                    $dato->EMP_KEYEMP = 0;
                }
                
                $consulta .= "(
                    ".$dato->EMP_KEYEMP. 
                    ",'".utf8_encode($dato->USU_NOMUSU).
                    "','".utf8_encode($dato->lastname1).
                    "','".utf8_encode($dato->lastname2).
                    "',".$dato->aplication_id.
                    ",".$dato->consecutive.
                    ",".$dato->operation;
                $count++;
                if($count < count($datos)) {
                    $consulta .= "),";
                } else {
                    $consulta .= ")";
                }
                    
            }

            $consulta = "INSERT INTO interface_application (employee_number, name, lastname1, lastname2, application_id, consecutive, operation) VALUES " . $consulta;
            
            $resultado = DB::select(DB::raw($consulta));
            if ($resultado) {
                return $count;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public $timestamps = false;

    public function lista($id)
    {
        $result = Rdbmsqrys::selectRaw('
            applications.name AS aplicacion,
            rdbms_qrys.id AS id_query,
            rdbms_qrys.status AS status,
            rdbms_qrys.qry_read AS query,
            rdbms.name AS conexion
        ')
        ->join('applications', 'applications.id',
            '=', 'rdbms_qrys.application_id')
        ->join('rdbms', 'rdbms.id', '=',
            'rdbms_qrys.rdbms_id')
        ->where("rdbms_id","=", $id)
        ->where("select_in","!=", 0)
        ->get();
        return $result;
    }

}
