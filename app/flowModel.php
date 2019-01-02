<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class flowModel extends Model
{
   public static function  agregar($usu,$state,$operacion,$consecutivo)
   {
        $sql="INSERT INTO `flow`(`consecutive`, `status`, `tipo`, `id_users`) 
                VALUES ($consecutivo,$state,$operacion,$usu)";
        $consultas = DB::select(DB::raw($sql));
   }
   public static function consultar($consecutivo)
   {
       $sql="SELECT id FROM flow WHERE consecutive=$consecutivo";
       $id = DB::select(DB::raw($sql));
       $data=$id[0]->id;
       return $data;
   }
}