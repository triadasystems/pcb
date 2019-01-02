@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="relacionpm"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Relación módulo perfil</div>
                @csrf
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
                                <a class="btn btn-success" href="{{route('relacionpm.create')}}">Registrar relacion M-P</a>
                            </div>
                        </div>
                        <div class="container">
                            <table class="table table-bordered" id="relacionpm">
                                <thead>
                                    <tr>
                                        <th scope="col">Perfil</th>
                                        <th scope="col">Modulos</th>
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
    $(document).ready(function () {
        @if(session('vacio'))
            swal(
                'Advertencia',
                'Actualmente no existen perfiles a los que no se les haya asignado módulos',
                'warning'
            )
        @endif
        @if(session('confirmacion'))
            swal(
                'Asignación agregada',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif
        @if(session('actualizo'))
            swal(
                'Actualización',
                'La operación se ha realizado con éxito',
                'success'
            )
        @endif
        
        var table = $('#relacionpm').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}"
                        // url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route("consultatodorelacionpm.data") !!}',
            columns: [
                {data: 'nombre', name: 'perfil'},
                {data: 'info', name: 'modulo'},
                {
                    targets: -1,
                    render: function (data, type, row) {
                        return '<div class="row"><div class="col-lg-5 text-center"><a class="btn btn-primary" href="relacionpm/' + row.id + '/edit">Editar <i class="fas fa-edit"></i></a></div></div>';
                    }
                }
            ]
        });
    });
</script>
@endpush
