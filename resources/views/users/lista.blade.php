@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="usuarios" />
<div class="container">
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
                    if(row.status == "Activo") {
                        return '<div class="row"><div class="col-lg-5 offset-lg-2 text-center"><button type="button" class="btn btn-danger" data-id="'+row.id+'" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div></div>';
                    } else if(row.status == "Inactivo") {
                        return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div></div>';
                    }
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