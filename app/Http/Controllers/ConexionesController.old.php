<?php

namespace App\Http\Controllers;

use App\rdbms;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class ConexionesController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function validateBlackList($psw) {
        $blackList = array(
            'select', 
            'insert', 
            'into', 
            'delete', 
            'update', 
            'alter', 
            'show',
            'database',
            'schema',
            'SELECT', 
            'INSERT', 
            'INTO', 
            'DELETE', 
            'UPDATE', 
            'ALTER', 
            'SHOW',
            'DATABASE',
            'SCHEMA',
            'Select', 
            'Insert', 
            'Into', 
            'Delete', 
            'Update', 
            'Alter', 
            'Show',
            'Database',
            'Schema'
        );
        
        $countDeteccion = 0;
        
        foreach ($blackList as $row) {
            if(strpos($psw, $row)) {
                $countDeteccion = $countDeteccion+1;
            }
        }
        
        if($countDeteccion > 0) {
            return false;
        }
        
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de conexiones',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view("conexiones.lista");
    }

    public function data(rdbms $rdbms)
    {
        return Datatables::of($rdbms->lista())->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("conexiones.register");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->validateBlackList($request->post('db_psw')) === false) {
            return redirect()->route('crear_conexiones')->with('errorMsj', 'Ha surgido un error al querer ingresar la conexión. Verifique el password ingresado.');
        }
        
        $request->validate([
            "name" => ["required", "regex:/^[A-Za-z0-9_[:space:]]+$/"],
            "rdbms_type" => ["required", "integer"],
            "sox" => ["required", "integer"],
            "hostname" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "ip_address" => ["required", "ip"],
            "port" => ["required", "integer"],
            "db_name" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "db_user" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "db_psw" => [
                "required", 
                "min:16", 
                "max:25"
            ]
        ]);

        $key = 'inxdix_2018';
        $sql = "
            INSERT INTO rdbms(name, rdbms_type, sox, hostname, ip_address, port, db_name, db_instance, db_user, db_psw) VALUES 
            (
                '".$request->post('name')."',
                ".$request->post('rdbms_type').",
                ".$request->post('sox').",
                '".$request->post('hostname')."',
                '".$request->post('ip_address')."',
                ".$request->post('port').",
                AES_ENCRYPT('".$request->post('db_name')."','".$key."'),
                AES_ENCRYPT('','".$key."'),
                AES_ENCRYPT('".$request->post('db_user')."','".$key."'),
                AES_ENCRYPT('".str_replace("\\", "\\\\", $request->post('db_psw'))."','".$key."')
            )
        ";
        try {
            DB::select(DB::raw($sql));

            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado el alta de una conexión',
                    'tipo' => 'alta',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route('listaConexiones')->with('confirmacion', 'registrado');
        } catch(Exception $e) {
            return redirect()->route('listaConexiones')->with('errorMsj', 'Error al intentar guardar la conexión. Si el problema persiste contacte al admon.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\rdbms  $rdbms
     * @return \Illuminate\Http\Response
     */
    public function show(rdbms $rdbms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\rdbms  $rdbms
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sql = '
        SELECT id, name, rdbms_type, sox, hostname, ip_address, port
        , CAST(AES_DECRYPT(db_name, "inxdix_2018") AS CHAR(250)) db_name
        , CAST(AES_DECRYPT(db_user, "inxdix_2018") AS CHAR(250)) db_user
        , CAST(AES_DECRYPT(db_psw, "inxdix_2018") AS CHAR(250)) db_psw
        FROM rdbms
        WHERE id='.$id.';
        ';

        $rdbms = DB::select(DB::raw($sql));
        
        return view('conexiones.edit', compact('rdbms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\rdbms  $rdbms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($this->validateBlackList($request->post('db_psw')) === false) {
            return redirect()->route('updateCon', $id)->with('errorMsj', 'Ha surgido un error al querer ingresar la conexión. Verifique el password ingresado.');
        }
        
        $request->validate([
            "name" => ["required", "regex:/^[A-Za-z0-9_[:space:]]+$/"],
            "rdbms_type" => ["required", "integer"],
            "sox" => ["required", "integer"],
            "hostname" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "ip_address" => ["required", "ip"],
            "port" => ["required", "integer"],
            "db_name" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "db_user" => ["required", "regex:/^[A-Za-z0-9_]+$/"],
            "db_psw" => [
                "required", 
                "min:16", 
                "max:25"
            ]
        ]);

        $key = 'inxdix_2018';
        $sql = "
            UPDATE rdbms SET
                name = '".$request->post('name')."',
                rdbms_type = ".$request->post('rdbms_type').",
                sox = ".$request->post('sox').",
                hostname = '".$request->post('hostname')."',
                ip_address = '".$request->post('ip_address')."',
                port = ".$request->post('port').",
                db_name = AES_ENCRYPT('".$request->post('db_name')."','".$key."'),
                db_user = AES_ENCRYPT('".$request->post('db_user')."','".$key."'),
                db_psw = AES_ENCRYPT('".str_replace("\\", "\\\\", $request->post('db_psw'))."','".$key."')
            WHERE id = ".$id."
        ";
        try {
            DB::select(DB::raw($sql));
            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado la modificación de una conexión',
                    'tipo' => 'modificacion',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route('listaConexiones')->with('actualizado', 'registrado');
        } catch(Exception $e) {
            return redirect()->route('listaConexiones')->with('errorMsj', 'Error al intentar guardar la conexión. Si el problema persiste contacte al admon.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\rdbms  $rdbms
     * @return \Illuminate\Http\Response
     */
    public function destroy(rdbms $rdbms)
    {
        //
    }

    public function testConexion(Request $request) {
        $data = array();
        parse_str($request->post('data'), $data);
        $conStatus = $this->tipoConexion($data);
        if($conStatus !== false) {
            if($conStatus > 0) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo 'failed';
        }
    }

    public function tipoConexion($data) {
        $tipoDB = $this->rdbmsType($data['rdbms_type']);
        
        if ($tipoDB['tipo'] != 'failed' && $tipoDB['tipo'] != 'oracle') {
            try {
                try {
                    config(['database.connections.dinamyc' => [
                        'driver' => $tipoDB['tipo'],
                        'host' => $data['ip_address'],
                        'port' => $data['port'],
                        'database' => $data['db_name'],
                        'username' => $data['db_user'],
                        'password' => $data['db_psw'],
                        'encriptado' => false
                    ]]);
                    $con = DB::connection('dinamyc')->select(DB::raw($tipoDB['query']));
                    return count($con);
                } catch (QueryException $e) {
                    // var_dump($e->getMessage());
                    // die();
                    return false;
                }
            }catch (FatalErrorException $e){
                return false;
            }
        } else if ($tipoDB['tipo'] != 'failed' && $tipoDB['tipo'] == 'oracle') {
            $con = '';
            $dataCon = $data['ip_address'].":".$data['port']."/".$data['db_name'];
            if($con = @oci_connect($data['db_user'], $data['db_psw'], $dataCon)) {
                if (oci_error($con)) {
                    return false;
                } else {
                    return 1;
                }
            } else {
                return false;
            }
        }
        
        return false;
    }

    public function rdbmsType($tipo) {
        $dataDB = array();
        switch ($tipo) {
            case 1:
                $dataDB['tipo'] = 'oracle';
                $dataDB['query'] = 'SELECT table_name FROM user_tables';
                break;
            case 2:
                $dataDB['tipo'] = 'sqlsrv';
                $dataDB['query'] = 'SELECT name FROM sys.Tables GO';
                // $dataDB['query'] = "SELECT name FROM sysobjects WHERE type = 'U'";
                break;
            case 4:
                $dataDB['tipo'] = 'mysql';
                $dataDB['query'] = 'SHOW TABLES';
                break;
            default:
                $dataDB['tipo'] = 'failed';
                $dataDB['query'] = 'failed';
                break;
        }
        return $dataDB;
    }

    public function desactivar(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            switch ($request->post("tipo")) {
                case 'Activo':
                    if(rdbms::where("id", "=", $request->post("id"))->update(['status' => 'Activo'])) {
                        echo "true";
                        
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la activación de una conexión',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
                
                case 'Inactivo':
                    if(rdbms::where("id", "=", $request->post("id"))->update(['status' => 'Inactivo'])) {
                        echo "true";
                        
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la desactivación de una conexión',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
            }

            echo  "false";
            exit();
        } else {
            echo "middleUpgrade";
            exit();
        }
    }
}
