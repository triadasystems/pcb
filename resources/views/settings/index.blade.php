@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcsconfiguracion"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <form method="POST" action="{{ route('actualizaSub')}}">
        @csrf
            <div class="card">
                <input type="hidden" id="tipo" name="tipo" value="1"/>
                <div class="card-header">Subfijo terceros</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form">Subfijo actual</label>
                                @foreach ($data['sub'] as $val)
                                <input type ="text" class="form-control" name="old" id="old" value="{{ $val->subfijo}}" disabled/>
                                <input type ="hidden" name="old_sub" id="old_sub" value="{{ $val->subfijo}}"/>
                                @endforeach
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form">Subfijo nuevo</label>
                                <input type ="text" class="form-control{{ $errors->has('subfijo_nuevo') ? ' is-invalid' : '' }}" value="{{ old('subfijo_nuevo') }}" name="subfijo_nuevo" id="subfijo_nuevo"/>
                                @if ($errors->has('subfijo_nuevo'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('subfijo_nuevo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="enviar">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>

        </form>
            </div>
        </div>
        <div class="col-md-6">
            <form method="POST" action="{{ route('actualizaSub')}}">
            <input type="hidden" id="tipo" name="tipo" value="2"/>
                @csrf
                <div class="card">
                    <div class="card-header">Periodo de alerta de vencimiento</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Dias del periodo de alerta de vencimiento</label>
                                @foreach ($data['sett'] as $val)
                                <input type ="text" class="form-control{{ $errors->has('dias') ? ' is-invalid' : '' }}" value="{{ $val->settings}}" name="dias" id="dias"/>
                                <input type ="hidden" class="form-control" value="{{ $val->id}}" name="id_dias" id="id_dias"/>
                                @endforeach
                                @if ($errors->has('dias'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('dias') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Descripción de la actualización</label>
                                <textarea class="form-control{{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('dias') }}" name="descripcion" id="descripcion">
                                </textarea>
                                @if ($errors->has('descripcion'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('descripcion') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="enviar">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function (){
    @if (session('confirmacion'))
            swal(
                'El subfijo ha sido actualizado',
                'La operación se ha realizado con éxito',
                'success'
            )
    @endif
    @if (session('confirmacion'))
            swal(
                'La configuración de los dias de alerta ha sido actualizada',
                'La operación se ha realizado con éxito',
                'success'
            )
    @endif
    @if (session('igual'))
            swal(
                'La configuración de los dias de alerta no se ha realizado ya que son los mismos',
                'La operación se ha realizado con éxito',
                'success'
            )
    @endif
});
</script>
@endpush