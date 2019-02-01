@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="emails" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar correo</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="container">
                        <div class="container">
                            <form method="post" action="{{route('mails.update', $mail->id)}}">
                                @csrf
                                @method('put')
                                <input name="id" id="id" type="hidden" value="{{ $mail->id }}">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="correo" class="col-lg-12 col-form-label">{{ __('E-mail') }}<span style="color: red;">*</span></label>
                                        <input id="correo" type="email" class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}" name="correo" value="{{ old('correo') ? old('correo') : $mail->correo }}" required autofocus>

                                        @if ($errors->has('correo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('correo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <small><strong>Seleccione el tipo de correo que podrá recibir esta cuenta</strong></small>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        @php
                                            $checkedA = '';
                                        @endphp
                                        @if(old('automatizacion') == 1 || $mail->automatizacion == 1)
                                            @php
                                                $checkedA = 'checked';
                                            @endphp
                                        @endif
                                        <input id="automatizacion" type="checkbox" class="form-control{{ $errors->has('automatizacion') ? ' is-invalid' : '' }}" name="automatizacion" value="1" {{$checkedA}} autofocus>
                                        <label for="automatizacion" class="col-lg-10 col-form-label">{{ __('Conciliación') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $checkedB = '';
                                        @endphp
                                        @if(old('bajas') == 1 || $mail->bajas == 1)
                                            @php
                                                $checkedB = 'checked';
                                            @endphp
                                        @endif
                                        <input id="bajas" type="checkbox" class="form-control{{ $errors->has('bajas') ? ' is-invalid' : '' }}" name="bajas" value="1" {{$checkedB}} autofocus>
                                        <label for="bajas" class="col-lg-10 col-form-label">{{ __('Bajas') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $checkedTcsB = '';
                                        @endphp
                                        @if(old('tcs_terceros_baja') == 1 || $mail->tcs_terceros_baja == 1)
                                            @php
                                                $checkedTcsB = 'checked';
                                            @endphp
                                        @endif
                                        <input id="tcs_terceros_baja" type="checkbox" class="form-control{{ $errors->has('tcs_terceros_baja') ? ' is-invalid' : '' }}" name="tcs_terceros_baja" value="1" {{$checkedTcsB}} autofocus>
                                        <label for="tcs_terceros_baja" class="col-lg-10 col-form-label">{{ __('Baja de Terceros') }}</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        @php
                                            $checkedTcsBAR = '';
                                        @endphp
                                        @if(old('tcs_terceros_baja_auth_resp') == 1 || $mail->tcs_terceros_baja_auth_resp == 1)
                                            @php
                                                $checkedTcsBAR = 'checked';
                                            @endphp
                                        @endif
                                        <input id="tcs_terceros_baja_auth_resp" type="checkbox" class="form-control{{ $errors->has('tcs_terceros_baja_auth_resp') ? ' is-invalid' : '' }}" name="tcs_terceros_baja_auth_resp" value="1" {{$checkedTcsBAR}} autofocus>
                                        <label for="tcs_terceros_baja_auth_resp" class="col-lg-10 col-form-label">{{ __('Baja Autorizador/Respons.') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <a href="{{route('mails.index')}}" id="regresar" class="btn btn-warning">Regresar</a>
                                    <input disabled="disabled" class="btn btn-primary" id="registrar" type="submit" value="Registrar">
                                </div>
                            </form>
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
            var automatizacion = "";
            var bajas = "";
            var tcs_terceros_baja = "";
            var contadorChecks = 0;
            
            if($("#automatizacion").is(':checked')) {
                contadorChecks = contadorChecks+1;
            }
            if($("#bajas").is(':checked')) {
                contadorChecks = contadorChecks+1;
            }
            if($("#tcs_terceros_baja").is(':checked')) {
                contadorChecks = contadorChecks+1;
            }
            if($("#tcs_terceros_baja_auth_resp").is(':checked')) {
                contadorChecks = contadorChecks+1;
            }
            
            if(
                $("#automatizacion").is(':checked') || 
                $("#bajas").is(':checked') || 
                $("#tcs_terceros_baja").is(':checked') ||
                $("#tcs_terceros_baja_auth_resp").is(':checked')
            ) {
                $("#registrar").prop("disabled", false);
            }

            $("#automatizacion").change(function(){
                if($(this).is(':checked')){
                    contadorChecks = contadorChecks+1;
                    if($("#registrar").attr("disabled")) {
                        $("#registrar").prop("disabled", false);
                    }
                } else {
                    contadorChecks = contadorChecks-1;
                    if(contadorChecks == 0) {
                        $("#registrar").prop("disabled", true);
                    }
                }
            });
            $("#bajas").change(function(){
                if($(this).is(':checked')){
                    contadorChecks = contadorChecks+1;
                    if($("#registrar").attr("disabled")) {
                        $("#registrar").prop("disabled", false);
                    }
                } else {
                    contadorChecks = contadorChecks-1;
                    if(contadorChecks == 0) {
                        $("#registrar").prop("disabled", true);
                    }
                }
            });
            $("#tcs_terceros_baja").change(function(){
                if($(this).is(':checked')){
                    contadorChecks = contadorChecks+1;
                    if($("#registrar").attr("disabled")) {
                        $("#registrar").prop("disabled", false);
                    }
                } else {
                    contadorChecks = contadorChecks-1;
                    if(contadorChecks == 0) {
                        $("#registrar").prop("disabled", true);
                    }
                }
            });
            $("#tcs_terceros_baja_auth_resp").change(function(){
                if($(this).is(':checked')){
                    contadorChecks = contadorChecks+1;
                    if($("#registrar").attr("disabled")) {
                        $("#registrar").prop("disabled", false);
                    }
                } else {
                    contadorChecks = contadorChecks-1;
                    if(contadorChecks == 0) {
                        $("#registrar").prop("disabled", true);
                    }
                }
            });
        });
    </script>
@endpush