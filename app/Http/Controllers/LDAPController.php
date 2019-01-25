<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Config;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');

class LDAPController extends Controller
{
    protected $passLDAP;

    public function __construct()
    {
        $this->passLDAP = Crypt::decryptString(config('app.ldap_password'));
        $this->middleware('auth');
    }
    
    public function conectionLDAP($email, $pass) {
        // conexión al servidor LDAP
        $ldapconn = ldap_connect("ldap://corp.televisa.com.mx", 636) or die("Could not connect to LDAP server.");

        if ($ldapconn) {
            // autenticación anónima
            $ldapbind = @ldap_bind($ldapconn, $email, $pass);

            return $ldapbind;
        } else {
            return 0;
        }
    }

    public function validateUser($email = null) {
        $ldap_email = "sysadmin_desa@televisa.com.mx";
        $ldap_password = $this->passLDAP;

        $ldap_con = ldap_connect("ldap://corp.televisa.com.mx", 636);
        
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
        
        if(@ldap_bind($ldap_con, $ldap_email, $ldap_password)) {
            // $filter = "(objectClass=user)";
            $filter = "(samaccountname=$email)";
            $result = ldap_search($ldap_con,"DC=corp,dc=televisa,DC=com,DC=mx",$filter) or exit("Unable to search");
            $entries = ldap_get_entries($ldap_con, $result);
            
            if($entries["count"] == 0) {
                return false;
            }
            return true;
        } else {
            return "Credenciales invalidas";
        }
    }
}