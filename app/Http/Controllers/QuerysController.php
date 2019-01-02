<?php

namespace App\Http\Controllers;

use App\rdbms;
use App\Rdbmsqrys;
use App\Applications;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class QuerysController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index($id)
    {
        DB::table('logbook_movements')->insert([
            [
                'ip_address' => $this->ip_address_client, 
                'description' => 'Visualización de la lista de consultas de una conexión',
                'tipo' => 'vista',
                'id_user' => Auth::user()->id
            ]
        ]);

        return view("consultas.lista")->with('id', $id);
    }

    public function validateBlackList($psw) {
        $blackList = array(
            'insert', 
            'into', 
            'delete', 
            'update', 
            'alter', 
            'show',
            'database',
            'schema',
            'INSERT', 
            'INTO', 
            'DELETE', 
            'UPDATE', 
            'ALTER', 
            'SHOW',
            'DATABASE',
            'SCHEMA',
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

    public function data(Rdbmsqrys $Rdbmsqrys, $id)
    {
        $consultas = $Rdbmsqrys->lista($id);
        $arrayClean = array();
        $countArray = 0;
        foreach($consultas as $row) {
            $query = utf8_decode(utf8_decode($row["query"]));
            $query = str_replace("?", "¦", $query);
            
            $arrayClean[$countArray]["aplicacion"] = utf8_encode($row["aplicacion"]);
            $arrayClean[$countArray]["id_query"] = $row["id_query"];
            $arrayClean[$countArray]["status"] = $row["status"];
            $arrayClean[$countArray]["query"] = $query;
            $arrayClean[$countArray]["conexion"] = utf8_encode($row["conexion"]);
            $countArray = $countArray+1;
        }
        
        return Datatables::of($arrayClean)->make(true);
    }

    public function desactivar(Request $request) {
        if(permisosUpgradeAjax() !== false) {
            switch ($request->post("tipo")) {
                case 'Activo':
                    if(Rdbmsqrys::where("id", "=", $request->post("id"))->update(['status' => 'Activo'])) {
                        echo "true";
                        
                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la activación de una consulta',
                                'tipo' => 'modificacion',
                                'id_user' => Auth::user()->id
                            ]
                        ]);

                        exit();
                    }
                    break;
                
                case 'Inactivo':
                    if(Rdbmsqrys::where("id", "=", $request->post("id"))->update(['status' => 'Inactivo'])) {
                        echo "true";

                        DB::table('logbook_movements')->insert([
                            [
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Se ha realizado la desactivación de una consulta',
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
    
    public function edit($id)
    {
        $applications = Applications::select('id', 'name')->get()->toArray();
        $rdbmsqrys = Rdbmsqrys::where('id', '=', $id)->first()->toArray();
        $optionsTQ = array(1 => 'Aplicativos', 2 => 'Labora' , 3 => 'Bajas');

        return view("consultas.edit")->with(['id' => $id, 'applications' => $applications, 'rdbmsqrys' => $rdbmsqrys, 'optionsTQ' => $optionsTQ]);
    }

    public function create($id)
    {
        $applications = Applications::select('id', 'name')->get()->toArray();
        $rdbms = rdbms::select('id', 'name')->get()->toArray();
        return view("consultas.register")->with(['id' => $id, 'applications' => $applications, 'rdbms' => $rdbms]);
    }

    public function store(Request $request)
    {
        if($this->validateBlackList($request->post('qry_read')) === false) {
            return redirect()->route('crear_consultas')->with('errorMsj', 'Ha surgido un error al querer ingresar la consulta. Verifique los datos.');
        }
        
        $request->validate([
            "application_id" => ["required", "integer"],
            "select_in" => ["required", "integer"],
            "qry_read" => ["required"]
        ]);

        $sql = '
        INSERT INTO rdbms_qrys(application_id, rdbms_id, qry_read, select_in) VALUES 
            (
                '.$request->post('application_id').',
                '.$request->post('rdbms_id').',
                "'.$request->post('qry_read').'",
                '.$request->post('select_in').'
            )
        ';
        try {
            DB::select(DB::raw($sql));

            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado el alta de una consulta',
                    'tipo' => 'alta',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route('listaConsultas', $request->post('rdbms_id'))->with('confirmacion', 'registrado');
        } catch(Exception $e) {
            return redirect()->route('listaConsultas', $request->post('rdbms_id'))->with('errorMsj', 'Error al intentar guardar la conexión. Si el problema persiste contacte al admon.');
        }
    }

    public function testConsulta(Request $request) {
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
        $conexion = New rdbms;
        $resultadoCon = $conexion->listaById($data['rdbms_id']);
        $resultadoCon = $resultadoCon[0];
        $tipoDB = $this->rdbmsType($resultadoCon['noType']);
                
        if ($tipoDB['tipo'] != 'failed' && $tipoDB['tipo'] != 'oracle' && $tipoDB['tipo'] != 'ODBC') {
            try {
                try {
                    config(['database.connections.dinamyc' => [
                        'driver' => $tipoDB['tipo'],
                        'host' => $resultadoCon['ip_address'],
                        'port' => $resultadoCon['port'],
                        'database' => $resultadoCon['db_name'],
                        'username' => $resultadoCon['db_user'],
                        'password' => $resultadoCon['db_psw'],
                        'encriptado' => false
                    ]]);
                    $con = DB::connection('dinamyc')->select(DB::raw($data['qry_read']));
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
            $dataCon = $resultadoCon['ip_address'].":".$resultadoCon['port']."/".$resultadoCon['db_name'];
            if($con = @oci_connect($resultadoCon['db_user'], $resultadoCon['db_psw'], $dataCon)) {
                if (oci_error($con)) {
                    return false;
                } else {
                    $stid = oci_parse($con,$data['qry_read']);
                    if ($stid) {
                        if ($r = @oci_execute($stid)) {
                            return 1;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        } else if($tipoDB['tipo'] != 'failed' && $tipoDB['tipo'] == 'ODBC') {
            echo 'odbc';
            exit();
        }
        
        return false;
    }

    public function rdbmsType($tipo) {
        $dataDB = array();
        switch ($tipo) {
            case 1:
                $dataDB['tipo'] = 'oracle';
                break;
            case 2:
                $dataDB['tipo'] = 'sqlsrv';
                break;
            case 4:
                $dataDB['tipo'] = 'mysql';
                break;
            default:
                if($tipo != 3 && $tipo != 5) {
                    $dataDB['tipo'] = 'failed';
                } else {
                    $dataDB['tipo'] = 'ODBC';
                }
                break;
        }
        return $dataDB;
    }

    public function update(Request $request, $id)
    {
        if($this->validateBlackList($request->post('qry_read')) === false) {
            return redirect()->route('crear_consultas')->with('errorMsj', 'Ha surgido un error al querer ingresar la consulta. Verifique los datos.');
        }
        
        $request->validate([
            "application_id" => ["required", "integer"],
            "select_in" => ["required", "integer"],
            "qry_read" => ["required"]
        ]);

        $sql = '
            UPDATE rdbms_qrys SET
                application_id = '.$request->post('application_id').',
                rdbms_id = '.$request->post('rdbms_id').',
                qry_read = "'.$request->post('qry_read').'",
                select_in = '.$request->post('select_in').'
            WHERE id = '.$id.'
        ';
        try {
            DB::select(DB::raw($sql));

            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha realizado la modificación de una consulta',
                    'tipo' => 'modificacion',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return redirect()->route('listaConsultas', $request->post('rdbms_id'))->with('edicion', 'registrado');
        } catch(Exception $e) {
            return redirect()->route('listaConsultas', $request->post('rdbms_id'))->with('errorMsj', 'Error al intentar guardar la conexión. Si el problema persiste contacte al admon.');
        }
    }
}
