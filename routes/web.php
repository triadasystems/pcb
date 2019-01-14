<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Terceros EVP
Route::group(['prefix' =>'motivosbajas', 'middleware' => 'userProfileInactivo'], function() {
    Route::get('/lista', 'MotivosbajasController@index')->name('motivosbajas')->middleware('reading');
    Route::get('/catmotivosbajas','MotivosbajasController@data')->name('motivosbajas.data');

    Route::post('/store', 'MotivosbajasController@store')->name('altamotivobaja')->middleware('writing');
    Route::put('/update', 'MotivosbajasController@update')->name('editarmotivobaja');

    Route::get('/permisosmotivosbajas','MotivosbajasController@permisosMotivosBajas')->name('permisosMotivosBajas');
    Route::put('/cambiostatusmotivobaja','MotivosbajasController@cambioStatus')->name('editarstatusmotivobaja');
});
Route::group(['prefix' =>'proveedores', 'middleware' => 'userProfileInactivo'], function() {
    Route::get('/lista', 'ProveedoresController@index')->name('proveedores')->middleware('reading');
    Route::get('/catproveedores','ProveedoresController@data')->name('proveedores.data');

    Route::post('/store', 'ProveedoresController@store')->name('altaproveedores')->middleware('writing');
    Route::put('/update', 'ProveedoresController@update')->name('editarproveedores');

    Route::get('/permisosproveedores','ProveedoresController@permisosProveedores')->name('permisosproveedores');
    Route::put('/cambiostatusproveedor','ProveedoresController@cambioStatus')->name('editarstatusproveedores');
});
Route::group(['prefix' =>'mesascontrol', 'middleware' => 'userProfileInactivo'], function() {
    Route::get('/lista', 'MesacontrolController@index')->name('mesacontrol')->middleware('reading');
    Route::get('/catmesascontrol','MesacontrolController@data')->name('mesacontrol.data');

    Route::post('/store', 'MesacontrolController@store')->name('altamesacontrol')->middleware('writing');
    Route::put('/update', 'MesacontrolController@update')->name('editarmesacontrol');

    Route::get('/permisosmesascontrol','MesacontrolController@permisosMesasControl')->name('permisosmesacontrol');
    Route::put('/cambiostatusmesacontrol','MesacontrolController@cambioStatus')->name('editarstatusmesacontrol');
});
// Fin de Terceros EVP

Route::resource('mails', "MailController", ['middleware' => ['userProfileInactivo']]);
Route::get('consultatodomails', 'consultasController@consulta_todo_mails')->name('consultatodomails.data');

Route::resource('modulos', "ModuloController", ['middleware' => ['userProfileInactivo']]);
Route::get('consultatodomodulos', 'consultasController@consulta_todo_modulos')->name('consultatodomodulos.data');

Route::resource('permisos', "PermisoController", ['middleware' => ['userProfileInactivo']]);
Route::get('consultatodopermisos','consultasController@consulta_todo_permisos')->name('consultatodopermisos.data');
Route::post('/permisos/desactivar','PermisoController@desactivar')->name('desactivarpermisos');

Route::resource('relacionpm', 'RelacionpmController', ['middleware' => ['userProfileInactivo']]);
Route::get('consultatodorelacionpm', 'consultasController@consulta_todo_relacionpm')->name('consultatodorelacionpm.data');

Route::get('/home', 'HomeController@index')->name('home')->middleware('userProfileInactivo');
Route::get('/homeajax', 'HomeController@homeajax')->name('homeajax')->middleware('userProfileInactivo');

// Permisos
Route::get('/ldapuser', 'LDAPController@validateUser')->name('ldapuser');
Route::get('/validatemodules/{id}/{modulo}', 'accesosController@validateModules')->name('validatemodules');
Route::get('/validatePermits/{id}', 'accesosController@validatePermits')->name('validatePermits');

Route::group(['prefix' => 'conciliacion', 'middleware' => 'userProfileInactivo'], function(){
    Route::get('/conciliacion', 'migracionController@index')->name('conciliacion');
    Route::get('/reporteBajas','reportesController@reporteBajas')->name('reporteBajas.data');
    Route::get('/reporteAutomatizacion','reportesController@reporteAutomatizacion')->name('reporteAutomatizacion.data');
    
    Route::get('/reporteBajasMail','reportesController@reporteBajasMail')->name('reporteBajasMail.data');
    Route::get('/reporteAutomatizacionMail','reportesController@reporteAutomatizacionMail')->name('reporteAutomatizacionMail.data');

    Route::get('/obtenerTotalConexiones/{tipo}', 'migracionController@obtenerTotalConexiones')->name('totalregistros');
    Route::get('/obtenerConsecutivo', 'migracionController@obtenerConsecutivo')->name('consecutivo');
    // Route::get('/ejecutarMigracion/{tipo}', 'migracionController@ejecutarMigracion')->name('migracion');
    Route::post('/ejecutarMigracion', 'migracionController@ejecutarMigracion')->name('migracion');
    Route::post('/log', 'migracionController@log')->name('logGuardar');
});

Route::group(['prefix' => 'usuarios', 'middleware' => 'userProfileInactivo'], function(){
    Route::get('/lista','usuariosController@getIndex')->name('listausuarios')->middleware('reading');
    Route::post('/cambiarrol','usuariosController@cambiarrol')->name('cambiarrol');
    Route::post('/desactivar','usuariosController@desactivar')->name('desactivarusuarios');
    Route::get('/editar','usuariosController@edit')->name('edit');
    Route::get('/anyData','usuariosController@anyData')->name('datatables.data');
});

