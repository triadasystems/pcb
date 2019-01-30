<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InterfaceLabora extends Model
{
    protected $table="interface_labora";

    protected $fillable = [
        'id',
        'employee_number',
        'name',
        'lastname1',
        'lastname2',
        'created',
        'origen_id',
        'consecutive',
        'operation',
        'fecha_baja',
        'motivo_baja'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function employeeByNumber($number) {
        return InterfaceLabora::where("employee_number", "=", $number)
        ->where("consecutive", "=", function($subquery){
            $subquery->select(DB::raw("max(consecutive)"))
            ->from(with(new InterfaceLabora)->getTable())->where("origen_id", "<>", 999);
        })
        ->get()
        ->toArray();
    }
}
