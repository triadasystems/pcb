@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="tcsaplicacion" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administración de Aplicaciones
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaAplicaciones">Alta de Aplicaciones</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="aplicaciones-table" name = "aplicaciones-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Aplicación</th>
                                    <th>Alias</th>
                                    <th>Estatus</th>
                                    <!-- <th>Editar</th> -->
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

    @if (session('confirmacion'))
            swal(
                'Aplicación registrada',
                'La operación se ha realizado con éxito.',
                'success'
            )
        @endif

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
                    url: '{{ route("desactivarapp") }}',
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

    $('#altaAplicaciones').click(function()
        {
            var url = '{!!route('altaAplicaciones')!!}';
            $( location).attr("href",url);
        });

    var table = $('#aplicaciones-table').DataTable({
        language:{
            url: "{{ asset('json/Spanish.json') }}",
            buttons: {
                copyTitle: 'Tabla copiada',
                copySuccess: {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
        procesing: true,
        serverSide: true,
        ajax: '{!! route("aplicacion.lista") !!}',
        columns:[
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'alias', name: 'alias'},
            {
                targets: -1,
                render: function (data, type, row) {
                    if(row.active == "1")
                    {
                        return '<div class="row"><div class="col-lg-5 offset-lg-2 text-center"><button type="button" class="btn btn-danger" data-id="'+row.id+'" data-tipo="2" id="delact">Desactivar <i class="fas fa-ban"></i></button></div></div>';
                    } 
                    else if(row.active == "2")
                    {
                        return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="1" id="delact">Activar <i class="fas fa-check"></i></button></div></div>';
                    }
                }
            }/*,
            {
                targets: -2,
                render: function (data, type, row)
                {
                    // return '<div class="col-lg-12 text-right"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Editar <i class="fas fa-check"></i></button></div>';
                    return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="' + row.id + '" id="edit">Editar <i class="fas fa-edit"></i></button></div></div>';
                }
            }*/
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