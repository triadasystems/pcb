@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="permisos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Permisos</div>

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
                                <a class="btn btn-success" href="{{route('permisos.create')}}">Registrar Permiso</a>
                            </div>
                        </div>
                        <div class="container table-responsive">
                            <table class="table table-bordered" id="permisos">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Lectura</th>
                                        <th>Escritura</th>
                                        <th>Actualizar</th>
                                        <th>Correo</th>
                                        <th>Ejecutar</th>
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
        $(document).on("change", ".permisos", function(){
            var id = $(this).attr("data-id");
            var tipo = $(this).attr("data-tipo");
            var act_ina = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                data: {id: id, tipo: tipo, act_ina: act_ina},
                url: '{{ route("desactivarpermisos") }}',
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
        });
        @if (session('confirmacion'))
            swal(
                'Permiso registrado',
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
        @if (session('vacio'))
            swal(
                'Advertencia',
                'Actualmente no existen perfiles sin permisos',
                'warning'
            )
        @endif        

        var table = $('#permisos').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}"
            // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route("consultatodopermisos.data") !!}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'profilename', name: 'profilename' },
            {
                targets: -1,
                render: function (data, type, row) {
                    var html = '';
                    var selected = '';
                    if(row.reading == "Activo") {
                        selected = '<option value="1" selected>Activo</option>';
                        selected += '<option value="0">Inactivo</option>';
                    } else {
                        selected = '<option value="1">Activo</option>';
                        selected += '<option value="0" selected>Inactivo</option>';
                    }
                    html = '<div class="row"><div class="col-lg-12 text-center"><select class="form-control permisos" data-id="'+row.id+'" data-tipo="reading">';
                    html += selected;
                    html += '</select></div></div>';
                    return html;
                }
            },
            {
                targets: -2,
                render: function (data, type, row) {
                    var html = '';
                    var selected = '';
                    if(row.writing == "Activo") {
                        selected = '<option value="1" selected>Activo</option>';
                        selected += '<option value="0">Inactivo</option>';
                    } else {
                        selected = '<option value="1">Activo</option>';
                        selected += '<option value="0" selected>Inactivo</option>';
                    }
                    html = '<div class="row"><div class="col-lg-12 text-center"><select class="form-control permisos" data-id="'+row.id+'" data-tipo="writing">';
                    html += selected;
                    html += '</select></div></div>';
                    return html;
                }
            },
            {
                targets: -3,
                render: function (data, type, row) {
                    var html = '';
                    var selected = '';
                    if(row.upgrade == "Activo") {
                        selected = '<option value="1" selected>Activo</option>';
                        selected += '<option value="0">Inactivo</option>';
                    } else {
                        selected = '<option value="1">Activo</option>';
                        selected += '<option value="0" selected>Inactivo</option>';
                    }
                    html = '<div class="row"><div class="col-lg-12 text-center"><select class="form-control permisos" data-id="'+row.id+'" data-tipo="upgrade">';
                    html += selected;
                    html += '</select></div></div>';
                    return html;
                }
            },
            {
                targets: -4,
                render: function (data, type, row) {
                    var html = '';
                    var selected = '';
                    if(row.send_email == "Activo") {
                        selected = '<option value="1" selected>Activo</option>';
                        selected += '<option value="0">Inactivo</option>';
                    } else {
                        selected = '<option value="1">Activo</option>';
                        selected += '<option value="0" selected>Inactivo</option>';
                    }
                    html = '<div class="row"><div class="col-lg-12 text-center"><select class="form-control permisos" data-id="'+row.id+'" data-tipo="send_email">';
                    html += selected;
                    html += '</select></div></div>';
                    return html;
                }
            },
            {
                targets: -5,
                render: function (data, type, row) {
                    var html = '';
                    var selected = '';
                    if(row.execution == "Activo") {
                        selected = '<option value="1" selected>Activo</option>';
                        selected += '<option value="0">Inactivo</option>';
                    } else {
                        selected = '<option value="1">Activo</option>';
                        selected += '<option value="0" selected>Inactivo</option>';
                    }
                    html = '<div class="row"><div class="col-lg-12 text-center"><select class="form-control permisos" data-id="'+row.id+'" data-tipo="execution">';
                    html += selected;
                    html += '</select></div></div>';
                    return html;
                }
            }
        ]
        });
    });
</script>
@endpush