Route::group(['prefix' => 'conexiones', 'middleware' => 'userProfileInactivo'], function(){
    Route::get('/lista','ConexionesController@index')->name('listaConexiones')->middleware('reading');
    Route::get('/data','ConexionesController@data')->name('datatablesCon.data');

    Route::post('/desactivar','ConexionesController@desactivar')->name('desactivarconexiones');

    Route::post('/store', 'ConexionesController@store')->name('store_conexiones');
    Route::get('/create', 'ConexionesController@create')->name('crear_conexiones')->middleware('writing');

    Route::get('/edit/{id}','ConexionesController@edit')->name('editarCon')->middleware('upgrade');
    Route::put('/update/{id}','ConexionesController@update')->name('updateCon');

    Route::post('/testConexion', 'ConexionesController@testConexion')->name('testConexion');

    Route::get('/consultas/lista/{id}','QuerysController@index')->name('listaConsultas')->middleware('reading');
    Route::get('/consultas/data/{id}','QuerysController@data')->name('datatablesConsultas.data');
    Route::post('/consultas/desactivar','QuerysController@desactivar')->name('desactivarconsultas');
    Route::post('/consultas/store', 'QuerysController@store')->name('store_consultas');
    Route::get('/consultas/create/{id}', 'QuerysController@create')->name('crear_consultas')->middleware('writing');
    Route::post('/consultas/testConsulta', 'QuerysController@testConsulta')->name('testConsulta');

    Route::get('/consultas/edit/{id}','QuerysController@edit')->name('editarConsulta')->middleware('upgrade');
    Route::put('/consultas/update/{id}','QuerysController@update')->name('updateConsulta');
});

Route::group(['prefix' => 'perfiles', 'middleware' => 'userProfileInactivo'], function(){
    Route::get('/index', 'ProfilesController@index')->name('index_perfil')->middleware('reading');
    Route::post('/store', 'ProfilesController@store')->name('store_perfil');
    Route::get('/create', 'ProfilesController@create')->name('crear_perfil')->middleware('writing');
    Route::post('/desactivar','ProfilesController@desactivar')->name('desactivarprofiles');
    Route::get('/consulta','ProfilesController@consulta')->name('consulta.data');
    Route::get('/edit/{id}','ProfilesController@edit')->name('editar')->middleware('upgrade');
    Route::put('/update/{id}','ProfilesController@update')->name('update');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});

Route::group(['prefix' =>'mailsend', 'middleware' => 'userProfileInactivo'], function(){
    Route::get('/ratificacion', 'sendMailController@conciliacion')->name('ratificacion');
    Route::get('/bajas','sendMailController@bajas')->name('bajas');
    Route::get('/send/{tipo}', 'sendMailController@sendMail')->name('send');
});

Route::get('/encrypt/viewEncrypt', 'EncryptController@viewEncrypt')->name('viewEncrypt');
Route::get('/encrypt/desEncrypt', 'EncryptController@desEncrypt')->name('desEncrypt');

//terceros
Route::group(['prefix' => 'terceros','middleware' => 'userProfileInactivo'], function()
{
    Route::get('/listar', 'tercerosController@index')->name('listar');
    Route::get('/alta', 'tercerosController@create')->name('terceros.alta');
    Route::post('/insertar', 'tercerosController@insertar')->name('insertar');
    Route::get('/anyData','tercerosController@anyData')->name('terceros.data');
    Route::get('/consecutivo', 'tercerosController@generar_consecutivo')->name('terceros.consecutivo');
    Route::get('autocomplete', ['uses'=>'tercerosController@autocomplete'])->name('terceros.autocomplete');
});

// configuraciones
Route::group(['prefix'=>'configuracion','middleware'=> 'userProfileInactivo'], function()
{
    Route::get('/index','settingsController@index')->name('settings');
    Route::post('/upsub','settingsController@updatesub')->name('actualizaSub');
});

Route::group(['prefix' =>'motivosbajas', 'middleware' => 'userProfileInactivo'], function() {
    Route::get('/lista', 'MotivosbajasController@index')->name('motivosbajas')->middleware('reading');
    Route::get('/catmotivosbajas','MotivosbajasController@data')->name('motivosbajas.data');

    Route::post('/store', 'MotivosbajasController@store')->name('altamotivobaja')->middleware('writing');
    Route::put('/update', 'MotivosbajasController@update')->name('editarmotivobaja');

    Route::get('/permisosmotivosbajas','MotivosbajasController@permisosMotivosBajas')->name('permisosMotivosBajas');
    Route::put('/cambiostatusmotivobaja','MotivosbajasController@cambioStatus')->name('editarstatusmotivobaja');
});

Route::group(['prefix' =>'reportes', 'middleware' => 'userProfileInactivo'], function() {
    Route::get('/bajasdiarias', 'TcsreportesController@reporteBajasDiarias')->name('bajasdiarias')->middleware('reading');
    Route::get('/bajasdiariasdata','TcsreportesController@reporteBajasDiariasData')->name('bajasdiarias.data');

    Route::get('/tercerosactivos', 'TcsreportesController@reporteActivos')->name('tercerosactivos')->middleware('writing');
    Route::get('/tercerosactivosdata', 'TcsreportesController@reporteActivosData')->name('tercerosactivos.data');

    Route::get('/trazabilidad','TcsreportesController@reporteTrazabilidad')->name('trazabilidad')->middleware('writing');
    Route::get('/trazabilidaddata','TcsreportesController@reporteTrazabilidadData')->name('trazabilidad.data');
});