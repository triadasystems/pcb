@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo"  value="fuses"/>
<input type="hidden" id="formAjax" value="1" />
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de Fuses
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>No. FUS</th>
                                        <th>Nombre / #</th>
                                        <th>Cargo</th>
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
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    @if (session('confirmacion'))
            swal(
                'FUS registrado',
                'La operación se ha realizado con éxito.',
                'success'
            )
        @endif
        var table=$('#fus-table').DataTable({
        language: {
                url: "{{ asset('json/Spanish.json') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route("fus.data", $id) !!}',
            columns: [
                {data: 'fus', name: 'fus'},
                {data: 'datos_fus', name: 'datos_fus'},
                {data: 'tipo', name: 'tipo'},
                {data: 'descripcion', name: 'descripcion'}
            ]
    });
});
</script>
@endpush