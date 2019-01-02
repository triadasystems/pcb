 @extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="conexiones" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Conexiones') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updateCon', $rdbms[0]->id) }}">
                        @method('PUT')
                        @csrf
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
                                <label for="name" class="col-lg-12 col-form-label">{{ __('Nombre') }}</label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{  $rdbms[0]->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            @if($rdbms[0]->rdbms_type)
                                @switch($rdbms[0]->rdbms_type)
                                    @case(1)
                                        @php 
                                            $selectedO = 'selected';
                                            $selectedS = '';
                                            $selectedM = '';
                                        @endphp
                                        @break

                                    @case(2)
                                        @php 
                                            $selectedO = '';
                                            $selectedS = 'selected';
                                            $selectedM = '';
                                        @endphp
                                        @break

                                    @case(4)
                                        @php 
                                            $selectedO = '';
                                            $selectedS = '';
                                            $selectedM = 'selected';
                                        @endphp
                                        @break

                                    @default
                                        @php 
                                            $selectedO = '';
                                            $selectedS = '';
                                            $selectedM = '';
                                        @endphp
                                        @break
                                @endswitch
                            @endif
                            <div class="col-md-6">
                                <label for="rdbms_type" class="col-lg-12 col-form-label">{{ __('Manejador de Base de Datos') }}</label>
                                <select id="rdbms_type" class="form-control{{ $errors->has('rdbms_type') ? ' is-invalid' : '' }}" name="rdbms_type" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1" {{ $selectedO }}>Oracle</option>
                                    <option value="2" {{ $selectedS }}>SQL Server</option>
                                    <option value="4" {{ $selectedM }}>MySQL</option>
                                </select>
                                @if ($errors->has('rdbms_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rdbms_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="hostname" class="col-lg-12 col-form-label">{{ __('Hostname') }}</label>
                                <input id="hostname" type="text" class="form-control{{ $errors->has('hostname') ? ' is-invalid' : '' }}" name="hostname" value="{{  $rdbms[0]->hostname }}" required autofocus>

                                @if ($errors->has('hostname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('hostname') }}</strong>
                                    </span>
                                @endif
                            </div>
                            @php 
                                $selectedS = '';
                                $selectedN = 'selected';
                            @endphp
                            @if($rdbms[0]->sox)
                                @switch($rdbms[0]->sox)
                                    @case(1)
                                        @php 
                                            $selectedS = 'selected';
                                            $selectedN = '';
                                        @endphp
                                        @break

                                    @case(0)
                                        @php 
                                            $selectedS = '';
                                            $selectedN = 'selected';
                                        @endphp
                                        @break
                                @endswitch
                            @endif
                            <div class="col-md-6">
                                <label for="sox" class="col-lg-12 col-form-label">{{ __('SOX') }}</label>
                                <select id="sox" class="form-control{{ $errors->has('sox') ? ' is-invalid' : '' }}" name="sox" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1" {{ $selectedS }}>Si</option>
                                    <option value="0" {{ $selectedN }}>No</option>
                                </select>
                                @if ($errors->has('sox'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sox') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="ip_address" class="col-lg-12 col-form-label">{{ __('Dirección IP') }}</label>
                                <input id="ip_address" type="text" class="form-control{{ $errors->has('ip_address') ? ' is-invalid' : '' }}" name="ip_address" value="{{  $rdbms[0]->ip_address }}" required autofocus>

                                @if ($errors->has('ip_address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ip_address') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="port" class="col-lg-12 col-form-label">{{ __('Puerto') }}</label>
                                <input id="port" type="text" class="form-control{{ $errors->has('port') ? ' is-invalid' : '' }}" name="port" value="{{  $rdbms[0]->port }}" required autofocus>
                                @if ($errors->has('port'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('port') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="db_name" class="col-lg-12 col-form-label">{{ __('Nombre DB') }}</label>
                                <div class="input-group">
                                    <input id="db_name" type="password" class="form-control{{ $errors->has('db_name') ? ' is-invalid' : '' }}" name="db_name" value="{{  $rdbms[0]->db_name }}" required autofocus>
                                    <span class="input-group-btn">
                                        <button type="button" data-tipo="name" class="btn btn-default btn-eye"><i class="far fa-eye" id="icon-name"></i></button>
                                    </span>
                                </div>

                                @if ($errors->has('db_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('db_name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="db_user" class="col-lg-12 col-form-label">{{ __('Usuario DB') }}</label>
                                <div class="input-group">
                                    <input id="db_user" type="password" class="form-control{{ $errors->has('db_user') ? ' is-invalid' : '' }}" name="db_user" value="{{  $rdbms[0]->db_user }}" required autofocus>
                                    <span class="input-group-btn">
                                        <button type="button" data-tipo="user" class="btn btn-default btn-eye"><i class="far fa-eye" id="icon-user"></i></button>
                                    </span>
                                </div>
                                
                                @if ($errors->has('db_user'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('db_user') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="db_psw" class="col-lg-12 col-form-label">{{ __('Password DB') }}</label>
                                <div class="input-group">
                                    <input id="db_psw" type="password" class="form-control{{ $errors->has('db_psw') ? ' is-invalid' : '' }}" name="db_psw" value="{{  $rdbms[0]->db_psw }}" required autofocus>
                                    <!--<span class="input-group-btn">
                                        <button type="button" data-tipo="psw" class="btn btn-default btn-eye"><i class="far fa-eye" id="icon-psw"></i></button>
                                    </span>-->
                                </div>

                                @if ($errors->has('db_psw'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('db_psw') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 text-right">
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
            var url = "{{ URL::to('/conexiones/lista') }}";
            
            $( location ).attr("href", url);
        });
        $('#tetsconexion').on('click', function() {
            testConexion();
        });
        $('form').on('submit', function() {
            $('select').removeAttr('disabled');
        });
    });
    function testConexion() {
        $('input').removeAttr('readonly');
        $('select').removeAttr('disabled');
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
            url: '{{ route("testConexion") }}',
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
                    text: 'La conexión fue exitosa.',
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
                    $('#guardarCon').removeAttr('disabled');
                });
                
            } else if(response == "failed") {
                swal({
                    title: 'Advertencia',
                    text: 'No se ha podido realizar la conexión. Por favor verifique los datos ingresados.',
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