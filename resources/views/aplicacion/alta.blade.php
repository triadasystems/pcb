@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcsaplicacion"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Aplicación</div>
                <div class="card-body">
                    <form method="post" action="{{route('appCreate')}}">
                    @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="nombre" class="col-lg-12 col-form-label">Nombre de la Aplicación</label>
                                <input id="nombre" name="nombre" type="txt" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" required autofocus/>
                                @if ($errors->has('nombre'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="nombre" class="col-lg-12 col-form-label">Alias de la Aplicación</label>
                                <input id="alias" name="alias" type="txt" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" value="{{ old('alias') }}" required autofocus/>
                                @if ($errors->has('alias'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('alias') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <a href="{{route('laplicacion')}}" id="regresar" class="btn btn-warning">Regresar</a>
                            <input class="btn btn-primary" id="registrar" type="submit" value="Registrar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection