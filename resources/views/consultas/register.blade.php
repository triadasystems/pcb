@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="conexiones" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Consulta') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('store_consultas') }}">
                        @csrf
                        <input type="hidden" name="rdbms_id" value="{{ $id }}" />
                        <?php
                            // if (isset($_GET["msjError"])) {
                            //     echo '<div class="col-md-12 text-center">
                            //         <span style="width: 100%; margin-top: .25rem; font-size: 80%; color: #e3342f !important;" role="alert">
                            //             <strong>'.$_GET["msjError"].'</strong>
                            //         </span>
                            //     </div>';
                            // }
                        ?>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="application_id" class="col-lg-12 col-form-label">{{ __('Aplicaciones') }}</label>
                                <select id="application_id" class="form-control{{ $errors->has('application_id') ? ' is-invalid' : '' }}" name="application_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($applications as $row)
                                        <option value="{{ $row['id'] }}">{{ utf8_encode($row['name']) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('application_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('application_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="select_in" class="col-lg-12 col-form-label">{{ __('Tipo de Consulta') }}</label>
                                <select id="select_in" class="form-control{{ $errors->has('select_in') ? ' is-invalid' : '' }}" name="select_in" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1">Aplicativos</option>
                                    <option value="2">Labora</option>
                                    <option value="3">Bajas</option>
                                </select>
                                @if ($errors->has('select_in'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('select_in') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="qry_read" class="col-lg-12 col-form-label">{{ __('Consulta (Query)') }}</label>
                                <!-- <input id="qry_read" type="text" class="form-control{{ $errors->has('qry_read') ? ' is-invalid' : '' }}" name="qry_read" value="{{ old('qry_read') }}" required autofocus> -->
                                <textarea id="qry_read" type="text" class="form-control{{ $errors->has('qry_read') ? ' is-invalid' : '' }}" name="qry_read" required autofocus>{{ old('qry_read') }}</textarea>
                                @if ($errors->has('qry_read'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('qry_read') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-lg-12 col-form-label">&nbsp;</label>
                                <button type="button" class="btn btn-warning" id="regresar">
                                    {{ __('Regresar') }}
                                </button>
                                <button type="button" class="btn btn-success" id="tetsconexion">
                                    {{ __('Test Conexión') }}
                                </button>
                                <button type="submit" class="btn btn-primary" id="guardarCon" disabled>
                                    {{ __('Guardar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        @if(session('errorMsj'))
            swal(
                'Error',
                '{{ session("errorMsj") }}',
                'error'
            )
        @endif
        $('#regresar').on('click', function(){
            var url = "{{ URL::to('/conexiones/consultas/lista') }}/{{$id}}";
            
            $( location ).attr("href", url);
        });
        $('#tetsconexion').on('click', function() {
            testConexion();
        });
        $('form').on('submit', function() {
            $('select').removeAttr('disabled');
            $('textarea').removeAttr('disabled');
        });
    });
    function testConexion() {
        $('input').removeAttr('readonly');
        $('select').removeAttr('disabled');
        $('textarea').removeAttr('disabled');
        $('#guardarCon').attr('disabled');
        var data = $("form").serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            data: {data: data},
            url: '{{ route("testConsulta") }}',
            async: true,
            beforeSend: function(){
                mostrarLoadingMail();
            },
            complete: function(){
                ocultarLoadingMail();
            }
        }).done(function(response){
            if (response == "success") {
                swal({
                    title: 'Listo',
                    text: 'Validación exitosa.',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'De acuerdo'
                }).then((result) => {
                    $('input').attr('readonly', true);
                    $('select').attr('disabled', true);
                    $('textarea').attr('disabled', true);
                    $('#guardarCon').removeAttr('disabled');
                });
                
            } else if(response == "failed") {
                swal({
                    title: 'Advertencia',
                    text: 'No se ha podido realizar la conexión. Por favor verifique los datos de conexión.',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'De acuerdo'
                // }).then((result) => {
                //     $('input').removeAttr('readonly');
                //     $('select').removeAttr('disabled');
                //     $('#guardarCon').attr('disabled');
                });
            } else if(response == "odbc") {
                swal({
                    title: 'Advertencia',
                    text: 'Se ha detectado una conexión por ODBC, la cual no puede ser testeada. Se permitirá la inserción de la consulta, sin embargo, se recomienda validar dicha consulta por otros medios.',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'De acuerdo'
                }).then((result) => {
                    $('input').attr('readonly', true);
                    $('select').attr('disabled', true);
                    $('textarea').attr('disabled', true);
                    $('#guardarCon').removeAttr('disabled');
                });
            }
        }).fail( function( jqXHR, textStatus, errorThrown ) {
            swal({
                title: 'Advertencia',
                text: 'Ha ocurrido un error, por favor verifique los datos de conexión. Si el problema persiste contacte al administrador.',
                type: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'De acuerdo'
            // }).then((result) => {
            //     $('input').removeAttr('readonly');
            //     $('select').removeAttr('disabled');
            //     $('#guardarCon').attr('disabled');
            });
        });
    }
</script>
@endpush