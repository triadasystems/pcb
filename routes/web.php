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
Route::group(['prefix' => 'terceros','middleware' => 'userProfileInactivo'], function(){
    Route::get('/listar', 'tercerosController@index')->name('listar');
    Route::get('/alta', 'tercerosController@create')->name('terceros.alta');
    Route::post('/insertar', 'tercerosController@insertar')->name('insertar');
    Route::get('/anyData','tercerosController@anyData')->name('terceros.data');
    //Route::get('/autocomplete', 'tercerosController@autocomplete')->name('terceros.autocomplete');
    //Route::post('/autocomplete', 'tercerosController@autocomplete')->name('terceros.autocomplete');
    Route::get('autocomplete', ['uses'=>'tercerosController@autocomplete'])->name('terceros.autocomplete');
});

