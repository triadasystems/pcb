@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="permisos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Registrar Permiso</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="post" action="{{route('permisos.store')}}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="profilename" class="col-lg-12 col-form-label">{{ __('Nombre') }}</label>
                                <select name="profilename" class="form-control{{ $errors->has('profilename') ? ' is-invalid' : '' }}" name="profilename" required autofocus>
                                    <option value="">Elegir perfil</option>
                                    @foreach($usuarios as $registro)
                                    <option value="{{$registro->id}}">{{$registro->profilename}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('profilename'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('profilename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                @php
                                    $checked = '';
                                @endphp
                                @if(old('reading') == 1)
                                    @php
                                        $checked = 'checked';
                                    @endphp
                                @endif
                                <input type="checkbox" class="form-control{{ $errors->has('reading') ? ' is-invalid' : '' }}" {{ $checked }} value="1" name="reading" autofocus>
                                <label for="reading" class="col-lg-6 col-form-label">
                                    {{ __('Lectura') }}
                                </label>

                                @if ($errors->has('reading'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('reading') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @php
                                    $checked = '';
                                @endphp
                                @if(old('writing') == 1)
                                    @php
                                        $checked = 'checked';
                                    @endphp
                                @endif
                                <input type="checkbox" class="form-control{{ $errors->has('writing') ? ' is-invalid' : '' }}" {{ $checked }} value="1" name="writing" autofocus>
                                <label for="writing" class="col-lg-6 col-form-label">
                                    {{ __('Escritura') }}
                                </label>

                                @if ($errors->has('writing'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('writing') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @php
                                    $checked = '';
                                @endphp
                                @if(old('upgrade') == 1)
                                    @php
                                        $checked = 'checked';
                                    @endphp
                                @endif
                                <input type="checkbox" class="form-control{{ $errors->has('upgrade') ? ' is-invalid' : '' }}" {{ $checked }} value="1" name="upgrade" autofocus>
                                <label for="upgrade" class="col-lg-6 col-form-label">
                                    {{ __('Actualización') }}
                                </label>

                                @if ($errors->has('upgrade'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('upgrade') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @php
                                    $checked = '';
                                @endphp
                                @if(old('send_email') == 1)
                                    @php
                                        $checked = 'checked';
                                    @endphp
                                @endif
                                <input type="checkbox" class="form-control{{ $errors->has('send_email') ? ' is-invalid' : '' }}" {{ $checked }} value="1" name="send_email" autofocus>
                                <label for="send_email" class="col-lg-6 col-form-label">
                                    {{ __('Envío E-mail') }}
                                </label>

                                @if ($errors->has('send_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('send_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                @php
                                    $checked = '';
                                @endphp
                                @if(old('execution') == 1)
                                    @php
                                        $checked = 'checked';
                                    @endphp
                                @endif
                                <input type="checkbox" class="form-control{{ $errors->has('execution') ? ' is-invalid' : '' }}" {{ $checked }} value="1" name="execution" autofocus>
                                <label for="execution" class="col-lg-6 col-form-label">
                                    {{ __('Ejecución') }}
                                </label>

                                @if ($errors->has('execution'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('execution') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <label class="col-lg-12 col-form-label">&nbsp;</label>
                            <a href="{{route('permisos.index')}}" id="regresar" class="btn btn-warning">Regresar</a>
                            <input class="btn btn-primary" type="submit" value="Registrar">
                        </div>

                        <!-- <table class="table">
                            <thead class="thead-hover">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Lectura</th>
                                    <th scope="col">Escritura</th>
                                    <th scope="col">Actualizar</th>
                                    <th scope="col">Enviar correos</th>
                                    <th scope="col">Ejecución</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="profilename" class="form-control" id="exampleFormControlSelect1" required="">
                                            <option value="">Elegir perfil</option>
                                            @foreach($usuarios as $registro)
                                            <option value="{{$registro->id}}">{{$registro->profilename}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->any())
                                        <div class="alert alert-white">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                <small><li class="text-danger">{{ $error }}</li></small>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input name="reading" class="form-check-input" type="radio" value=1 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Permitido
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="reading" class="form-check-input" type="radio" value=0 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                No Permitido
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input name="writing" class="form-check-input" type="radio" value=1 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Permitido
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="writing" class="form-check-input" type="radio" value=0 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                No Permitido
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input name="upgrade" class="form-check-input" type="radio" value=1 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Permitido
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="upgrade" class="form-check-input" type="radio" value=0 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                No Permitido
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input name="send_mail" class="form-check-input" type="radio" value=1 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Permitido
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="send_mail" class="form-check-input" type="radio" value=0 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                No Permitido
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input name="execution" class="form-check-input" type="radio" value=1 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                Permitido
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input name="execution" class="form-check-input" type="radio" value=0 checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                No Permitido
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td><td></td><td></td>
                                    <td><a href="{{route('permisos.index')}}" id="regresar" class="btn btn-warning">Regresar</a></td>
                                    <td><input class="btn btn-primary" type="submit" value="Registrar"></td>
                                </tr>
                            </tbody>
                        </table> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection