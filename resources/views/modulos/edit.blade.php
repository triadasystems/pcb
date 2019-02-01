@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="modulos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Módulo</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="container">
                        <div class="container">
                            <form method="post" action="{{route('modulos.update', $modulo->id)}}">
                                @csrf
                                @method('put')
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="modulename" class="col-lg-12 col-form-label">{{ __('Nombre') }}<span style="color: red;">*</span></label>
                                        <input id="modulename" type="text" class="form-control{{ $errors->has('modulename') ? ' is-invalid' : '' }}" name="modulename" value="{{ $modulo->modulename }}" required autofocus>
                                        
                                        @if ($errors->has('modulename'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('modulename') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="col-lg-12 col-form-label">{{ __('Estatus') }}</label>
                                        <select id="status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status" required>
                                            <option value="">Seleccione...</option>
                                            @if($modulo->status=="Activo")
                                                <option value="Activo" selected>Activo</option>
                                                <option value="Inactivo">Inactivo</option>
                                            @else
                                                <option value="Activo">Activo</option>
                                                <option value="Inactivo" selected>Inactivo</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="description" class="col-lg-12 col-form-label">{{ __('Descripción') }}<span style="color: red;">*</span></label>
                                        <textarea id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required autofocus>{{ $modulo->description }}</textarea>
                                        
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <label class="col-lg-12 col-form-label">&nbsp;</label>
                                    <a href="{{route('modulos.index')}}" id="regresar" class="btn btn-warning">Regresar</a>
                                    <input class="btn btn-primary" type="submit" value="Registrar">
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

