@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcslistaactivos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('ALTA DE USUARIOS EXTERNOS')}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('terceros.create')}}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label>{{ __('Tipo de solicitud')}}</label><br>
                            <select id="t_solicitud" class="form-control">
                                <option>{{ __('Tipo de solicitud')}}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('No. de FUS/RFC')}}</label><br>
                            <input type="text" id="fus" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>{{'Mesa de control'}}</label><br>
                            <select id="mesa" class="form-control">
                                <option>{{ __('Mesa de control')}}</option>
                            </select>
                        </div>
                    </div>
                    <label>Datos del usuario</label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name" class="col-md-8 col-form-label text-md-left">{{ __('Nombre')}}</label><br>
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                        <div class="col-md-4">
                            <label for="paterno" class="col-md-8 col-form-label text-md-rigth">{{ __('Apellido paterno')}}</label>
                            <input id="a_paterno" type="text" class="form-control{{ $errors->has('a_paterno') ? ' is-invalid' : '' }}" name="a_paterno" value="{{ old('a_paterno') }}" required autofocus>
                        </div>
                        <div class="col-md-4">
                            <label for="materno" class="col-md-8 col-form-label text-md-rigth">{{ __('Apellido materno')}}</label>
                            <input id="a_materno" type="text" class="form-control{{ $errors->has('a_materno') ? ' is-invalid' : '' }}" name="a_materno" value="{{ old('a_materno') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Fecha inicial : </label>
                            <input class = "date form-control" type = "date" id="fecha_ini">
                        </div>
                        <div class="col-md-6">
                            <label>Fecha final :</label>
                            <input class="date form-control" type = "date" id="fecha_fin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="provedor" class="col-md-12 col-form-label text-md-left">{{ __('Empresa a la que pertenece')}}</label><br>
                            <select id="empresa" class="form-control">
                                <option value="0">Selecciona la empresa</option>
                                @foreach ($empresa as $val)
                                <option value="{{ $val['id']}}">{{ $val
                                ['name']}}</option>                                
                                @endforeach
                            </select>
                            <input id="empresa" type="text" class="form-control {{ $errors->has('empresa') ? ' is-invalid' : '' }}" name="empresa" value="{{ old('empresa') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __('No. de gafete')}}</label><br>
                            <input type="text" id="gafete" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Usuario de red/e-mail')}}</label><br>
                            <input type="text" id="correo" class="form-control">
                        </div>
                    </div>
                    <label>{{ __('Autorizador')}}</label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __('Número de empleado del autorizador')}}</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Nombre completo del autorizador')}}</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <label>{{ __('Responsable')}}</label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __('Número de empleado del responsable')}}</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Nombre completo del responsable')}}</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </form>
                <button type="submit" class="btn btn-primary" id="enviar">Agregar</button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
