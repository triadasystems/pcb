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
                                <table class="table">
                                    <thead class="thead-hover">
                                        <tr>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Conciliación</th>
                                            <th scope="col">Bajas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input name="correo" type="email" class="form-control" id="inputEmail4" placeholder="Correo" value="{{$mail->correo}}">
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
                                                @if($mail->automatizacion==0)
                                                <div class="form-check">
                                                    <input name="automatizacion" class="form-check-input" type="radio" value=1>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        Permitido
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="automatizacion" class="form-check-input" type="radio" value=0 checked>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        No Permitido
                                                    </label>
                                                </div>
                                                @else
                                                <div class="form-check">
                                                    <input name="automatizacion" class="form-check-input" type="radio" value=1 checked>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        Permitido
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="automatizacion" class="form-check-input" type="radio" value=0 >
                                                    <label class="form-check-label" for="gridRadios1">
                                                        No Permitido
                                                    </label>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($mail->bajas==0)
                                                <div class="form-check">
                                                    <input name="bajas" class="form-check-input" type="radio" value=1>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        Permitido
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="bajas" class="form-check-input" type="radio" value=0 checked>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        No Permitido
                                                    </label>
                                                </div>
                                                @else
                                                <div class="form-check">
                                                    <input name="bajas" class="form-check-input" type="radio" value=1 checked>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        Permitido
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input name="bajas" class="form-check-input" type="radio" value=0 >
                                                    <label class="form-check-label" for="gridRadios1">
                                                        No Permitido
                                                    </label>
                                                </div>

                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><a id="regresar" href="{{route('mails.index')}}" class="btn btn-warning">Regresar</a></td>
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