@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcslistaactivos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de externos activos
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaTerceros">Alta de Externos Activos</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="terceros-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre | Gafete</th>
                                        <th>E-mail</th>
                                        <!--<th>Fecha de alta</th>
                                        <th>Fecha de baja</th>-->
                                        <th>Autorizador #</th>
                                        <th>Responsable #</th>
                                        <th>Empresa</th>
                                        <!--<th>Estado</th>-->
                                        <!--<th>Acciones</th>-->
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
        @if (session('confirmacion'))
            swal(
                'Tercero registrado',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif 
        @if (session('validacionCalculo'))
            swal(
                'Tercero registrado',
                'La operción no se puede realizar se ha llegado al limite de id de externos, favor de ponerse en contacto con el administrador.',
                'success'
            )
        @endif                         
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
                {
                    render: function (data, type, row) {
                        var gafete="S/N";
                        if (row.gafete!=null) {
                            gafete=row.gafete;
                        }
                        var autorizador = row.nombre+' | '+gafete;
                        return autorizador;
                    }
                },
                { data: 'correo', name: 'correo' },
                /*{ data: 'f_inicial', name: 'f_inicial' },
                { data: 'f_fin', name: 'f_fin' },*/
                {
                    render: function (data, type, row) {
                        var autorizador = row.nom_autorizador+' | '+row.num_autorizador;
                        return autorizador;
                    }
                },
                {
                    render: function (data, type, row) {
                        var reponsable = row.nom_resposable+' | '+row.num_resposable;
                        return reponsable;
                    }
                },
                { data: 'empresa', name: 'empresa' }/*,
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
                }*/
            ]
        });
    });
</script>
@endpush
