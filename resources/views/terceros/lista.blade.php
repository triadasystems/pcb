@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcslistaactivos" />
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    Lista de externos activos
                </div>
                <div class="panel-pbc-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaTerceros">Alta de externos activos</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered" id="terceros-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>E-mail</th>
                                        <th>Fecha de alta</th>
                                        <th>Fecha de baja</th>
                                        <th>Autorizador</th>
                                        <th>Responsable</th>
                                        <th>Empresa</th>
                                        <th>Estado</th>
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
    $(document).ready(function()
    {
        /*$(document).on("click","#delact", function()
        {
            var id = $(this).attr("data-id");
            var tipo = $(this).attr("data-tipo");
            swal({
                title: '¿Esta seguro?',
                text: "¡Siempre podrá revertir la acción!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor
            });
        });*/
                            
        $('#altaTerceros').click(function()
        {
            var url = '{!!route('terceros.alta')!!}';
            $( location).attr("href",url);
        });
        var table = $('#terceros-table').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('terceros.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nombre', name: 'nombre' },
                { data: 'correo', name: 'correo' },
                { data: 'f_inicial', name: 'f_inicial' },
                { data: 'f_fin', name: 'f_fin' },
                { data: 'nom_autorizador', name: 'nom_autorizador' },
                { data: 'nom_resposable', name: 'nom_resposable' },
                { data: 'empresa', name: 'empresa' },
                { data: 'empresa', name: 'empresa' },
                {
                    targets: -1,
                    render: function (data, type, row)
                    {
                        if (row.estatus == 1)
                        {
                            return '<div class="row"><div class="col-lg-5 text-center"><button type="button" class="btn btn-danger" data-id="'+row.id+'" data-tipo="Inactivo" id="delact">Desactivar <i class="fas fa-ban"></i></button></div></div>';
                        } 
                        else if(row.estatus == 2)
                        {
                            return '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-id="'+row.id+'" data-tipo="Activo" id="delact">Activar <i class="fas fa-check"></i></button></div></div>';
                        }
                    }
                }
            ]
        });
    });
</script>
@endpush
