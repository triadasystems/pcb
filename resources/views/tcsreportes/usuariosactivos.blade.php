@extends('layouts.app')

@section('content')
<div class="container-fluid">
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
                                    <th>#&nbsp;Gafete&nbsp;del&nbsp;Tercero</th>
                                    <th>Número&nbsp;del&nbsp;Empleado</th>
                                    <th>Nombre&nbsp;del&nbsp;Empleado</th>
                                    <th>Fecha&nbsp;Inicial</th>
                                    <th>Fecha&nbsp;de&nbsp;Baja</th>
                                    <th>Autorizador&nbsp;#</th>
                                    <th>Responsable&nbsp;#</th>
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
                { data: 'badge_number', name: 'badge_number' },
                { data: 'id_external', name: 'id_external' },
                { data: 'datos_tercero', name: 'datos_tercero' },                
                { data: 'initial_date', name: 'initial_date' },
                { data: 'low_date', name: 'low_date' },
                { data: 'autorizador', name: 'autorizador' },
                { data: 'responsable', name: 'responsable' }
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