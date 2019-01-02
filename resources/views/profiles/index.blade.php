@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="perfiles" />
<div class = "container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class = "card-header">{{ __('Perfiles') }}</div>
                <div class="card-body">
                    <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                        <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                        <a class="btn btn-success" href="{{route('crear_perfil')}}">Registrar Perfil</a>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if(isset($msjError))
                        <div class="col-md-12 text-center">
                            <span style="width: 100%; margin-top: .25rem; font-size: 80%; color: #e3342f !important;" role="alert">
                                <strong>{{$msjError}}</strong>
                            </span>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="profiles-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Estatus</th>
                                        <th>Modificaci&oacute;n estatus</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        @if (session('confirmacion'))
            swal(
                'Perfil registrado',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif
        @if (session('edito'))
            swal(
                'Perfil editado',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif

        $(document).on("click", "#edit", function () {
            var id = $(this).attr("data-id");
            window.location.href = 'edit/' + id;
        });
        $(document).on("click", "#delact", function () {
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
                        url: '{{ route("desactivarprofiles") }}',
                        async: false,
                        beforeSend: function () {
                            $("#loading_pes").removeClass("loading_pes_hide");
                        },
                        complete: function () {
                            $("#loading_pes").addClass("loading_pes_hide");
                        }
                    }).done(function (response) {
                        if (response == "true") {
                            table.ajax.reload();
                            swal(
                                'Activar/Desactivar',
                                'La operación se ha realizado con éxito',
                                'success'
                            )
                        } else if (response == "false") {
                            swal(
                                'Error',
                                'La operación no pudo ser realizada',
                                'danger'
                            )
                        } else if (response == "middleUpgrade") {
                            window.location.href = "{{ route('homeajax') }}";
                        }
                    });
                }
            })
        });
    
        var table = $('#profiles-table').DataTable({
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
            ajax: '{!! route('consulta.data') !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'profilename', name: 'profilename'},
                {data: 'description', name: 'description'},
                {data: 'status', name: 'status'},
                {
                    targets: - 1,
                    render: function (data, type, row) {
                        var botones;
                        if (row.status == "Activo") {
                            botones = '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-danger" data-id="' + row.id + '" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div></div>';
                        } else if (row.status == "Inactivo") {
                            botones = '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id + '" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div></div>';
                        }
                        return botones;
                    }
                },
                {
                    targets: - 2,
                    render: function (data, type, row) {
                        return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id + '" id="edit">Editar <i class="fas fa-edit"></i></button></div></div>';
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