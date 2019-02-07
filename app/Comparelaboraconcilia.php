<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comparelaboraconcilia extends Model
{
    protected $table = 'compare_labora_concilia';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'employee_number',
        'name',
        'lastname1',
        'lastname2',
        'created',
        'origen_id',
        'consecutive',
        'operation'
    ];
    public function employeeByNumber($number)
    {
        return Comparelaboraconcilia::where("employee_number", "=", $number)
        ->where("consecutive", "=", function($subquery){
            $subquery->select(DB::raw("max(consecutive)"))
            ->from(with(new Comparelaboraconcilia)->getTable())->where("origen_id", "<>", 999);
        })
        ->get()
        ->toArray();
    }
}
