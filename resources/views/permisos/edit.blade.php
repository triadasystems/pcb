@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="permisos" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar Permiso</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="container">
                        <div class="container">
                            <form method="post" action="{{route('permisos.update', $permiso->id)}}">
                                @csrf
                                @method('put')
                                <table class="table">
                                    <thead class="thead-hover">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Lectura</th>
                                            <th scope="col">Escritura</th>
                                            <th scope="col">Actualizar</th>
                                            <th scope="col">Enviar correos</th>
                                            <th scope="col">Ejecución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="col">{{$permiso->id}}<input name="id" type="hidden" value="{{$permiso->id}}">@if ($errors->any())
                                                <div class="alert alert-white">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                        <small><li class="text-danger">{{ $error }}</li></small>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </th>
                                            <td>
                                                <select name="reading" class="form-control" id="exampleFormControlSelect1">
                                                    @if($permiso->reading == 1)
                                                    <option value="{{$permiso->reading}}">Activado</option>
                                                    <option value="0"> Inactivo</option>
                                                    @elseif($permiso->reading == 0)
                                                    <option value="{{$permiso->reading}}">Inactivado</option>
                                                    <option value="1"> Activo</option>
                                                    @endif
                                                </select>

                                            </td>
                                            <td>
                                                <select name="writing" class="form-control" id="exampleFormControlSelect1">
                                                    @if($permiso->writing == 1)
                                                    <option value="{{$permiso->writing}}" selected>Activado</option>
                                                    <option value="0"> Inactivo</option>
                                                    @elseif($permiso->writing == 0)
                                                    <option value="{{$permiso->writing}}">Inactivado</option>
                                                    <option value="1"> Activo</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <select name="upgrade" class="form-control" id="exampleFormControlSelect1">
                                                    @if($permiso->upgrade == 1)
                                                    <option value="{{$permiso->upgrade}}" selected>Activado</option>
                                                    <option value="0"> Inactivo</option>
                                                    @elseif($permiso->upgrade == 0)
                                                    <option value="{{$permiso->upgrade}}" selected>Inactivado</option>
                                                    <option value="1"> Activo</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <select name="send_email" class="form-control" id="exampleFormControlSelect1">
                                                    @if($permiso->send_email == 1)
                                                    <option value="{{$permiso->send_email}}" selected>Activado</option>
                                                    <option value="0"> Inactivo</option>
                                                    @elseif($permiso->send_email == 0)
                                                    <option value="{{$permiso->send_email}}" selected>Inactivado</option>
                                                    <option value="1"> Activo</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <select name="execution" class="form-control" id="exampleFormControlSelect1">
                                                    @if($permiso->execution == 1)
                                                    <option value="{{$permiso->execution}}" selected>Activado</option>
                                                    <option value="0"> Inactivo</option>
                                                    @elseif($permiso->execution == 0)
                                                    <option value="{{$permiso->execution}}" selected>Inactivado</option>
                                                    <option value="1"> Activo</option>
                                                    @endif
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td><td></td><td></td><td></td>
                                            <td><a href="{{route('permisos.index')}}" id="regresar" class="btn btn-warning">Regresar</a></td>
                                            <td><input class="btn btn-primary" type="submit" value="Actualizar"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection