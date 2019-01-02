@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="relacionpm"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Relacion modulo perfil</div>
                
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="post" action="{{route('relacionpm.store')}}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="profiles_users_id" class="col-lg-12 col-form-label">{{ __('Estatus') }}</label>
                                <select id="profiles_users_id" class="form-control{{ $errors->has('profiles_users_id') ? ' is-invalid' : '' }}" name="profiles_users_id" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($perfiles as $row)
                                        <option value="{{ $row->id }}">{{ $row->profilename }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('profiles_users_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('profiles_users_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-lg-12 col-form-label">{{ __('Módulos') }}</label>
                                @php
                                    $htmlErrorModules = '<div class="col-md-12">';
                                @endphp
                                @foreach($modulos as $registro)
                                    @if($errors->has($registro->modulename))
                                        @php
                                            $htmlErrorModules .= '<span class="invalid-feedback" style="display:block !important;" role="alert"><strong>';
                                            $errores = json_decode($errors, true);
                                            if(isset($errores["$registro->modulename"])) {
                                                foreach($errores["$registro->modulename"] as $err) {
                                                    $htmlErrorModules .= $err;
                                                }
                                            }
                                            $htmlErrorModules .= '</strong></span>';
                                        @endphp
                                    @endif
                                    <div class="col-md-4">
                                        @php
                                            $checked = '';
                                        @endphp
                                        @if(old('execution') == 1)
                                            @php
                                                $checked = 'checked';
                                            @endphp
                                        @endif
                                        <input type="checkbox" class="form-control{{ $errors->has('execution') ? ' is-invalid' : '' }}" {{ $checked }} value="{{ $registro->id }}" name="{{ $registro->modulename }}" autofocus>
                                        <label for="{{ $registro->modulename }}" class="col-lg-8 col-form-label">
                                            {{ $registro->modulename }}
                                        </label>
                                    </div>
                                @endforeach
                                @php
                                    $htmlErrorModules .= '</div>';
                                    echo $htmlErrorModules;
                                @endphp
                        </div>
                        <div class="col-md-12 text-right">
                            <label class="col-lg-12 col-form-label">&nbsp;</label>
                            <a href="{{route('relacionpm.index')}}" id="regresar" class="btn btn-warning">Regresar</a>
                            <input class="btn btn-primary" type="submit" value="Registrar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection