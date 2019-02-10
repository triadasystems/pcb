@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporte de Trazabilidad de Terceros
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="bajasdiarias-table">
                            <thead>
                                <tr>
                                    <th>Número&nbsp;del&nbsp;Empleado</th>
                                    <th>#&nbsp;Gafete&nbsp;del&nbsp;Tercero</th>
                                    <th>Usuario&nbsp;del&nbsp;Tercero</th>
                                    <th>Fecha&nbsp;de&nbsp;Alta</th>
                                    <th>FUS Fisico</th>
                                    <th>Tipo&nbsp;de&nbsp;Movimiento</th>
                                    <th>Autorizador&nbsp;#</th>
                                    <th>Responsable&nbsp;#</th>
                                    <th>Fecha&nbsp;Captura&nbsp;de&nbsp;Baja</th>
                                    <th>Motivo&nbsp;Baja</th>
                                    <th>Fecha&nbsp;Efectiva&nbsp;de&nbsp;Baja</th>
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

        var table = $('#bajasdiarias-table').DataTable({
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
            
            ajax: '{!! route("trazabilidad.data") !!}',
            columns: [
                { data: 'id_external', name: 'id_external' },
                { data: 'badge_number', name: 'badge_number' },
                { data: 'email', name: 'email' },
                { data: 'initial_date', name: 'initial_date' },
                { data: 'fus_physical', name: 'fus_physical' },
                { data: 'description', name: 'description' },
                { data: 'autorizador', name: 'autorizador' },
                { data: 'responsable', name: 'responsable' },
                { data: 'low_date', name: 'low_date' },
                { data: 'typelow', name: 'typelow' },
                { data: 'real_low_date', name: 'real_low_date' }
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