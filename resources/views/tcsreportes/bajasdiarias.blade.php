@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporte de Bajas Diarias
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
                                    <th>Solicitante</th>
                                    <th>Número&nbsp;del&nbsp;Empleado</th>
                                    <th>#&nbsp;Gafete&nbsp;del&nbsp;Tercero</th>
                                    <th>Nombre&nbsp;del&nbsp;Tercero</th>
                                    <!-- <th>Autorizador&nbsp;#</th>
                                    <th>Responsable&nbsp;#</th> -->
                                    <th>Fecha&nbsp;de&nbsp;Baja</th>
                                    <th>Tipo&nbsp;de&nbsp;Baja</th>
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
                { data: 'quien_realizo',      name: 'quien_realizo' },
                { data: 'id_external',      name: 'id_external' },
                { data: 'badge_number',     name: 'badge_number' },
                { data: 'datos_tercero',    name: 'datos_tercero' },
                // { data: 'autorizador',      name: 'autorizador' },
                // { data: 'responsable',      name: 'responsable' },
                { data: 'low_date_fus',     name: 'low_date_fus' },
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