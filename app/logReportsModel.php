<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class logReportsModel extends Model
{
    public static function agregar($conexion_nom,$status,$id_flow,$consul)
    {
        if (is_null($consul)){
            
            $sql = "INSERT INTO `log_reports`(`connection`, `status`, `id_flow`) 
            VALUES ('".$conexion_nom."','".$status."',$id_flow)";
        }
        else
        {
            $sql = "INSERT INTO `log_reports`(`connection`, `status`, `id_flow`, `id_qrys`) 
            VALUES ('".$conexion_nom."','".$status."',$id_flow,$consul)";
        }
        
        //print_r($sql);
        $consultas = DB::select(DB::raw($sql));
        //print_r($sql);

    }
    
}

?>