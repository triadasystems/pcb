@extends('layouts.app')

@section('content')
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
    $(document).ready(function(){
        $(document).on("click", "#sustituir", function(){
            var numEmpleado = $(this).attr("data-num-empleado");

            formEditar(numEmpleado);
        });
        
        function formEditar(numEmpleado = "") {
            var idBotonGuardar = "guardarMesaControl"

            if(tipo == "editar") {
                idBotonGuardar = "guardarEditarMesa"
            }

            Swal({
                title: 'MESA DE CONTROL',
                // type: 'info',
                html:
                '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                        '<input type="hidden" name="id" id="idMesaControl" value="'+id+'">'+
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
// NEL
        $(document).on("click", "#guardarsustitucion", function(){
            swal({
                title: '¿Esta seguro?',
                text: "¡Todos los terceros asignados a este empleado serán actualizados.!",
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
                        url: '{{ route("sustitucion") }}',
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
                                            
                    });
                }
            });
        });
// FIN
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
                                    '<div class="col-lg-5 text-center">'+
                                        '<button class="btn btn-primary" id="sustituir" data-num-empleado="'+row.numero+'">'+
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
    });
</script>
@endpush