@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="emails" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Correos</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                                <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                                <a class="btn btn-success" href="{{route('mails.create')}}">Registrar correo</a>
                            </div>
                        </div>
                        <div class="container table-responsive">
                            <table class="table table-bordered" id='mails'>
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Conciliación</th>
                                        <th scope="col">Bajas</th>
                                        <th></th>
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
        @if (session('confirmacion'))
            swal(
                'Correo registrado',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif
        @if (session('edito'))
            swal(
                'Perfil editado',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif
        @if (session('vacio'))
            swal(
                'Advertencia',
                'Actualmente no existen perfiles sin permisos',
                'warning'
            )
        @endif
        var table = $('#mails').DataTable({
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
            ajax: '{!! route('consultatodomails.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'correo', name: 'correo' },
                { data: 'automatizacion', name: 'automatizacion' },
                { data: 'bajas', name: 'bajas' },
                {
                    targets: - 1,
                    render: function (data, type, row) {
                        return '<div class="row"><div class="col-lg-5 text-center"><a class="btn btn-primary" href="mails/' + row.id + '/edit">Editar <i class="fas fa-edit"></i></a></div></div>';
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
