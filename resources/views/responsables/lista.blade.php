@extends('layouts.app')

@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>
<input type="hidden" id="modulo" value="tcssustitucionmasiva" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Sustitución de Autorizadores/Responsables
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="responsables-table">
                            <thead>
                                <tr>
                                    <th># de Empleado</th>
                                    <th>Nombre del Autorizador/Responsable</th>
                                    <th>Tipo</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Autocomplete
    $(document).on('focus','.autocomplete_txt', function() {
        type = $(this).data('type');

        if(type =='num_auto')autoType='numero';
        if(type =='nom_auto')autoType='nombre';
        
        $(this).autocomplete({
            minLength:0,
            source: function(request, response) {
                $.ajax({
                    url:"{{ route('sustitucion.autocomplete') }}",
                    dataType: "json",
                    data:{ term: request.term, type: type },
                    success: function(data) {
                        var array = $.map(data, function(item){
                            var response = "";
                            if(item[autoType] !== undefined && item[autoType] !== undefined) {
                                response = {
                                    label: item[autoType],
                                    value: item[autoType],
                                    data: item
                                }
                            } else if(item != "") {
                                response = {
                                    label: item,
                                    value: item,
                                    data: "fail"
                                };
                            }
                            return response;
                        });
                        console.log(array);
                        response(array);
                    }
                });
            },
            select: function( event, ui) {
                var data = ui.item.data;
                if (data != "fail") {
                    $('#nombre').val(data.nombre);
                    $('#numEmpleado').val(data.numero);
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
        });
    });

    $(document).ready(function(){
        $(document).on("click", "#sustituir", function(){
            var numEmpleado = $(this).attr("data-num-empleado");
            var nombre = $(this).attr("data-nombre");
            var tipo = $(this).attr("data-tipo");

            formEditar(numEmpleado, tipo, nombre);
        });

        $(document).on("click", "#guardarSustitucion", function(){
            guardarAltaEditar("editar");
        });
        
        function formEditar(numEmpleado, tipo, nombre) {
            Swal({
                title: 'SUSTITUCIÓN DE AUTORIZADOR/RESPONSABLE',
                // type: 'info',
                html:
                '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                        '<input type="hidden" name="numEmpleadoActual" id="numEmpleadoActual" value="'+numEmpleado+'">'+
                        '<input type="hidden" name="tipo" id="tipo" value="'+tipo+'">'+
                        '<div class="form-group row">'+
                            '<div class="col-md-6">'+
                                '<label for="nombre" class="col-lg-12 col-form-label text-left txt-bold">Nombre Completo</label>'+
                                '<input id="nombre" type="text" class="form-control autocomplete_txt" data-type="nom_auto" name="nombre" required value="'+nombre+'">'+

                                '<span id="errmsj_nombre" class="error-msj" role="alert">'+
                                    '<strong>El campo Nombre es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<label for="numEmpleado" class="col-lg-12 col-form-label text-left txt-bold">Número de Empleado</label>'+
                                '<input id="numEmpleado" type="text" class="form-control" data-type="num_auto" name="numEmpleado" required value="'+numEmpleado+'">'+

                                '<span id="errmsj_numEmpleado" class="error-msj" role="alert">'+
                                    '<strong>El campo número de empleado es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-12 text-right">'+
                            '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                            '<input class="btn btn-primary" id="guardarSustitucion" type="button" value="Sustituir">'+
                        '</div>'+
                    '</form>'+
                '</div>',
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: false,
                focusConfirm: false,
                confirmButtonText: 'Aplicar Baja',
                confirmButtonAriaLabel: 'Aplicar Baja',
                cancelButtonText: 'Cancelar Baja',
                allowOutsideClick: false,
            });   
        }
        var table = $('#responsables-table').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}",
                buttons: {
                    copyTitle: 'Tabla copiada',
                    copySuccess: {
                        _: '%d líneas copiadas',
                        1: '1 línea copiada'
                    }
                }
                // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            processing: true,
            serverSide: true,
            
            ajax: '{!! route("sustitucionrespauth.data") !!}',
            columns: [
                { data: 'numero', name: 'numero' },
                { data: 'nombre', name: 'nombre' },
                {
                    render: function (data, type, row) {
                        var tipo = row.tipo;
                        var cargo = '';

                        switch (tipo) {
                            case '1':
                                cargo = 'Autorizador';
                                break;
                            case '2':
                                cargo = 'Responsable';
                                break;
                            case '3':
                                cargo = 'Autorizador/Responsable';
                                break;
                        }
                        return cargo;
                    }
                },
                {
                    render: function (data, type, row) {
                        var html = '';
                        html = '<div class="row">'+
                                    '<div class="col-lg-12 text-center">'+
                                        '<button class="btn btn-primary" id="sustituir" data-nombre="'+row.nombre+'" data-tipo="'+row.tipo+'" data-num-empleado="'+row.numero+'">'+
                                            'Sustituir <i class="fas fa-user-friends"></i>'+
                                        '</button>'+
                                    '</div>'+
                                '</div>';

                        return html;
                    }
                }
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

        // NEL
        var validaRequired = 0;

        function guardarAltaEditar() {
            var tipo = $("#tipo").val();
            var nombre = $("#nombre").val();
            var numEmpleado = $("#numEmpleado").val();
            var numEmpleadoActual = $("#numEmpleadoActual").val();

            if (nombre == null || nombre == "") {
                mostrarError("errmsj_nombre");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_nombre");
                validaRequired = validaRequired+1;
            }
            if (numEmpleado == null || numEmpleado == "") {
                mostrarError("errmsj_numEmpleado");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_numEmpleado");
                validaRequired = validaRequired+1;
            }

            if (validaRequired == 2) {
                ocultarError("errmsj_nombre");
                ocultarError("errmsj_numEmpleado");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var ajax = $.ajax({
                    type: 'PUT',
                    data: { nombre: nombre, numEmpleado: numEmpleado, tipo: tipo, numEmpleadoActual: numEmpleadoActual },
                    dataType: 'JSON',
                    url: '{{ route("sustitucion") }}',
                    async: false,
                    beforeSend: function(){
                        console.log("Cargando");
                    },
                    complete: function(){
                        console.log("Listo");
                    }
                });
                                
                ajax.done(function(response){
                    if(response === true) {
                        table.ajax.reload();
                        swal(
                            'Sustitución',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response === false) {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    }
                }).fail(function(response) {
                    if (response.responseText !== undefined && response.responseText == "middleUpgrade") {
                        window.location.href = "{{ route('homeajax') }}";
                    }
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.nombre !== undefined && response.responseJSON.errors.nombre[0] != "") {
                        $("#errmsj_nombre").html("<strong>"+response.responseJSON.errors.nombre[0]+"</strong>");
                        mostrarError("errmsj_nombre");
                    }
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.numEmpleado !== undefined && response.responseJSON.errors.numEmpleado[0] != "") {
                        $("#errmsj_numEmpleado").html("<strong>"+response.responseJSON.errors.numEmpleado[0]+"</strong>");
                        mostrarError("errmsj_numEmpleado");
                    }
                });
            }
            
            validaRequired = 0;
        }
        // FIN
    });
</script>
@endpush