@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="perfiles" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Perfil</div>
                
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="post" action="{{route('store_perfil')}}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="profilename" class="col-lg-12 col-form-label">{{ __('Nombre') }}</label>
                                <input id="profilename" type="text" class="form-control{{ $errors->has('profilename') ? ' is-invalid' : '' }}" name="profilename" value="{{ old('profilename') }}" required autofocus>
                                <!-- <input name="profilename" type="text" class="form-control" id="inputEmail4" placeholder="Usuario"> -->
                                @if ($errors->has('profilename'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('profilename') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="col-lg-12 col-form-label">{{ __('Descripción') }}</label>
                                <textarea id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required autofocus>{{ old('description') }}</textarea>
                                
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <label class="col-lg-12 col-form-label">&nbsp;</label>
                            <a href="{{route('index_perfil')}}" id="regresar" class="btn btn-warning">Regresar</a>
                            <input class="btn btn-primary" type="submit" value="Registrar">
                        </div>
                        <!-- <table class="table">
                            <thead class="thead-hover">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input name="profilename" type="text" class="form-control" id="inputEmail4" placeholder="Usuario">
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
                                    <td colspan="3">
                                        <input type="hidden" name="status" value="Activo">
                                        <textarea class="form-control" name="description" placeholder="Descripcion"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td><td></td>
                                    <td><a href="{{route('index_perfil')}}" id="regresar" class="btn btn-warning">Regresar</a></td>
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

