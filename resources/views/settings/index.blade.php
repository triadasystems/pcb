@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcsconfiguracion"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="{{ route('actualizaSub')}}">
                    @csrf
                    <input type="hidden" id="tipo" name="tipo" value="1"/>
                    <div class="card-header">Sufijo terceros</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="container">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="form">Sufijo actual</label>
                                    @foreach ($data['sub'] as $val)
                                    <input type ="text" class="form-control" name="old" id="old" value="{{ $val->subfijo}}" disabled/>
                                    <input type ="hidden" name="old_sub" id="old_sub" value="{{ $val->subfijo}}"/>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <label class="form">Sufijo nuevo</label>
                                    <input type ="text" class="form-control{{ $errors->has('subfijo_nuevo') ? ' is-invalid' : '' }}" value="{{ old('subfijo_nuevo') }}" name="subfijo_nuevo" id="subfijo_nuevo"/>
                                    @if ($errors->has('subfijo_nuevo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subfijo_nuevo') }}</strong>
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
        <div class="col-md-6">
            <form method="POST" action="{{ route('actualizaSub')}}">
                <input type="hidden" id="tipo" name="tipo" value="2"/>
                @csrf
                    <div class="card">
                        <div class="card-header">Configuración</div>
                        <div class="card-body">
                            @php
                                $count = 1;
                                $countRow = count($data['sett']);
                            @endphp
                            @foreach($data['sett'] as $row)
                                @php
                                    $errorEVP = "config[".$count."][".$row['name']."]";
                                @endphp
                                @if($count % 2 != 0)
                                    <div class="form-group row">
                                @endif
                                        <div class="col-md-6">
                                            <label>{{ $row["name_large"] }}</label>
                                            <input type ="{{ $row['type_input_html'] }}" class="form-control{{ $errors->has($errorEVP) ? ' is-invalid' : '' }}" value="{{ $row['settings'] }}" name="config[{{ $count }}][{{ $row['name'] }}]" id="{{ $row['name'] }}"/>
                                            <input type ="hidden" value="{{ $row['id'] }}" name="config[{{ $count }}][id]" id="id_{{ $row['name'] }}"/>
                                            <input type ="hidden" value="{{ $row['name'] }}" name="config[{{ $count }}][name]" id="name_{{ $row['name'] }}"/>
                                            <input type ="hidden" value="{{ $row['type_input_html'] }}" name="config[{{ $count }}][type_input_html]" id="input_html_{{ $row['name'] }}"/>
                                            
                                            @if ($errors->has($errorEVP))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first($row['name']) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                @if($count % 2 == 0 || $count == $countRow)
                                    </div>
                                @endif
                                
                                @php
                                    $count = $count+1;
                                @endphp
                            
                            @endforeach
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