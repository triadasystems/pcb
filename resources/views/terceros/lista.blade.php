@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>
<input type="hidden" id="modulo" value="tcslistaactivos" />
<input type="hidden" id="formAjax" value="1" />
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de externos activos
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaTerceros">Alta de Externos Activos</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="terceros-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre | Gafete</th>
                                        <th>E-mail</th>
                                        <th>Autorizador #</th>
                                        <th>Responsable #</th>
                                        <th>Empresa</th>
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
    // Autocomplete
    $(document).on('focus','.autocomplete_txt, .autocomplete_txt2', function() {
        var type = $(this).data('type');
        var tipoResponsable = $(this).attr("data-responsable");

        if(type =='num_auto' || type =='num_res') {
            autoType='numero';
        }
        if(type =='nom_auto' || type =='nom_res') { 
            autoType='nombre';
        }
        
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
                    switch (tipoResponsable) {
                        case "auto":
                            $('#nomAuto').val(data.nombre);
                            $('#numauto').val(data.numero);    
                            break;
                        case "resp":
                            $('#nomResp').val(data.nombre);
                            $('#numResp').val(data.numero);
                            break;
                    }
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
        });
    });
    // FIN Auto complete
    $(document).ready(function() {          
        @if (session('confirmacion'))
            swal(
                'Tercero registrado',
                'La operación se ha realizado con éxito. El ID del tercero es: {{session("confirmacion")}}',
                'success'
            )
        @endif
        @if (session('error_alta'))
            swal(
                'Advertencia',
                'La operación no se ha llevado acabo ya que el limite de subfijo ha sido superado, favor de ponerse en contacto con el administrador del sistema',
                'warning'
            )
        @endif 
        @if (session('validacionCalculo'))
            swal(
                'Tercero registrado',
                'La operción no se puede realizar se ha llegado al limite de id de externos, favor de ponerse en contacto con el administrador.',
                'success'
            )
        @endif                         
        $('#altaTerceros').click(function()
        {
            var url = '{!!route('terceros.alta')!!}';
            $( location).attr("href",url);
        });
        
        var table = $('#terceros-table').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('terceros.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                {
                    render: function (data, type, row) {
                        var gafete = "S/N";
                        
                        if (row.gafete != null) {
                            gafete = row.gafete;
                        }
                        var nombre = row.name+' '+row.lastname1+' '+row.lastname2+' | '+gafete;
                        return nombre;
                    }
                },
                { data: 'correo', name: 'correo' },
                {
                    render: function (data, type, row) {
                        var autorizador = row.nom_autorizador+' | '+row.num_autorizador;
                        return autorizador;
                    }
                },
                {
                    render: function (data, type, row) {
                        var reponsable = row.nom_responsable+' | '+row.num_responsable;
                        return reponsable;
                    }
                },
                { data: 'empresa', name: 'empresa' },
                {
                    render: function (data, type, row) {
                        var html = '';
                        html = '<div class="row">'+
                                    '<div class="col-lg-5 text-center">'+
                                        '<button class="btn btn-primary" id="sustituir" data-nom-auto="'+row.nom_autorizador+'" data-num-auto="'+row.num_autorizador+'" data-nom-resp="'+row.nom_responsable+'" data-num-resp="'+row.num_responsable+'" data-id="'+row.ident+'">'+
                                            'Cambio de Auto./Resp. <i class="fas fa-user-friends"></i>'+
                                        '</button>'+
                                    '</div>'+
                                '</div>';

                        return html;
                    }
                },
            ]
        });

        $(document).on("click", "#sustituir", function(){
            var nomAuto = $(this).attr("data-nom-auto");
            var nomResp = $(this).attr("data-nom-resp");
            var numAuto = $(this).attr("data-num-auto");
            var numResp = $(this).attr("data-num-resp");

            var idTercero = $(this).attr("data-id");

            formEditar(idTercero, nomAuto, numAuto, nomResp, numResp);
        });

        $(document).on("click", "#guardarSustitucion", function(){
            guardarAltaEditar("editar");
        });
        
        function formEditar(idTercero, nomAuto, numAuto, nomResp, numResp) {
            Swal({
                title: 'CAMBIO DE AUTORIZADOR/RESPONSABLE',
                // type: 'info',
                html:
                '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                        '<input type="hidden" name="nomAutoActual" id="nomAutoActual" value="'+nomAuto+'">'+
                        '<input type="hidden" name="numAutoActual" id="numAutoActual" value="'+numAuto+'">'+
                        '<input type="hidden" name="nomRespActual" id="nomRespActual" value="'+nomResp+'">'+
                        '<input type="hidden" name="numRespActual" id="numRespActual" value="'+numResp+'">'+
                        '<input type="hidden" name="idTercero" id="idTercero" value="'+idTercero+'">'+
                        '<div class="form-group row">'+
                            '<h5><strong>Autorizador</strong></h5>'+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<div class="col-md-6">'+
                                '<label for="nomAuto" class="col-lg-12 col-form-label text-left txt-bold">Nombre Completo</label>'+
                                '<input id="nomAuto" type="text" class="form-control autocomplete_txt" data-type="nom_auto" data-responsable="auto" name="nomAuto" required autofocus value="'+nomAuto+'">'+

                                '<span id="errmsj_nomAuto" class="error-msj" role="alert">'+
                                    '<strong>El campo es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<label for="numAuto" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                                '<input id="numAuto" type="text" class="form-control autocomplete_txt" data-type="num_auto" data-responsable="auto" name="numAuto" required autofocus value="'+numAuto+'">'+

                                '<span id="errmsj_numAuto" class="error-msj" role="alert">'+
                                    '<strong>El campo es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<h5><strong>Responsable</strong></h5>'+
                        '</div>'+
                        '<div class="form-group row">'+
                            '<div class="col-md-6">'+
                                '<label for="nomResp" class="col-lg-12 col-form-label text-left txt-bold">Nombre Completo</label>'+
                                '<input id="nomResp" type="text" class="form-control autocomplete_txt2" data-type="nom_res" data-responsable="resp" name="nomResp" required autofocus value="'+nomResp+'">'+
                            
                                '<span id="errmsj_nomResp" class="error-msj" role="alert">'+
                                    '<strong>El campo es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<label for="numResp" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                                '<input id="numResp" type="text" class="form-control autocomplete_txt2" data-type="num_res" data-responsable="resp" name="numResp" required autofocus value="'+numResp+'">'+

                                '<span id="errmsj_numResp" class="error-msj" role="alert">'+
                                    '<strong>El campo es obligatorio</strong>'+
                                '</span>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-12 text-right">'+
                            '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                            '<input class="btn btn-primary" id="guardarSustitucion" type="button" value="Cambiar">'+
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
        // NEL
        var validaRequired = 0;

        function guardarAltaEditar() {
            var modulo = $("#modulo").val();
            var formAjax = $("#formAjax").val();

            var idTercero = $("#idTercero").val();
            var nomAuto = $("#nomAuto").val();
            var numAuto = $("#numAuto").val();
            var nomResp = $("#nomResp").val();
            var numResp = $("#numResp").val();

            var nomAutoActual = $("#nomAutoActual").val();
            var numAutoActual = $("#numAutoActual").val();
            var nomRespActual = $("#nomRespActual").val();
            var numRespActual = $("#numRespActual").val();

            if (nomAuto == null || nomAuto == "") {
                mostrarError("errmsj_nomAuto");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_nomAuto");
                validaRequired = validaRequired+1;
            }
            if (numAuto == null || numAuto == "") {
                mostrarError("errmsj_numAuto");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_numAuto");
                validaRequired = validaRequired+1;
            }

            if (nomResp == null || nomResp == "") {
                mostrarError("errmsj_nomResp");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_nomResp");
                validaRequired = validaRequired+1;
            }
            if (numResp == null || numResp == "") {
                mostrarError("errmsj_numResp");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_numResp");
                validaRequired = validaRequired+1;
            }

            if (validaRequired == 4) {
                ocultarError("errmsj_nomAuto");
                ocultarError("errmsj_numAuto");
                ocultarError("errmsj_nomResp");
                ocultarError("errmsj_numResp");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var ajax = $.ajax({
                    type: 'PUT',
                    data: { formAjax: formAjax, modulo: modulo, idTercero: idTercero, nomAuto: nomAuto, numAuto: numAuto, nomResp: nomResp, numResp: numResp, nomAutoActual: nomAutoActual, numAutoActual: numAutoActual, nomRespActual: nomRespActual, numRespActual: numRespActual },
                    dataType: 'JSON',
                    url: '{{ route("cambioautoresp") }}',
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
                            'Cambio de Auto./Resp.',
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
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.nomAuto !== undefined && response.responseJSON.errors.nomAuto[0] != "") {
                        $("#errmsj_nomAuto").html("<strong>"+response.responseJSON.errors.nomAuto[0]+"</strong>");
                        mostrarError("errmsj_nomAuto");
                    }
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.numAuto !== undefined && response.responseJSON.errors.numAuto[0] != "") {
                        $("#errmsj_numAuto").html("<strong>"+response.responseJSON.errors.numAuto[0]+"</strong>");
                        mostrarError("errmsj_numAuto");
                    }
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.nomResp !== undefined && response.responseJSON.errors.nomResp[0] != "") {
                        $("#errmsj_nomResp").html("<strong>"+response.responseJSON.errors.nomResp[0]+"</strong>");
                        mostrarError("errmsj_nomResp");
                    }
                    if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.numResp !== undefined && response.responseJSON.errors.numResp[0] != "") {
                        $("#errmsj_numResp").html("<strong>"+response.responseJSON.errors.numResp[0]+"</strong>");
                        mostrarError("errmsj_numResp");
                    }
                });
            }
            
            validaRequired = 0;
        }
        // FIN
    });
</script>
@endpush
