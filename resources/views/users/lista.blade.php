@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="usuarios" />
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    Lista de Usuarios
                </div>
                <div class="panel-pbc-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaUser">Alta de Usuarios</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th># Empleado</th>
                                        <th>E-mail</th>
                                        <th>Estado</th>
                                        <th>Fecha de creación</th>
                                        <th>Perfil / Rol</th>
                                        <th>Acciones</th>
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

    @if(session('confirmacion'))
        swal(
            'Usuario agregado',
            'La operación se ha realizado con éxito',
            'success'
        )
    @endif

    var urlEdit = "{{URL::to('/')}}/usuarios/edit";
    var urlDel = "{{URL::to('/')}}/usuarios/del";

    $('#altaUser').click(function(){
        var url = "{{URL::to('/')}}";
        url = url+"/register";
        $( location ).attr("href", url);
    });

    $(document).on("change", ".rol", function(){
        var rol = $(this).val();
        var id = $(this).attr("data-id");

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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    data: {id: id, rol: rol},
                    url: '{{ route("cambiarrol") }}',
                    async: false,
                    beforeSend: function(){
                        $("#loading_pes").removeClass("loading_pes_hide");
                    },
                    complete: function(){
                        $("#loading_pes").addClass("loading_pes_hide");
                    }
                }).done(function(response){
                    if(response == "true") {
                        table.ajax.reload();
                        swal(
                            'Cambio de rol',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response == "false") {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    } else if(response == "middleUpgrade") {
                            window.location.href = "{{ route('homeajax') }}";
                    }
                });
            }
        });
    });

    $(document).on("click", "#delact", function(){
        var id = $(this).attr("data-id");
        var tipo = $(this).attr("data-tipo");

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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    data: {id: id, tipo: tipo},
                    url: '{{ route("desactivarusuarios") }}',
                    async: false,
                    beforeSend: function(){
                        // $("#loading_pes").removeClass("loading_pes_hide");
                        mostrarLoadingMail();
                    },
                    complete: function(){
                        // $("#loading_pes").addClass("loading_pes_hide");
                        ocultarLoadingMail();
                    }
                }).done(function(response){
                    if(response == "true") {
                        table.ajax.reload();
                        swal(
                            'Activar/Desactivar',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response == "false") {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    } else if(response == "middleUpgrade") {
                            window.location.href = "{{ route('homeajax') }}";
                    }
                });
            }
        });
    });

    var table = $('#users-table').DataTable({
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
        ajax: '{!! route('datatables.data') !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'num_employee', name: 'num_employee' },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            {
                targets: -1,
                render: function (data, type, row) {
                    var html = '';
                    var perfiles
                    
                    html += '<select class="form-control rol" data-id="'+row.id+'">';
                    
                    if(row.profilename != "") {
                        html += '<option value ="'+row.idP+'">'+row.profilename+'</option>';
                    } else {
                        html += '<option>Seleccione...</option>';
                    }
                    @foreach($perfiles as $row)
                        var per = "{{ $row->profilename }}";
                        if(per != row.profilename) {
                            html += '<option value="{{ $row->id }}">{{ $row->profilename }}</option>';
                        }
                    @endforeach
                    
                    html += '</select>';
                    
                    return html;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    var html = '<div class="row">';
                    if(row.status == "Activo") {
                        html += '<div class="col-md-6 text-center"><button type="button" class="btn btn-danger" data-id="'+row.id+'" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div>';
                    } else if(row.status == "Inactivo") {
                        html += '<div class="col-md-6 text-center"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div>';
                    }

                    html += '<div class="col-md-6 text-center"><button class="btn btn-primary editUsuario" data-id="'+row.id+'" data-nombre="'+row.name+'" data-apellidos="'+row.lastname+'" data-num="'+row.num_employee+'">Editar <i class="fa fa-edit"></i></button></div></div>';

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

    $(document).on("click", ".editUsuario", function(){
        var id = $(this).attr("data-id");
        var nombre = $(this).attr("data-nombre");
        var apellidos = $(this).attr("data-apellidos");
        var numEmpleado = $(this).attr("data-num");

        formAltaEditar(id, nombre, apellidos, numEmpleado);
    });
    
    function formAltaEditar(id = "", nombre = "", apellidos = "", numEmpleado = "") {
        Swal({
            title: 'USUARIOS PCB',
            // type: 'info',
            html:
            '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    '<input type="hidden" name="id" id="idUsuario" value="'+id+'">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-4">'+
                            '<label for="nombre" class="col-lg-12 col-form-label text-left txt-bold">Nombre</label>'+
                            '<input id="nombre" type="text" class="form-control" name="nombre" required autofocus value="'+nombre+'">'+

                            '<span id="errmsj_nombre" class="error-msj" role="alert">'+
                                '<strong>El campo es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<label for="apellidos" class="col-lg-12 col-form-label text-left txt-bold">Apellidos</label>'+
                            '<input id="apellidos" type="text" class="form-control" name="apellidos" required autofocus value="'+apellidos+'">'+

                            '<span id="errmsj_apellidos" class="error-msj" role="alert">'+
                                '<strong>El campo es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<label for="numEmpleado" class="col-lg-12 col-form-label text-left txt-bold"># Empleado</label>'+
                            '<input id="numEmpleado" type="number" class="form-control" name="numEmpleado" required autofocus value="'+numEmpleado+'" min="1" step="1">'+

                            '<span id="errmsj_numEmpleado" class="error-msj" role="alert">'+
                                '<strong>El campo es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-12 text-right">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                        '<input class="btn btn-primary" id="guardarUsuario" type="button" value="Guardar">'+
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

    $(document).on("click", "#guardarUsuario", function(){
        guardarAltaEditar();
    });

    var validaRequired = 0;

    function guardarAltaEditar() {
        var nombre = $("#nombre").val();
        var apellidos = $("#apellidos").val();
        var numEmpleado = $("#numEmpleado").val();
        var id = $("#idUsuario").val();
        var formAjax = $("#formAjax").val();

        if (nombre == null || nombre == "") {
            mostrarError("errmsj_nombre");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_nombre");
            validaRequired = validaRequired+1;
        }
        if (apellidos == null || apellidos == "") {
            mostrarError("errmsj_apellidos");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_apellidos");
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

        if (validaRequired == 3) {
            ocultarError("errmsj_nombre");
            ocultarError("errmsj_apellidos");
            ocultarError("errmsj_numEmpleado");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var ajax = $.ajax({
                type: 'PUT',
                data: { formAjax: formAjax, nombre: nombre, apellidos: apellidos, numEmpleado: numEmpleado, id: id },
                dataType: 'JSON',
                url: '{{ route("userupdate") }}',
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
                        'Usuario PCB',
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
                console.log(response.responseText);
                if (response.responseText !== undefined && response.responseText == "middleUpgrade") {
                    window.location.href = "{{ route('homeajax') }}";
                }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.nombre !== undefined && response.responseJSON.errors.nombre[0] != "") {
                    $("#errmsj_nombre").html("<strong>"+response.responseJSON.errors.nombre[0]+"</strong>");
                    mostrarError("errmsj_nombre");
                }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.apellidos !== undefined && response.responseJSON.errors.apellidos[0] != "") {
                    $("#errmsj_apellidos").html("<strong>"+response.responseJSON.errors.apellidos[0]+"</strong>");
                    mostrarError("errmsj_apellidos");
                }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.numEmpleado !== undefined && response.responseJSON.errors.numEmpleado[0] != "") {
                    $("#errmsj_numEmpleado").html("<strong>"+response.responseJSON.errors.numEmpleado[0]+"</strong>");
                    mostrarError("errmsj_numEmpleado");
                }
            });
        }
        
        validaRequired = 0;
    }
});
</script>
@endpush