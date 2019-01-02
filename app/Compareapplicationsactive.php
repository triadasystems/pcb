<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compareapplicationsactive extends Model
{
    protected $table = 'compare_applications_active';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'employee_number',
        'name',
        'lastname1',
        'lastname2',
        'created',
        'application_id',
        'consecutive',
        'operation'
    ];
}
