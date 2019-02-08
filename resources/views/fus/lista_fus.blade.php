@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>
<input type="hidden" id="modulo"  value="fuses"/>
<input type="hidden" id="formAjax" value="1" />
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de Fuses
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a href="{{ route('listar') }}" class="btn btn-warning" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>No. FUS</th>
                                        <th>Nombre / #</th>
                                        <th>Cargo</th>
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
</div>
@endsection
@push('scripts')
<script>
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

    $(document).on("click", "#sustituir", function(){
        var nom = $(this).attr("data-nom");
        var num = $(this).attr("data-num");
        var tipo = $(this).attr("data-tipo");
        var idFus = $(this).attr("data-id-fus");
        var idRespActual = $(this).attr("data-id-responsable");

        formEditar(idFus, nom, num, tipo, idRespActual);
    });

    $(document).on("click", "#guardarSustitucion", function(){
        guardarAltaEditar();
    });
    
    function formEditar(idFus, nom, num, tipo, idRespActual) {
        Swal({
            title: 'SUSTITUCIÓN DE AUTORIZADOR/RESPONSABLE',
            // type: 'info',
            html:
            '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    '<input type="hidden" name="tipo" id="tipo" value="'+tipo+'">'+
                    '<input type="hidden" name="idRespActual" id="idRespActual" value="'+idRespActual+'">'+
                    '<input type="hidden" name="idfus" id="idfus" value="'+idFus+'">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<label for="nombre" class="col-lg-12 col-form-label text-left txt-bold">Nombre Completo</label>'+
                            '<input id="nombre" type="text" class="form-control autocomplete_txt" data-type="nom_auto" name="nombre" required value="'+nom+'">'+

                            '<span id="errmsj_nombre" class="error-msj" role="alert">'+
                                '<strong>El campo Nombre es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-6">'+
                            '<label for="numEmpleado" class="col-lg-12 col-form-label text-left txt-bold">Número de Empleado</label>'+
                            '<input id="numEmpleado" type="text" class="form-control autocomplete_txt" data-type="num_auto" name="numEmpleado" required value="'+num+'">'+

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

    @if (session('confirmacion'))
        swal(
            'FUS registrado',
            'La operación se ha realizado con éxito.',
            'success'
        )
    @endif
    
    var table = $('#fus-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route("fus.data", $id) !!}',
        columns: [
            {data: 'fus', name: 'fus'},
            {data: 'datos_fus', name: 'datos_fus'},
            {data: 'tipo', name: 'tipo'},
            {data: 'descripcion', name: 'descripcion'},
            {
                render: function (data, type, row) {
                    var html = '';
                    html = '<div class="row">'+
                                '<div class="col-lg-12 text-center">'+
                                    '<button class="btn btn-primary" id="sustituir" data-nom="'+row.nombre+'" data-num="'+row.numero+'" data-tipo="'+row.tipoNum+'" data-id-fus="'+row.idfus+'" data-id-responsable="'+row.idRespActual+'">'+
                                        'Cambio de Auto./Resp. <i class="fas fa-user-friends"></i>'+
                                    '</button>'+
                                '</div>'+
                            '</div>';

                    return html;
                }
            },
        ]
    });

    var validaRequired = 0;

    function guardarAltaEditar() {
        var modulo = $("#modulo").val();
        var formAjax = $("#formAjax").val();

        var tipo = $("#tipo").val();
        var idfus = $("#idfus").val();
        var nom = $("#nombre").val();
        var num = $("#numEmpleado").val();
        var idRespActual = $("#idRespActual").val();

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
                data: { formAjax: formAjax, modulo: modulo, nombre: nom, numEmpleado: num, tipo: tipo, idfus: idfus, idRespActual: idRespActual},
                dataType: 'JSON',
                url: '{{ route("sustitucionindividual") }}',
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
</script>
@endpush