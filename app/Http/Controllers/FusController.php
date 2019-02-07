<?php
namespace App\Http\Controllers;

use App\Profileusers;
use App\LogBookMovements;
use App\autorizador_responsable;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FusController extends Controller
{
    public $ip_address_client;
    protected $requestProp;
    public function __construct()
    {
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
        $this->middleware('writing', ['only' => ['store']]);
        $this->middleware('upgrade', ['only' => ['update']]);
    }
    public function index($id)
    {
        $perfiles = Profileusers::select('id', 'profilename')->where('profilename', '!=', 'root')->get();

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de FUS',
            'tipo' => 'vista',
            'id_user' => Auth::user()->id
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        return view("fus.lista_fus")->with('id',$id);
    }
    public function anyData($id)
    {
        $querys = new autorizador_responsable;
        
        return Datatables::of($querys->listar($id))->make(true);
    }
}
?>