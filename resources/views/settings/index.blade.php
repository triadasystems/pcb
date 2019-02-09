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
                    <div class="card-header">Sufijo Terceros</div>
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
                                    <input type ="text" class="form-control{{ $errors->has('subfijo') ? ' is-invalid' : '' }}" value="{{ old('subfijo') }}" name="subfijo" id="subfijo"/>
                                    @if ($errors->has('subfijo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subfijo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
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
                                $errorEVP = "config.".$count.".".$row["name"];
                                $minTypeNumber = '';
                            @endphp

                            @if($row['type_input_html'] == "number")
                                @php
                                    $minTypeNumber = 'min=1 step=1';
                                @endphp
                            @endif
                            @if($count % 2 != 0)
                                <div class="form-group row">
                            @endif
                                    <div class="col-md-6">
                                        <label>{{ $row["name_large"] }}</label>
                                        <input {{ $minTypeNumber }} type ="{{ $row['type_input_html'] }}" data-ejemplo="{{ $errors->has($errorEVP) }}" class="form-control{{ $errors->has($errorEVP) ? ' is-invalid' : '' }}" value="{{ $row['settings'] }}" name="config[{{ $count }}][{{ $row['name'] }}]" id="{{ $row['name'] }}"/>
                                        <input type ="hidden" value="{{ $row['id'] }}" name="config[{{ $count }}][id]" id="id_{{ $row['name'] }}"/>
                                        <input type ="hidden" value="{{ $row['name'] }}" name="config[{{ $count }}][name]" id="name_{{ $row['name'] }}"/>
                                        <input type ="hidden" value="{{ $row['type_input_html'] }}" name="config[{{ $count }}][type_input_html]" id="input_html_{{ $row['name'] }}"/>
                                        
                                        @if ($errors->has($errorEVP))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first($errorEVP) }}</strong>
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
                                <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
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
                'Configuración del sufijo',
                'La operación se ha realizado con éxito',
                'success'
            )
    @endif
    @if (session('confirmacion'))
            swal(
                'Configuración',
                'La operación se ha realizado con éxito',
                'success'
            )
    @endif
});
</script>
@endpush