@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    Reporte de Bajas Diarias
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="responsables-table">
                            <thead>
                                <tr>
                                    <th># de Empleado</th>
                                    <th>Nombre del Autorizador/Responsable</th>
                                    <th>Tipo</th>
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

        var table = $('#responsables-table').DataTable({
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
            
            ajax: '{!! route("responsables.data") !!}',
            columns: [
                { data: 'numero', name: 'numero' },
                { data: 'nombre', name: 'nombre' },
                {
                    render: function (data, type, row) {
                        var tipo = row.tipo;
                        var cargo = '';

                        switch (tipo) {
                            case '1':
                                cargo = 'Autorizador';
                                break;
                            case '2':
                                cargo = 'Responsable';
                                break;
                            case '3':
                                cargo = 'Autorizador/Responsabñe';
                                break;
                        }
                        return cargo;
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