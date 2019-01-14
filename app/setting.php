<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class setting extends model
{
    //protected $table="tcs_external_employees";
    public static function settings($id="0")
    {
        $and="";
        if ($id!=0) {
            $and=" AND id=$id";
        }
        $sql="SELECT `id`, `settings`, `description`, `status`, `created_at`, `updated_at`, `extra` FROM `tcs_settings` WHERE 1=1 $and";
        $consultas = DB::select(DB::raw($sql));
        return $consultas;
    }
    public static function updsettings($data,$id)
    {
        DB::table('tcs_settings')
            ->where('id', $id)
            ->update($data);
    }
}

?>