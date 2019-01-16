<?php
namespace App\Http\Controllers;

use App\mailSendModel;
use App\Mail\DemoEmail;
use App\Mail\bajasTerceros;
use Illuminate\Support\Facades\Mail;
use App\Comparelaboraremove;
use Illuminate\Http\Request;
use App\Http\Controllers\reportesController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Validator;

class sendMailController extends Controller
{
    public $ip_address_client;

    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
    }

    public function conciliacion()
    {
        $data=mailSendModel::select('correo')->where("automatizacion", "=", 1)->get()->toJson();
        dd($data);
    }
    public function bajas()
    {
        $data=mailSendModel::select('correo')->where("bajas","=",1)->get()->toJson();
        dd($data);
    }
    public function sendMail($option)
    {
        if(permisosSendMailsAjax() !== false) {
            $tipoNombre = '';

            $errores = array();
            $reporte= new reportesController;
            if ($option==1)
            {
                $tipoNombre = 'Conciliación';

                $data=$reporte->reporteAutomatizacionMail();
                $datos=mailSendModel::select('correo')->where("automatizacion", "=", 1)->get()->toArray();
                foreach($datos as $key)
                {   
                    $objDemo = new \stdClass();
                    $objDemo->demo_one = $data;
                    $objDemo->demo_two = $option;
                    $objDemo->sender = 'SYSADMIN';
                    $correo=Validator::make($key, ['correo' => 'regex:/^.+@(.+\..+)$/']);
                    $mail = Mail::to(array($key["correo"]));
                    if ($correo->fails() === true) 
                    {
                        $errores[$key["correo"]] ='Falló al enviar';
                    } 
                    else 
                    { 
                        $mail->send(new DemoEmail($objDemo, $option));
                        $errores[$key["correo"]]='Enviado';   
                    }
                }
            }
            else if($option==2)
            {
                $tipoNombre = 'Bajas';
                $data=$reporte->reporteBajasMail();
                $terceros=new Comparelaboraremove;
                $b_terceros=$terceros->v_bajas(); 
                $datos=mailSendModel::select('correo')->where("bajas","=",1)->get()->toArray();
                foreach($datos as $key)
                {   
                    $objDemo = new \stdClass();
                    $objDemo->demo_one = $data;
                    $objDemo->demo_two = $option;
                    $objDemo->sender = 'SYSADMIN';
                    $correo=Validator::make($key, ['correo' => 'regex:/^.+@(.+\..+)$/']);
                    $mail = Mail::to(array($key["correo"]));

                    if ($correo->fails() === true) 
                    {
                        $errores[$key["correo"]] ='Falló al enviar';
                    } 
                    else 
                    { 
                        $mail->send(new DemoEmail($objDemo, $option));
                        $errores[$key["correo"]]='Enviado';   
                    }
                }
                if ($b_terceros!="" || $b_terceros>0)
                {
                    $correo=mailSendModel::select('correo')->where("tcs_terceros_baja_auth_resp","=",1)->get()->toArray();
                    foreach ($correo as $value)
                    {                    
                        $objMail= new \stdClass();
                        $objMail->data=$b_terceros;
                        $objMail->sender='SYSADMIN';
                        $correo=Validator::make($value, ['correo' => 'regex:/^.+@(.+\..+)$/']);
                        $mail = Mail::to(array($value["correo"]));
                        if ($correo->fails() === true) 
                        {
                            $errores[$value["correo"]] ='Falló al enviar correo de terceros';
                        }
                        else 
                        { 
                            $mail->send(new bajasTerceros($objMail));
                            $errores[$value["correo"]]='Enviado';   
                        }
                    }
                }
            }

            DB::table('logbook_movements')->insert([
                [
                    'ip_address' => $this->ip_address_client, 
                    'description' => 'Se ha enviado un e-mail con el reporte de '.$tipoNombre,
                    'tipo' => 'sendMail',
                    'id_user' => Auth::user()->id
                ]
            ]);

            return response()->json([$errores]);
        } else {
            echo "middleSendMails";
            exit();
        }
    }
}
