<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    protected $table = 'applications';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'instance_id',
        'responsability_id',
        'name',
        'alias',
        'active'
    ];
}
