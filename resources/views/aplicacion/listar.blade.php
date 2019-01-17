@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="tcsaplicacion" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Administración de Aplicaciones
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaTerceros">Alta de Aplicaciones</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="aplicaciones-table" name = "aplicaciones-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Aplicación</th>
                                    <th>Alias</th>
                                    <th>Estatus</th>
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
    var table = $('#aplicaciones-table').DataTable({
        language:{
            url: "{{ asset('json/Spanish.json') }}"
        },
        procesing: true,
        serverSide: true,
        ajax: '{!! route("aplicacion.lista") !!}',
        columns:[
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'alias', name: 'alias'},
            { data: 'active', name: 'active'}
        ]
    });
});
</script>
@endpush