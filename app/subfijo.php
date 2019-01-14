<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class subfijo extends Model
{
    protected $table="tcs_subfijo";

    protected $fillable = [
        'id', 
        'subfijo', 
        'created_at'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public static function recuperar_subfijo()
    {
        $consultas = subfijo::where("subfijo", function($subquery){
            $subquery->selectRaw('max(subfijo)')->from('tcs_subfijo');
        })
        ->get()
        ->toArray();

        return $consultas;
    }
    public static function nuevo($dato)
    {
        DB::table('tcs_subfijo')->insert($dato);
    }
}
