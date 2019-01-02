@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="conexiones" />
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    Lista de Conexiones
                </div>
                <div class="panel-pbc-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="registerObj">Alta de Conexiones</button>
                        </div>
                    </div>
                    <div class="row table-responsive">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="conexiones-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Base de Datos</th>
                                        <th>SOX</th>
                                        <th>Hostname</th>
                                        <th>Dirección Ip</th>
                                        <th>Puerto</th>
                                        <th>Modificación estatus</th>
                                        <th>Editar</th>
                                        <th>Consultas</th>
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
    $(document).on("click", "#edit", function () {
        var id = $(this).attr("data-id");
        window.location.href = 'edit/' + id;
    });
    $(document).on("click", "#consultas", function () {
        var id = $(this).attr("data-id");
        window.location.href = 'consultas/lista/' + id;
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
                    url: '{{ route("desactivarconexiones") }}',
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

    @if(session('confirmacion'))
        swal(
            'Conexión agregada',
            'La operación se ha realizado con éxito',
            'success'
        )
    @endif
    @if(session('actualizado'))
        swal(
            'Conexión editada',
            'La operación se ha realizado con éxito',
            'success'
        )
    @endif
    @if(session('errorMsj'))
        swal(
            'Error',
            '{{ session("errorMsj") }}',
            'error'
        )
    @endif

    $('#registerObj').click(function(){
        var url = "{{URL::to('/')}}/conexiones";
        url = url+"/create";
        $( location ).attr("href", url);
    });

    var table = $('#conexiones-table').DataTable({
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
        ajax: '{!! route("datatablesCon.data") !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'rdbms_type', name: 'rdbms_type' },
            { data: 'sox', name: 'sox' },
            { data: 'hostname', name: 'hostname' },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'port', name: 'port' },
            {
                targets: -1,
                render: function (data, type, row) {
                    var html = '<div class="row">';
                    if(row.status == "Activo") {
                        html += '<div class="col-lg-12 text-left"><button type="button" class="btn btn-danger" data-id="'+row.id+'" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div>';
                    } else if(row.status == "Inactivo") {
                        html += '<div class="col-lg-12 text-left"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div>';
                    }
                    html += '</div>';
                    return html;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    // return '<div class="col-lg-12 text-right"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Editar <i class="fas fa-check"></i></button></div>';
                    return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id + '" id="edit">Editar <i class="fas fa-edit"></i></button></div></div>';
                }
            },
            {
                targets: -3,
                render: function (data, type, row) {
                    return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id + '" id="consultas">Consultas <i class="far fa-list-alt"></i></button></div></div>';
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