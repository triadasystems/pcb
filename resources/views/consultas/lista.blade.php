@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="conexiones" />
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    Lista de Consultas
                </div>
                <div class="panel-pbc-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a href="{{ route('listaConexiones') }}" class="btn btn-warning" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                            <button class="btn btn-success" id="registerObj">Alta de Consultas</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered" id="conexiones-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Aplicación</th>
                                        <th>Conexión</th>
                                        <th>Consulta</th>
                                        <th>Modificación estatus</th>
                                        <th>Editar</th>
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
        window.location.href = '../edit/' + id;
    });
    $(document).on("click", ".verConsulta", function () {
        var id = $(this).attr("data-id");
        var textQuery = $('#query_'+id).text();

        swal({
            title: 'Consulta',
            type: 'info',
            html: '<pre><code>'+textQuery+'</code></pre>',
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
                    url: '{{ route("desactivarconsultas") }}',
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
            'Consulta agregada',
            'La operación se ha realizado con éxito',
            'success'
        )
    @endif
    @if(session('edicion'))
        swal(
            'Consulta editada',
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
        var url = "{{URL::to('/')}}/conexiones/consultas";
        url = url+"/create/{{ $id }}";
        $( location ).attr("href", url);
    });

    var table = $('#conexiones-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}"
            // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route("datatablesConsultas.data", $id) !!}',
        columns: [
            { data: 'id_query', name: 'id_query' },
            { data: 'aplicacion', name: 'aplicacion' },
            { data: 'conexion', name: 'conexion' },
            {
                targets: -1,
                render: function (data, type, row) {
                    var html = '';
                    html = "<div class='row'>"+
                        "<div class='col-lg-12 text-center'>"+
                            "<button type='button' class='btn btn-primary verConsulta' data-id='" + row.id_query + "'>"+
                                "Ver Consulta <i class='far fa-list-alt'></i>"+
                            "</button>"+
                            "<p class='p_query' id='query_"+row.id_query+"'>"+
                                row.query
                            "</p>"+
                        "</div>"+
                    "</div>";

                    return html;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    var html = '<div class="row">';
                    if(row.status == "Activo") {
                        html += '<div class="col-lg-12 text-center"><button type="button" class="btn btn-danger" data-id="'+row.id_query+'" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div>';
                    } else if(row.status == "Inactivo") {
                        html += '<div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="'+row.id_query+'" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div>';
                    }
                    html += '</div>';
                    return html;
                }
            },
            {
                targets: -3,
                render: function (data, type, row) {
                    // return '<div class="col-lg-12 text-right"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Editar <i class="fas fa-check"></i></button></div>';
                    return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id_query + '" id="edit">Editar <i class="fas fa-edit"></i></button></div></div>';
                }
            }
        ]
    });
});
</script>
@endpush