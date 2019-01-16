@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporte de Bajas Diarias
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="bajasdiarias-table">
                            <thead>
                                <tr>
                                    <th># Gafete del Tercero</th>
                                    <th>Usuario del Tercero</th>
                                    <th>Nombre del Empleado</th>
                                    <th>Autorizador #</th>
                                    <th>Fecha de Baja</th>
                                    <th>Tipo de Baja</th>
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
            
            ajax: '{!! route("bajasdiarias.data") !!}',
            columns: [
                { data: 'badge_number', name: 'badge_number' },
                { data: 'email', name: 'email' },
                {
                    render: function (data, type, row) {
                        var nombre = row.name+' '+row.lastname1+' '+row.lastname2;
                        return nombre;
                    }
                },
                {
                    render: function (data, type, row) {
                        var autorizador = row.authorizing_name+' | '+row.authorizing_number;
                        return autorizador;
                    }
                },
                {
                    render: function (data, type, row) {
                        var lowDate = new Date(row.low_date).getTime() / 1000;
                        var lowDateFus = new Date(row.low_date_fus).getTime() / 1000;
                        
                        var showDateLow;

 	                    if(lowDate < lowDateFus) {
                            showDateLow = row.low_date_fus;
                        } else {
                            showDateLow = row.low_date;
                        }

                        return showDateLow;
                    }
                },
                // { data: 'low_date', name: 'low_date' },
                { data: 'typelow', name: 'typelow' }
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