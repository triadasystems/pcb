<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Rdbmsqrys;
use App\flowModel;
use App\logReportsModel;
use Illuminate\Support\Facades\Crypt;

use Config;

class ejecutableController extends Controller
{
    protected $passDB;
    protected $passLDAP;

    public function __construct() {
        $this->passDB = Crypt::decryptString(config('database.connections.mysql.password'));
        $this->passLDAP = Crypt::decryptString(config('app.ldap_password'));
    }
    public function ejecutable() {
        $dat="D:\\Active_Directory_PBC\\ADTelevisa.exe 1 ".$this->passDB." ".$this->passLDAP;
        exec($dat);
    }
}
