<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Rdbmsqrys;
use App\flowModel;
use App\logReportsModel;

class migracionController extends Controller
{

    const LIMIT_INCREMENT = 2;
    public $errores = array();
    public $ok_conec = array();
    public $complemento = array();

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view("concilia.concilia");
    }
    
    public function obtenerTotalConexiones($a = 1)
    {
        if(permisosExecutionAjax() !== false) {
            $b=3;//labora_baja
            $l=2;//labora activos
    
            if($a==1) {
                $querys = Rdbmsqrys::recupera_query($l, null, 2);
            } else if($a==2) {
                $querys = Rdbmsqrys::recupera_query($b, null, 2);
            }
    
            echo $querys;
            exit();
        } else {
            echo "middleExecution";
            exit();
        }
    }
    
    public function recorre_ok_error($status, $con_oks)
    {
        foreach ($con_oks as $value)
        {
            array_push($status, $value);
        }
        return $status;
    }

    public function obtenerConsecutivo(){
        $consecutivo = time(date("ymdhms"));
        echo $consecutivo;
    }

    //public function ejecutarMigracion(Request $request, $a)
    public function ejecutarMigracion(Request $request)
    {
        $data=$request->post();
        $a="";
        $consecutivo = $data["consecutivo"];
        $a = $data["a"];
        if ($data["ejecutables"]== 0)
        {
            $excelTel = $this->ejecutable2($consecutivo,$a);
            if ($excelTel === true) 
            {
                if (@is_array($data["status"]["errores"])) {
                    $data["status"]["errores"]=$this->recorre_ok_error($data["status"]["errores"], $this->errores);
                } else {
                    $data["status"]["errores"] = $this->errores;
                }
            } else {
                if(@is_array($data["status"]["ok"])) {
                    $data["status"]["ok"] = $this->recorre_ok_error($data["status"]["ok"], $this->ok_conec);
                } else {
                    $data["status"]["ok"] = $this->ok_conec;
                } 
            }
            $data["ejecutables"] = 1;
            echo json_encode($data);
            exit();
        }
        if ($data["ejecutablesad"] == 0)
        {
            $excelTel = $this->ejecutablead($consecutivo,$a);
            if ($excelTel === true) 
            {
                if (@is_array($data["status"]["errores"])) {
                    $data["status"]["errores"]=$this->recorre_ok_error($data["status"]["errores"], $this->errores);
                } else {
                    $data["status"]["errores"] = $this->errores;
                }
            } else {
                if(@is_array($data["status"]["ok"])) {
                    $data["status"]["ok"] = $this->recorre_ok_error($data["status"]["ok"], $this->ok_conec);
                } else {
                    $data["status"]["ok"] = $this->ok_conec;
                } 
            }
            $data["ejecutablesad"] = 1;
            echo json_encode($data);
            exit();
        }
        if ($data["recorrido"] == 0 && $data["ejecutables"] == 1) {
            $cant = $data["cant"];
            $total = $data["total"];
            
            if ($cant <= $total) {
                $cantActualizado = $this->migrando($a, 3, 2, $cant, $total, $consecutivo);
                $data["cant"] = $cantActualizado;
                if(@is_array($data["status"]["errores"])) {
                    $data["status"]["errores"] = $this->recorre_ok_error($data["status"]["errores"], $this->errores);
                } else {
                    $data["status"]["errores"] = $this->errores;
                }
                if(@is_array($data["status"]["ok"])) {
                    $data["status"]["ok"] = $this->recorre_ok_error($data["status"]["ok"], $this->ok_conec);
                } else {
                    $data["status"]["ok"] = $this->ok_conec;
                }

            } else {
                $data["recorrido"] = 1;
            }

            echo json_encode($data);
            exit();
        }
        if($data["pasa"] == 0 && $data["recorrido"] == 1) 
        {
            if($this->pasa_ctive($consecutivo,$a) === false) {
                if(@is_array($data["status"]["errores"])) {
                    $data["status"]["errores"] = $this->recorre_ok_error($data["status"]["errores"], $this->errores);
                } else {
                    $data["status"]["errores"] = $this->errores;
                }
            } else {
                if(@is_array($data["status"]["ok"])) {
                    $data["status"]["ok"] = $this->recorre_ok_error($data["status"]["ok"], $this->ok_conec);
                } else {
                    $data["status"]["ok"] = $this->ok_conec;
                }
            }
            $data["pasa"] = 1;

            echo json_encode($data);
            exit();
        }
        if($data["zero_bueno"] == 0 && $data["pasa"] == 1) {
            $querys = Rdbmsqrys::zero_app($consecutivo);
            $querys = Rdbmsqrys::bueno_app($consecutivo);
            $querys = Rdbmsqrys::zero_labora($consecutivo);
            $querys = Rdbmsqrys::bueno_labora($consecutivo);
            $data["zero_bueno"] = 1;
            echo json_encode($data);
            exit();
        }
        if($data["limpia"] == 0 && $data["recorrido"] == 1 && $data["zero_bueno"] == 1 && $data["pasa"] == 1 && $data["ejecutables"] == 1)
        {
            if ($a == 1) 
            { // concilia
                $querys = Rdbmsqrys::libera_labora();
                $querys = Rdbmsqrys::libera_application();
                $labora_concilia = Rdbmsqrys::labora_concilia($consecutivo);
                $app_concilia = Rdbmsqrys::app_concilia($consecutivo);
                $data["limpia"] = 1;
            }
            else if ($a == 2) 
            {//baja
                $querys = Rdbmsqrys::libera_remove();
                $querys = Rdbmsqrys::libera_app_active();
                $labora_remove = Rdbmsqrys::labora_remove($consecutivo);
                $bueno_app = Rdbmsqrys::app_activo($consecutivo);
                $data["limpia"] = 1; 
            }
            echo json_encode($data);
            exit();
        }
    }
    public function migrando($a, $b, $l, $cant, $total, $consecutivo) {
        if ($cant <= $total) {
            if ($a == 1) {//conciliacion
                $con2 = "";
                $querys = Rdbmsqrys::recupera_query($l, $cant); // se guardan los querys recuperados de la consulta
                $countErrorInsert = 0;
                foreach ($querys as $consultas) {// recorrido de la consultas
                    
                    $rdbms_type = $consultas->rdbms_type;
                    $id_consul = $consultas->id; //recupero el id de la consulta
                    $id_conexion = $consultas->rdbms_id; //recupero el id de la conexion para obtener los datos de
                    $app_id = $consultas->application_id; //recupera el id de la aplicacion para la inserccion del dato
                    
                    if ($consultas->select_in == 1) {//aplicacion
                        
                        $con2 = Rdbmsqrys::conexion2($id_conexion);
                        
                        if(@is_array($con2) && isset($con2["error"])) {
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Conexión", "app_id" => $id_consul));
                        } else {
                            $sql = $consultas->qry_read;
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);
                            
                                if(@is_array($data) && isset($data["error"])) {
                                    $error = array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul);
                                    array_push($this->errores, $error);
                                } else {
                                    $incre = 0;
                                    
                                    foreach ($data as $val) {
                                        if(isset($val['EMP_KEYEMP'])){
                                            $num_emp = $val['EMP_KEYEMP'];
                                        } else {
                                            $num_emp = 0;
                                        }
                                        
                                        $resultado = intval(preg_replace('/[^0-9]+/', '', $num_emp), 10);
                                        $fullName= str_replace("'","",$val['FULL_NAME']);
                                        $descripcion= str_replace("'","",$val['DESCRIPTION']);
                                        $datos['number_employe'] = $resultado;
                                        $datos['username'] = $val['USU_NOMUSU'];
                                        $datos['fullname'] = utf8_encode($fullName);
                                        $datos['descripcion'] = utf8_encode($descripcion);
                                        $datos['aplicacion'] = $app_id;
                                        $datos['consecutive'] = $consecutivo;
                                        $datos['operation'] = $a;
                                  
                                        $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                        
                                        if ($insert != 1) {
                                            $countErrorInsert++;
                                        }
                                        $incre++;
                                    }

                                    array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }                   
                        }
                    } else if ($consultas->select_in == 2){//labora
                        $sql = $consultas->qry_read;
                        $con2 = Rdbmsqrys::conexion2($id_conexion);
                        
                        if(@is_array($con2) && isset($con2["error"])) {
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Conexión", "app_id" => $id_consul));
                        } else {
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);
                            
                            if(@is_array($data) && isset($data["error"])) {
                                array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul));
                            } else {
                                $inc = 0;
                                foreach ($data as $val) {
                                    if(isset($val['EMP_KEYEMP'])){
                                        $datos['number_employe'] = $val['EMP_KEYEMP'];
                                    } else {
                                        $datos['number_employe'] = 0;
                                    }
                                    
                                    // $datos['number_employe'] = $val['EMP_KEYEMP'];
                                    $datos['fullname'] = utf8_encode($val['FULL_NAME']);
                                    $datos['aplicacion'] = $app_id;
                                    $datos['consecutive'] = $consecutivo;
                                    $datos['operation'] = $a;

                                    $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                
                                    $inc++;
                                }
                                
                                //array_push($this->ok_conec,utf8_encode(utf8_encode($consultas->name)));
                                array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }
                        }
                    } else if ($consultas->select_in == 4){
                        $sql = $consultas->qry_read;
                        $con2 = Rdbmsqrys::conexion2($id_conexion);
                        
                        if(@is_array($con2) && isset($con2["error"])) {
                            // $this->errores = $data["error"];
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", $consultas->name) => "Conexión", "app_id" => $id_consul));
                        } else {
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);

                            if(@is_array($data) && isset($data["error"])) {
                                array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul));
                            } else {
                                foreach ($data as $value) {
                                    $datos['username'] = $value['USU_NOMUSU'];
                                    $datos['aplicacion'] = $app_id;
                                    $datos['consecutive'] = $consecutivo;
                                    $datos['operation'] = $a;
    
                                    $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                }
    
                                //array_push($this->ok_conec, utf8_encode($consultas->name));
                                array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }
                        }
                    }
                }
            } else if ($a == 2) {
                $querys = Rdbmsqrys::recupera_query($b, $cant); // se guardan los querys recuoerados de la consulta

                foreach ($querys as $consultas) {// recorrido de la consultas
                    $id_conexion = $consultas->rdbms_id; //recupero el id de la conexion para obtener los datos de
                    $app_id = $consultas->application_id; //recupera el id de la aplicacion para la inserccion del dato
                    $id_consul = $consultas->id; //recupero el id de la consulta
                    $rdbms_type = $consultas->rdbms_type;
                    $sql = $consultas->qry_read;           

                    if ($consultas->select_in == 1) {//aplicacion oralce
                        
                        $con2 = Rdbmsqrys::conexion2($id_conexion);
                        if(@is_array($con2) && isset($con2["error"])) {
                            // echo $consultas->name."<br>";
                            // echo str_replace(array(" ", "'", '"', "  "), "_", $consultas->name);
                            // die("error");
                            // $this->errores = $data["error"];
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Conexión", "app_id" => $id_consul));
                        } else {
                            // die("normal");
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);
                            $id_query = $consultas->id;

                            if(@is_array($data) && isset($data["error"])) {
                                array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul));
                            } else {

                                foreach ($data as $val) {
                                    if(isset($val['EMP_KEYEMP'])){
                                        $num_emp=$val['EMP_KEYEMP'];
                                    } else {
                                        $num_emp=0;
                                    }

                                    $valor=str_replace("'","",$val['DESCRIPTION']);
                                    $resultado = intval(preg_replace('/[^0-9]+/', '', $num_emp), 10);
                                    $fullName = str_replace("'","",$val['FULL_NAME']);
                                    $datos['number_employe'] = $resultado;
                                    $datos['username'] = $val['USU_NOMUSU'];
                                    $datos['fullname'] = utf8_encode($fullName);
                                    $datos['descripcion'] = $valor;
                                    $datos['aplicacion'] = $app_id;
                                    $datos['consecutive'] = $consecutivo;
                                    $datos['operation'] = $a;

                                    $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                }
                                //array_push($this->ok_conec, utf8_encode($consultas->name));
                                array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }
                        }
                    } else if ($consultas->select_in == 4){
                        $sql = $consultas->qry_read;
                        $con2 = Rdbmsqrys::conexion2($id_conexion);

                        if(@is_array($con2) && isset($con2["error"])) {
                            // $this->errores = $data["error"];
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Conexión", "app_id" => $id_consul));
                        } else {
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);

                            if(@is_array($data) && isset($data["error"])) {
                                array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul));
                            } else {

                                if(count($data) > 0) {
                                    foreach ($data as $value)
                                    {
                                        $datos['username'] = $value['USU_NOMUSU'];
                                        $datos['aplicacion'] = $app_id;
                                        $datos['consecutive'] = $consecutivo;
                                        $datos['operation'] = $a;
                                        $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                    }
                                }
                                //array_push($this->ok_conec, utf8_encode($consultas->name));
                                array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }
                        }
                    } else if ($consultas->select_in == 3) {//labora BAJA.
                        $sql = $consultas->qry_read;
                        $con2 = Rdbmsqrys::conexion2($id_conexion);

                        if(@is_array($con2) && isset($con2["error"])) {
                            // $this->errores = $data["error"];
                            array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Conexión", "app_id" => $id_consul));
                        } else {
                            $data = Rdbmsqrys::ejecuta_consulta($con2, $sql, $rdbms_type, $consultas->name);
                                    
                            if(@is_array($data) && isset($data["error"])) {
                                array_push($this->errores, array(str_replace(array(" ", "'", '"', "  "), "_", utf8_encode($consultas->name)) => "Consulta", "app_id" => $id_consul));
                            } else {
                                
                                $i = 0;
                                foreach ($data as $val)
                                {
                                    if(isset($val['EMP_KEYEMP'])){
                                        $datos['number_employe'] = $val['EMP_KEYEMP'];
                                    } else {
                                        $datos['number_employe'] = 0;
                                    }
                                    $datos['fullname'] = utf8_encode($val['FULL_NAME']);
                                    $datos['aplicacion'] = $app_id;
                                    $datos['consecutive'] = $consecutivo;
                                    $datos['operation'] = $a;
                                    $datos['aplicacion'] = $app_id;
                                    $datos['fecha_baja'] = $val['FECHA_BAJA'];
                                    $datos['tipo_baja'] = $val['TIPO_BAJA'];
                                    $insert = Rdbmsqrys::inserta_application($consultas->select_in, $datos, $rdbms_type);
                                }
                                //die();

                                //array_push($this->ok_conec, utf8_encode($consultas->name));
                                array_push($this->ok_conec, array(utf8_encode($consultas->name)=>"OK", "app_id" => $id_consul));
                            }
                        }
                    }
                }
            }

            $cant = $cant+self::LIMIT_INCREMENT;
            
            return $cant;
        }
    }
    public function ejecutable() {
        exec("D:\Lectura_ActiveDirectory\ADTelevisa.exe");
    }

    public function ejecutable2($cons, $operacion) {
        $cmd = "D:\\Lectura_Excel\\ExcelesTelevisa.exe ".intval($cons)." $operacion";
        exec($cmd);

        if($this->buscarTxt($cons) === false) {
            array_push($this->errores, array("ExcelesTelevisa" => "Falla en Excel"));
            
            return true;
        } else {
            //array_push($this->ok_conec, "ExcelesTelevisa");
            array_push($this->ok_conec, array("ExcelesTelevisa" => "OK"));

            return false;
        }
    }

    public function ejecutablead($cons, $operacion)
    {
        $cmd = "D:\\Active_Directory_PBC\\ADTelevisa.exe 2 ".intval($cons)." $operacion";
        exec($cmd);

        //array_push($this->ok_conec, "ActiveDirectory");
        array_push($this->ok_conec, array("ActiveDirectory" => "OK"));

        return false;
    }

    public function buscarTxt($cons) {
        $pathText = "D:\\ProyectosTF33\\ExpressTelevisa\\Trabajando";

        if(is_dir($pathText)) {
            $files = scandir($pathText);
            
            foreach(scandir($pathText) as $file){
                if(strpos($file, ".txt")) {
                    $fileConsecutive = substr($file,0,-25);
                    if($fileConsecutive == $cons){
                        $fileRead = file($pathText."/".$file);
                        if(count($fileRead) > 0) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
    }

    public function pasa_ctive($consecutivo, $opera) {
        $resultado = Rdbmsqrys::take_dates($consecutivo, $opera);
        return Rdbmsqrys::give_dates($resultado);
    }
    public function log(Request $request)
    {
        $data=$request->post();
        $usu=Auth::user()->id;
        $operacion = $data["oper"];
        $consecutivo = $data["con"];
        if(array_key_exists('errores', $data["datos"]))
        {
            $state=1;
            flowModel::agregar($usu,$state,$operacion,$consecutivo);
            $id_flow=flowModel::consultar($consecutivo);
        }
        else
        {
            $state=0;
            flowModel::agregar($usu,$state,$operacion,$consecutivo);
            $id_flow=flowModel::consultar($consecutivo);
        }
        
        foreach ($data["datos"]["errores"] as $key => $value)
        {
            $consul=NULL;
            foreach ($value as $k => $v)
            {
                if ($k == "app_id")
                {
                    $consul=$v;
                }
                if ($k != "app_id")
                {
                    $conexion_nom=$k;
                    $status = $v;
                    if ($status == "Conexión")
                    {
                        $status="conexion";
                    }
                }
            }
            logReportsModel::agregar($conexion_nom,$status,$id_flow,$consul);
        }
        foreach ($data["datos"]["ok"] as $key => $value)
        {
            $consul=NULL;
            foreach ($value as $k => $v)
            {
                if ($k == "app_id")
                {
                    $consul=$v;
                }
                if ($k != "app_id")
                {
                    $conexion_nom=$k;
                    $status = $v;
                }
            }
            logReportsModel::agregar($conexion_nom,$status,$id_flow,$consul);
        }
    }
}
