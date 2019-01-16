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
<input type="hidden" id="modulo" value="tcsproveedores" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Motivos de Proveedores</div>

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
                                <button type="button" class="btn btn-success" id="altaProveedor">Registrar Proveedor</button>
                            </div>
                        </div>
                        <div class="container table-responsive">
                            <table class="table table-bordered" id="tblproveedores">
                                <thead>
                                    <tr>
                                        <th scope="col">Alias</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Fecha de Alta</th>
                                        <th scope="col">Fecha de Baja</th>
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
            $("#altaProveedor").click(function(){
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
                            url: '{{ route("editarstatusproveedores") }}',
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
                                    'Proveedores',
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
            $(document).on("click", "#editarProveedor", function(){
                var id = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                var alias = $(this).attr("data-alias");
                var description = $(this).attr("data-descripcion");

                formAltaEditar("editar", id, name, alias, description);
            });

            $(document).on("click", "#guardarProveedor", function(){
                guardarAltaEditar();
            });

            $(document).on("click", "#guardarEditarProveedor", function(){
                guardarAltaEditar("editar");
            });

            var table = $('#tblproveedores').DataTable({
                language: {
                    url: "{{ asset('json/Spanish.json') }}"
                        // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route("proveedores.data") !!}',
                columns: [
                    { data: 'alias', name: 'alias' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' },
                    { data: 'high_date', name: 'high_date' },
                    { data: 'low_date', name: 'low_date' },
                    {
                        render: function (data, type, row) {
                            var html = '<div class="row"><div class="col-lg-5 text-center"><button class="btn btn-primary" id="editarProveedor" data-id="'+row.id+'" data-name="'+row.name+'" data-alias="'+row.alias+'" data-descripcion="'+row.description+'">Editar <i class="fas fa-edit"></i></button></div></div>';

                            return html;
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
        
            function formAltaEditar(tipo = "alta", id = "", name = "", alias = "", description = "") {
                var idBotonGuardar = "guardarProveedor"

                if(tipo == "editar") {
                    idBotonGuardar = "guardarEditarProveedor"
                }

                Swal({
                    title: 'PROVEEDORES',
                    // type: 'info',
                    html:
                    '<div class="container" style="margin-top: 10px;">'+
                        '<form method="post" action="">'+
                            '<input type="hidden" name="id" id="idProveedor" value="'+id+'">'+
                            '<div class="form-group row">'+
                                '<div class="col-md-6">'+
                                    '<label for="name" class="col-lg-12 col-form-label text-left txt-bold">Nombre</label>'+
                                    '<input id="name" type="text" class="form-control" name="name" required autofocus value="'+name+'">'+

                                    '<span id="errmsj_name" class="error-msj" role="alert">'+
                                        '<strong>El campo Nombre es obligatorio</strong>'+
                                    '</span>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                    '<label for="alias" class="col-lg-12 col-form-label text-left txt-bold">Alias</label>'+
                                    '<input id="alias" type="text" class="form-control" name="alias" required autofocus value="'+alias+'">'+

                                    '<span id="errmsj_alias" class="error-msj" role="alert">'+
                                        '<strong>El campo Alias es obligatorio</strong>'+
                                    '</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group row">'+
                                '<div class="col-md-12">'+
                                    '<label for="description" class="col-lg-12 col-form-label text-left txt-bold">Descripción</label>'+
                                    '<input id="description" type="text" class="form-control" name="description" required autofocus value="'+description+'">'+

                                    '<span id="errmsj_descripcion" class="error-msj" role="alert">'+
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
                var name = $("#name").val();
                var alias = $("#alias").val();
                var description = $("#description").val();

                if (name == null || name == "") {
                    mostrarError("errmsj_name");
                    if(validaRequired > 0) {
                        validaRequired = validaRequired-1;    
                    }
                } else {
                    ocultarError("errmsj_name");
                    validaRequired = validaRequired+1;
                }
                if (alias == null || alias == "") {
                    mostrarError("errmsj_alias");
                    if(validaRequired > 0) {
                        validaRequired = validaRequired-1;    
                    }
                } else {
                    ocultarError("errmsj_alias");
                    validaRequired = validaRequired+1;
                }
                if (description == null || description == "") {
                    mostrarError("errmsj_descripcion");
                    if(validaRequired > 0) {
                        validaRequired = validaRequired-1;    
                    }
                } else {
                    ocultarError("errmsj_descripcion");
                    validaRequired = validaRequired+1;
                }

                if (validaRequired == 3) {
                    ocultarError("errmsj_name");
                    ocultarError("errmsj_alias");
                    ocultarError("errmsj_descripcion");

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if(tipo == "editar") {
                        var id = $("#idProveedor").val();
                        var ajax = $.ajax({
                            type: 'PUT',
                            data: { name: name, alias: alias, description: description, id: id },
                            dataType: 'JSON',
                            url: '{{ route("editarproveedores") }}',
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
                            data: { name: name, alias: alias, description: description},
                            dataType: 'JSON',
                            url: '{{ route("altaproveedores") }}',
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
                                'Proveedores',
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
                        if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.name !== undefined && response.responseJSON.errors.name[0] != "") {
                            $("#errmsj_name").html("<strong>"+response.responseJSON.errors.name[0]+"</strong>");
                            mostrarError("errmsj_name");
                        }
                        if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.alias !== undefined && response.responseJSON.errors.alias[0] != "") {
                            $("#errmsj_alias").html("<strong>"+response.responseJSON.errors.alias[0]+"</strong>");
                            mostrarError("errmsj_alias");
                        }
                        if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.description !== undefined && response.responseJSON.errors.description[0] != "") {
                            $("#errmsj_descripcion").html("<strong>"+response.responseJSON.errors.description[0]+"</strong>");
                            mostrarError("errmsj_descripcion");
                        }
                    });
                }
                
                validaRequired = 0;
            }
        });
    </script>
@endpush