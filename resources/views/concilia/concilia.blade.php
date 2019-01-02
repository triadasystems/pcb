@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="conciliacion" />
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    PCB
                </div>
                <div class="panel-pbc-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-left">
                            <div class="row">
                                <div id="pestana-auto" class="offset-lg-1 col-lg-2 text-center pestana-activa" style="font-size: 18px;cursor:pointer;">
                                    Conciliación
                                </div>
                                <div id="pestana-bajas" class="col-lg-2 text-center pestana-inactiva" style="font-size: 18px;cursor:pointer;">
                                    Bajas
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <input type="hidden" name="url_login" id="url_login" value="{{ route('login') }}"/>
                            <input type="hidden" name="tipo-au-ba" id="tipo-au-ba" value="1"/>
                            <input type="hidden" name="value-sendmail" id="value-sendmail" value="1"/>
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-primary" id="btnSendemail">Enviar Resultados <i class="fas fa-share-square"></i></button>
                            <button class="btn btn-primary validatePermisos writing" id="btnEjecutar">Ejecutar <i class="fas fa-caret-right"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="resultado-concilia" class="table-responsive">
                                <p>
                                    Los siguientes empleados en Aplicaciones no se encontraron en Labora:
                                </p>
                                <table class="table table-bordered" id="conciliacion-table">
                                    <thead>
                                        <tr>
                                            <th>Número de Empleado</th>
                                            <th>Username</th>
                                            <th>Nombre Completo</th>
                                            <th>Aplicación</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="resultado-bajas" class="oculta-conciliacion-bajas">
                                <p>
                                    Se encontraron estos empleados en aplicaciones para dar de baja:
                                </p>
                                <table class="table table-bordered" id="bajas-table">
                                    <thead>
                                        <tr>
                                            <th>Número de Empleado</th>
                                            <th>Username</th>
                                            <th>Nombre Completo</th>
                                            <th>Aplicación</th>
                                            <th>Motivo de baja</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/front_service.js') }}"></script>
<script>
var tableAuto;
var tableBajas;
$(document).ready(function(){
    $("#btnSendemail").on("click", function(){
        var a = $("#value-sendmail").val();
        var url = "{{ URL::to('/mailsend/send') }}/"+a;
                               
        swal({
            title: 'Envió de reporte',
            text: "Tome en cuenta que se enviará el reporte generado por última vez",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Enviar'
        }).then((result) => {
            if (result.value) {
                mostrarLoadingMail();
                $.ajax({
                    type: 'GET',
                    url: url,
                    async: false
                }).done(function(response){
                    if(response == "middleSendMails") {
                        ocultarLoadingMail();
                        window.location.href = "{{ route('homeajax') }}";
                    } else {
                        var resultado = "";

                        $.each(response[0], function( ind, val ) {
                            resultado += '<tr><td>'+ind+'</td><td>'+val+'</td></tr>';
                        });
                        ocultarLoadingMail();
                        swal({
                            title: '<strong>Resultado de la ejecución</strong>',
                            type: 'info',
                            html: '<div style="overflow-y:auto;height: 350px;"><table border="1" style="width:100%;"><thead><tr><th>E-mails</th><th>Resultado</th></tr></thead><tbody>'+resultado+'</tbody></table></div>',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                            confirmButtonText: 'Confirmar',
                            allowOutsideClick: false,
                        });
                    }
                });
            }
        })
    });
    $("#pestana-bajas").click(function(){
        $("#resultado-bajas").removeClass("oculta-conciliacion-bajas");
        $("#resultado-concilia").addClass("oculta-conciliacion-bajas");
        
        $("#pestana-bajas").removeClass("pestana-inactiva");
        $("#pestana-bajas").addClass("pestana-activa");

        $("#pestana-auto").removeClass("pestana-activa");
        $("#pestana-auto").addClass("pestana-inactiva");

        tableBajas.ajax.reload();

        $("#tipo-au-ba").val(2);
        $("#value-sendmail").val(2);
    });
    $("#pestana-auto").click(function(){

        $("#resultado-concilia").removeClass("oculta-conciliacion-bajas");
        $("#resultado-bajas").addClass("oculta-conciliacion-bajas");

        $("#pestana-bajas").removeClass("pestana-activa");
        $("#pestana-bajas").addClass("pestana-inactiva");

        $("#pestana-auto").removeClass("pestana-inactiva");
        $("#pestana-auto").addClass("pestana-activa");
        
        tableAuto.ajax.reload();
        
        $("#tipo-au-ba").val(1);
        $("#value-sendmail").val(1);
    });
    
    ////////////////////////////////////////////////////
    
    tableAuto = $('#conciliacion-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}",
            buttons: {
                copyTitle: 'Tabla copiada',
                copySuccess: {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('reporteAutomatizacion.data') !!}',
        columns: [
            { data: 'idemp', name: 'idemp' },
            { data: 'nombre', name: 'nombre' },
            { data: 'apellidos', name: 'apellidos' },
            { data: 'alias', name: 'alias' }
        ],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copiar',
            },
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

    tableBajas = $('#bajas-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}",
            buttons: {
                copyTitle: 'Tabla copiada',
                copySuccess: {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('reporteBajas.data') !!}',
        columns: [
            { data: 'idemp', name: 'idemp' },
            { data: 'nombre', name: 'nombre' },
            { data: 'apellidos', name: 'apellidos' },
            { data: 'alias', name: 'alias' },
            { data: 'motivo_baja', name: 'motivo_baja' }
        ],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copiar',
            },
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
@endpush