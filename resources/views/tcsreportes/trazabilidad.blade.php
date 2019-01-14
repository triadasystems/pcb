@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-pbc">
                <div class="panel-pbc-head">
                    Reporte de Trazabilidad de Terceros
                </div>
                <div class="panel-pbc-body">
                    <div class="row table-responsive">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="bajasdiarias-table">
                                <thead>
                                    <tr>
                                        <th># Gafete del Tercero</th>
                                        <th>Usuario del Tercero</th>
                                        <th>Fecha de Alta</th>
                                        <th>Autorizador #</th>
                                        <th>Fecha Captura de Baja</th>
                                        <th>Motivo Baja</th>
                                        <th>Fecha Efectiva de Baja</th>
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
                { data: 'badge_number', name: 'badge_number' },
                { data: 'email', name: 'email' },
                { data: 'initial_date', name: 'initial_date' },
                {
                    render: function (data, type, row) {
                        var autorizador = row.authorizing_name+' | '+row.authorizing_number;
                        return autorizador;
                    }
                },
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