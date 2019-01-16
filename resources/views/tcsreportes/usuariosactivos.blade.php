@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporte de Usuarios Activos
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tcsactivos-table">
                            <thead>
                                <tr>
                                    <th># Gafete del Tercero</th>
                                    <th>Nombre del Empleado</th>
                                    <th>Fecha Inicial</th>
                                    <th>Fecha de Baja</th>
                                    <th>Autorizador #</th>
                                    <th>Responsable #</th>
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

        var table = $('#tcsactivos-table').DataTable({
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
            
            ajax: '{!! route("tercerosactivos.data") !!}',
            columns: [
                { data: 'id_generate_fus', name: 'id_generate_fus' },
                {
                    render: function (data, type, row) {
                        var nombre = row.name+' '+row.lastname1+' '+row.lastname2;
                        return nombre;
                    }
                },
                { data: 'initial_date', name: 'initial_date' },
                { data: 'low_date', name: 'low_date' },
                {
                    render: function (data, type, row) {
                        var autorizador = row.authorizing_name+' | '+row.authorizing_number;
                        return autorizador;
                    }
                },
                {
                    render: function (data, type, row) {
                        var reponsable = row.responsible_name+' | '+row.responsible_number;
                        return reponsable;
                    }
                },
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