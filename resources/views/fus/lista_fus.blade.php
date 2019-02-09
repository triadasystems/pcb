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
                                        <th>No.&nbsp;FUS</th>
                                        <th>FUS&nbsp;Físico</th>
                                        <th>Autorizador&nbsp;#</th>
                                        <th>Responsable&nbsp;#</th>
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

    $(document).on('focus','.autocomplete_txt_2', function() {
        type = $(this).data('type');

        if(type =='num_resp')autoType='numero';
        if(type =='nom_resp')autoType='nombre';
        
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

                        response(array);
                    }
                });
            },
            select: function( event, ui) {
                var data = ui.item.data;
                if (data != "fail") {
                    $('#nombreR').val(data.nombre);
                    $('#numEmpleadoR').val(data.numero);  
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
        });
    });

    $(document).on("click", "#sustituir", function(){
        var auto = $(this).attr("data-auto");
        var resp = $(this).attr("data-resp");
        
        var idFus = $(this).attr("data-id-fus");

        formEditar(idFus, auto, resp);
    });

    $(document).on("click", "#guardarSustitucion", function(){
        guardarAltaEditar();
    });
    
    function formEditar(idFus, auto, resp) {
        var autorizador = auto.split(" - ");
        var responsable = resp.split(" - ");
        
        Swal({
            title: 'SUSTITUCIÓN DE AUTORIZADOR/RESPONSABLE',
            // type: 'info',
            html:
            '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    '<input type="hidden" name="autoActual" id="autoActual" value="'+auto+'">'+
                    '<input type="hidden" name="respActual" id="respActual" value="'+resp+'">'+
                    '<input type="hidden" name="idfus" id="idfus" value="'+idFus+'">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<label for="nombre" class="col-lg-12 col-form-label text-left txt-bold">Nombre Autorizador</label>'+
                            '<input id="nombre" type="text" class="form-control autocomplete_txt" data-type="nom_auto" name="nombre" required value="'+autorizador[0]+'">'+

                            '<span id="errmsj_nombre" class="error-msj" role="alert">'+
                                '<strong>El campo Nombre es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-6">'+
                            '<label for="numEmpleado" class="col-lg-12 col-form-label text-left txt-bold"># Emp. Autorizador</label>'+
                            '<input id="numEmpleado" type="text" class="form-control autocomplete_txt" data-type="num_auto" name="numEmpleado" required value="'+autorizador[1]+'">'+

                            '<span id="errmsj_numEmpleado" class="error-msj" role="alert">'+
                                '<strong>El campo número de empleado es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<label for="nombreR" class="col-lg-12 col-form-label text-left txt-bold">Nombre Responsable</label>'+
                            '<input id="nombreR" type="text" class="form-control autocomplete_txt_2" data-type="nom_resp" name="nombreR" required value="'+responsable[0]+'">'+

                            '<span id="errmsj_nombreR" class="error-msj" role="alert">'+
                                '<strong>El campo Nombre es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-6">'+
                            '<label for="numEmpleadoR" class="col-lg-12 col-form-label text-left txt-bold"># Emp. Responsable</label>'+
                            '<input id="numEmpleadoR" type="text" class="form-control autocomplete_txt_2" data-type="num_resp" name="numEmpleadoR" required value="'+responsable[1]+'">'+

                            '<span id="errmsj_numEmpleadoR" class="error-msj" role="alert">'+
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
            {data: 'fus_fisico', name: 'fus_fisico'},
            {data: 'autorizador', name: 'autorizador'},
            {data: 'responsable', name: 'responsable'},
            {data: 'descripcion', name: 'descripcion'},
            {
                render: function (data, type, row) {
                    var html = '';
                    html = '<div class="row">'+
                                '<div class="col-lg-12 text-center">'+
                                    '<button class="btn btn-primary" id="sustituir" data-auto="'+row.autorizador+'" data-resp="'+row.responsable+'" data-id-fus="'+row.idfus+'">'+
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

        var idfus = $("#idfus").val();
        var autoA = $("#autoActual").val();
        var respA = $("#respActual").val();
        
        var aut = $("#nombre").val();
        var autNo = $("#numEmpleado").val();
        var resp = $("#nombreR").val();
        var respNo = $("#numEmpleadoR").val();

        if (aut == null || aut == "") {
            mostrarError("errmsj_nombre");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_nombre");
            validaRequired = validaRequired+1;
        }

        if (autNo == null || autNo == "") {
            mostrarError("errmsj_numEmpleadoR");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_numEmpleadoR");
            validaRequired = validaRequired+1;
        }

        if (resp == null || resp == "") {
            mostrarError("errmsj_nombreR");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_nombreR");
            validaRequired = validaRequired+1;
        }

        if (respNo == null || respNo == "") {
            mostrarError("errmsj_numEmpleadoR");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_numEmpleadoR");
            validaRequired = validaRequired+1;
        }

        if (validaRequired == 4) {
            ocultarError("errmsj_nombre");
            ocultarError("errmsj_numEmpleado");
            ocultarError("errmsj_nombreR");
            ocultarError("errmsj_numEmpleadoR");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var ajax = $.ajax({
                type: 'PUT',
                data: { formAjax: formAjax, modulo: modulo, nombre: aut, numEmpleado: autNo, nombreR: resp, numEmpleadoR: respNo, idfus: idfus, autoA: autoA, respA: respA},
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

                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.nombreR !== undefined && response.responseJSON.errors.nombreR[0] != "") {
                    $("#errmsj_nombreR").html("<strong>"+response.responseJSON.errors.nombreR[0]+"</strong>");
                    mostrarError("errmsj_nombreR");
                }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.numEmpleadoR !== undefined && response.responseJSON.errors.numEmpleadoR[0] != "") {
                    $("#errmsj_numEmpleadoR").html("<strong>"+response.responseJSON.errors.numEmpleadoR[0]+"</strong>");
                    mostrarError("errmsj_numEmpleadoR");
                }
            });
        }
        
        validaRequired = 0;
    }
</script>
@endpush