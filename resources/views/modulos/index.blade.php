@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="modulos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Módulos</div>

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
                                <a class="btn btn-success" href="{{route('modulos.create')}}">Registrar módulo</a>
                            </div>
                        </div>
                        <div class="container table-responsive">
                            <table class="table table-bordered" id="modulos">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Nombre modulo</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Editar</th>
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
                'Modulo registrado',
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
        var table = $('#modulos').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}"
                    // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route("consultatodomodulos.data") !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'modulename', name: 'modulename' },
                { data: 'status', name: 'status' },
                { data: 'description', name: 'description' },
                {
                    targets: - 1,
                        render: function (data, type, row) {
                        return '<div class="row"><div class="col-lg-5 text-center"><a class="btn btn-primary" href="modulos/' + row.id + '/edit">Editar <i class="fas fa-edit"></i></a></div></div>';
                    }
                }
            ]
        });
    });
</script>
@endpush