<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
