@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="encrypt" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Herramienta de encriptación') }}</div>

                <div class="card-body">
                    @csrf
                    @if (isset($msjError))
                        <div class="col-md-12 text-center">
                            <span style="width: 100%; margin-top: .25rem; font-size: 80%; color: #e3342f !important;" role="alert">
                                <strong>{{ $msjError }}</strong>
                            </span>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="stringEncrypt" class="col-sm-4 col-form-label text-md-right">{{ __('Coloque el texto a encriptar') }}</label>

                        <div class="col-md-6">
                            <input id="stringEncrypt" type="text" class="form-control{{ $errors->has('stringEncrypt') ? ' is-invalid' : '' }}" name="stringEncrypt" value="{{ old('stringEncrypt') }}" required autofocus>

                            @if ($errors->has('stringEncrypt'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('stringEncrypt') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div id="txt-encriptado">
                        <code>
                            <p id="p-encriptado"></p>
                        </code>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-10 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button type="button" class="btn btn-primary" id="btn-encrypt">
                                {{ __('Aceptar') }}
                            </button>
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
    $('#btn-encrypt').click(function(){
        var text = $('#stringEncrypt').val();
        
        $.ajax({
            type: 'GET',
            url: '{{ route("desEncrypt") }}',
            data: {text: text},
        }).done(function(response){
            swal({
                title: 'Encriptación de texto',
                text: 'El resultado deberá ser copiado y pegado.',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'De acuerdo'
            }).then((result) => {
                // console.log(response);
                $('#p-encriptado').html(response);
            });
        });
    });
    </script>
@endpush