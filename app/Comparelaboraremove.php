<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use App\ReporteResponsable;

class Comparelaboraremove extends Model
{
    protected $table = 'compare_labora_remove';
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
    public function v_bajas()
    {
        $responsables = ReporteResponsable::select('nombre', 'numero', DB::raw('SUM(tipo) AS tipo'))
                ->whereIn("numero",function ($query)
                {
                    $query->select('employee_number')->from("compare_labora_remove");
                })
                ->groupBy('nombre', 'numero')->get()->toArray();
        return $responsables;
    }
}
