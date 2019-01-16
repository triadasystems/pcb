@extends('layouts.app')

@section('content')
<style>
    /* SweetAlert2 */
    /* .swal2-popup .swal2-title {
        font-size: 1.05em !important;
    } */
    .col-form-label {
        font-size: .9rem;
    }
    .txt-bold {
        font-weight:bold;
    }
</style>
<input type="hidden" id="modulo" value="tcsmotivosbajas" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Motivos de Bajas</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                                <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                                <button type="button" class="btn btn-success" id="altaMotivoBaja">Registrar Motivo de Baja</button>
                            </div>
                        </div>
                        <div class="container table-responsive">
                            <table class="table table-bordered" id="tblmotivosbajas">
                                <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Edición</th>
                                        <th scope="col">Modificación de Estado</th>
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
        $(document).ready(function(){
            $(document).on("change", "#code",function() {
                let x = $(this).val();

                if(!isNaN(x)) {
                    if(x.toString().length > 10) {
                        x = x.toString().slice(0, 10);
                    }

                    let result = Math.abs(x);


                    $(this).val(parseInt(result));
                } else {
                    $(this).val("");
                }
            });

            $("#altaMotivoBaja").click(function(){
                formAltaEditar();
            });

            $(document).on("click", "#cambioStatus", function(){
                swal({
                    title: '¿Esta seguro?',
                    text: "¡Siempre podrá revertir la acción!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.value) {
                        var id = $(this).attr("data-id");
                        var statusNuevo = $(this).attr("data-status-nuevo");

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'PUT',
                            data: { id: id, status: statusNuevo },
                            dataType: 'JSON',
                            url: '{{ route("editarstatusmotivobaja") }}',
                            async: false,
                            beforeSend: function(){
                                console.log("Cargando");
                            },
                            complete: function(){
                                console.log("Listo");
                            }
                        }).done(function(response){
                            if(response === true) {
                                table.ajax.reload();
                                swal(
                                    'Motivos de Bajas',
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
                                                
                        });
                    }
                });
            });

            $(document).on("click", "#editarMotivoBaja", function(){
                var id = $(this).attr("data-id");
                var codigo = $(this).attr("data-codigo");
                var descripcion = $(this).attr("data-descripcion");

                formAltaEditar("editar", id, codigo, descripcion);
            });

            $(document).on("click", "#guardarMotBaja", function(){
                guardarAltaEditar();
            });

            $(document).on("click", "#guardarEditarMotBaja", function(){
                guardarAltaEditar("editar");
            });

            var table = $('#tblmotivosbajas').DataTable({
                language: {
                    url: "{{ asset('json/Spanish.json') }}"
                        // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route("motivosbajas.data") !!}',
                columns: [
                    { data: 'code', name: 'code' },
                    { data: 'status', name: 'status' },
                    { data: 'type', name: 'type' },
                    {
                        render: function (data, type, row) {
                            return '<div class="row"><div class="col-lg-5 text-center"><button class="btn btn-primary" id="editarMotivoBaja" data-id="'+row.id+'" data-codigo="'+row.code+'" data-descripcion="'+row.type+'">Editar <i class="fas fa-edit"></i></button></div></div>';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            var html = '';
                            switch (row.status) {
                                case "Activo":
                                    html += '<div class="row"><div class="col-lg-5 text-center"><button class="btn btn-danger" id="cambioStatus" data-status-nuevo="Inactivo" data-id="'+row.id+'">Desactivar <i class="fas fa-ban"></i></button></div></div>';
                                    break;
                                case "Inactivo":
                                    html += '<div class="row"><div class="col-lg-5 text-center"><button class="btn btn-primary" id="cambioStatus" data-status-nuevo="Activo" data-id="'+row.id+'">Activar <i class="fas fa-check"></i></button></div></div>';
                                    break;
                            }

                            return html;
                        }
                    }
                ]
            });
            
            function formAltaEditar(tipo = "alta", id = "", codigo = "", descripcion = "") {
                var idBotonGuardar = "guardarMotBaja"

                if(tipo == "editar") {
                    idBotonGuardar = "guardarEditarMotBaja"
                }

                Swal({
                    title: 'MOTIVOS DE BAJAS',
                    // type: 'info',
                    html:
                    '<div class="container" style="margin-top: 10px;">'+
                        '<form method="post" action="">'+
                            '<input type="hidden" name="id" id="idMotBaja" value="'+id+'">'+
                            '<div class="form-group row">'+
                                '<div class="col-md-6">'+
                                    '<label for="code" class="col-lg-12 col-form-label text-left txt-bold">Código</label>'+
                                    '<input id="code" type="number" class="form-control" name="code" required autofocus value="'+codigo+'" min="1" step="1">'+

                                    '<span id="errmsj_codigo" class="error-msj" role="alert">'+
                                        '<strong>El campo Código es obligatorio</strong>'+
                                    '</span>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<label for="type" class="col-lg-12 col-form-label text-left txt-bold">Descripción</label>'+
                                    '<input id="type" type="text" class="form-control" name="type" required autofocus value="'+descripcion+'">'+

                                    '<span id="errmsj_tipo" class="error-msj" role="alert">'+
                                        '<strong>El campo Descripción es obligatorio</strong>'+
                                    '</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-12 text-right">'+
                                '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                                '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                                '<input class="btn btn-primary" id="'+idBotonGuardar+'" type="button" value="Guardar">'+
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

            var validaRequired = 0;

            function guardarAltaEditar(tipo = "alta") {
                var codigo = $("#code").val();
                var descripcion = $("#type").val();
                var modulo = $("#modulo").val();

                if (codigo == null || codigo == "") {
                    mostrarError("errmsj_codigo");
                    if(validaRequired > 0) {
                        validaRequired = validaRequired-1;    
                    }
                } else {
                    ocultarError("errmsj_codigo");
                    validaRequired = validaRequired+1;
                }
                if (descripcion == null || descripcion == "") {
                    mostrarError("errmsj_tipo");
                    if(validaRequired > 0) {
                        validaRequired = validaRequired-1;    
                    }
                } else {
                    ocultarError("errmsj_tipo");
                    validaRequired = validaRequired+1;
                }

                if (validaRequired == 2) {
                    ocultarError("errmsj_codigo");
                    ocultarError("errmsj_tipo");

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if(tipo == "editar") {
                        var id = $("#idMotBaja").val();
                        var ajax = $.ajax({
                            type: 'PUT',
                            data: { code: codigo, type: descripcion, modulo: modulo, id: id },
                            dataType: 'JSON',
                            url: '{{ route("editarmotivobaja") }}',
                            async: false,
                            beforeSend: function(){
                                console.log("Cargando");
                            },
                            complete: function(){
                                console.log("Listo");
                            }
                        });
                    } else {
                        var ajax = $.ajax({
                            type: 'POST',
                            data: { code: codigo, type: descripcion, modulo: modulo },
                            dataType: 'JSON',
                            url: '{{ route("altamotivobaja") }}',
                            async: false,
                            beforeSend: function(){
                                console.log("Cargando");
                            },
                            complete: function(){
                                console.log("Listo");
                            }
                        });
                    }
                    
                    ajax.done(function(response){
                        if(response === true) {
                            table.ajax.reload();
                            swal(
                                'Motivo de Baja',
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
                        if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.code !== undefined && response.responseJSON.errors.code[0] != "") {
                            $("#errmsj_codigo").html("<strong>"+response.responseJSON.errors.code[0]+"</strong>");
                            mostrarError("errmsj_codigo");
                        }
                        if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.type !== undefined && response.responseJSON.errors.type[0] != "") {
                            $("#errmsj_tipo").html("<strong>"+response.responseJSON.errors.type[0]+"</strong>");
                            mostrarError("errmsj_tipo");
                        }
                    });
                }
                
                validaRequired = 0;
            }
        });
    </script>
@endpush