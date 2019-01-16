@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="tcslistaactivos" />
<style>
    @media only screen and (min-width: 768px) {
        #btn-pasar {
            margin-top:38px;
        }
    }
    #btn-quitar {
        margin-top:3px;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('ALTA DE USUARIOS EXTERNOS')}}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('insertar')}}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="fus" class="col-md-8 col-form-label text-md-rigth">No. de FUS/RFC</label>
                            <input type="text" id="fus" name="fus" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="mesa" class="col-md-8 col-form-label text-md-rigth">Mesa de control</label>
                            <select id="mesa" name ="mesa" class="form-control{{ $errors->has('mesa') ? ' is-invalid' : '' }}" value="{{ old('mesa') }}" required>
                                <option value="">Seleccione...</option>
                                @foreach ($data['mesa'] as $val)
                                <option value="{{ $val->id}}">{{ $val->name}}</option>                                
                                @endforeach
                            </select>
                            @if ($errors->has('mesa'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('mesa') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label><strong>Datos del usuario</strong></label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="name" class="col-md-8 col-form-label text-md-rigth">Nombre</label>
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" onkeyup="javascript:this.value=this.value.toUpperCase();"  onKeyPress="return sololetras(event)" required >
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="a_paterno" class="col-md-8 col-form-label text-md-rigth">Apellido Paterno</label>
                            <input id="a_paterno" type="text" class="form-control{{ $errors->has('a_paterno') ? ' is-invalid' : '' }}" name="a_paterno" value="{{ old('a_paterno') }}" onkeyup="javascript:this.value=this.value.toUpperCase();"  onKeyPress="return sololetras(event)" required >
                            @if ($errors->has('a_paterno'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('a_paterno') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="a_materno" class="col-md-8 col-form-label text-md-rigth">Apellido Materno</label>
                            <input id="a_materno" type="text" class="form-control{{ $errors->has('a_materno') ? ' is-invalid' : '' }}" name="a_materno" value="{{ old('a_materno') }}" onkeyup="javascript:this.value=this.value.toUpperCase();"  onKeyPress="return sololetras(event)" >
                            @if ($errors->has('a_materno'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('a_materno') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="fecha_ini" class="col-md-8 col-form-label text-md-rigth">Fecha Inicial : </label>
                            <input class = "date form-control{{ $errors->has('fecha_ini') ? ' is-invalid' : '' }}" type = "date" id="fecha_ini" name="fecha_ini" value="{{ old('fecha_ini') }}" required>
                            @if ($errors->has('fecha_ini'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fecha_ini') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="col-md-8 col-form-label text-md-rigth">Fecha Final : </label>
                            <input class="date form-control{{ $errors->has('fecha_fin') ? ' is-invalid' : '' }}" type = "date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                            @if ($errors->has('fecha_fin'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fecha_fin') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="empresa" class="col-md-12 col-form-label text-md-rigth">Empresa a la que Pertenece</label>
                            <select id="empresa" name="empresa" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" value="{{ old('empresa') }}" required>
                                <option value="">Selecciona la empresa</option>
                                @foreach ($data['empresa'] as $val)
                                <option value="{{ $val->id}}">{{ $val->name}}</option>                                
                                @endforeach
                            </select>
                            @if ($errors->has('empresa'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('empresa') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="gafete" class="col-md-12 col-form-label text-md-rigth">No. de Gafete</label>
                            <input type="text" id="gafete" name="gafete"  class="form-control">
                            @if ($errors->has('gafete'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('gafete') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="col-md-12 col-form-label text-md-rigth">Usuario de Red/E-mail</label>
                            <input type="text" id="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <label><strong>Autorizador</strong></label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="nom_auto" class="col-md-12 col-form-label text-md-rigth">Nombre Completo del Autorizador</label>
                            <input type="text" class="form-control{{ $errors->has('nom_auto') ? ' is-invalid' : '' }} autocomplete_txt" data-type="nom_auto" id="nom_auto"  name="nom_auto" onKeyPress="return sololetras(event)" value="{{ old('nom_auto') }}" required>
                            @if ($errors->has('nom_auto'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('nom_auto') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="nom_auto" class="col-md-12 col-form-label text-md-rigth">Número de Empleado del Autorizador</label>
                            <input type="text" class="form-control{{ $errors->has('num_auto') ? ' is-invalid' : '' }} autocomplete_txt" data-type="num_auto" id="num_auto" name="num_auto" onKeyPress="return soloNumeros(event)" value="{{ old('num_auto') }}" required>
                            @if ($errors->has('num_auto'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('num_auto') }}</strong>
                                </span>
                            @endif
                            <div id="au"></div>
                        </div>   
                    </div>
                    <label><strong>Responsable</strong></label>
                    <hr>
                    <div class="form-group row">
                    <div class="col-md-6">
                            <label for="nom_res" class="col-md-12 col-form-label text-md-rigth">Nombre Completo del Responsable</label>
                            <input type="text" class="form-control{{ $errors->has('nom_res') ? ' is-invalid' : '' }} autocomplete" data-type="nom_res" id="nom_res" name="nom_res" onKeyPress="return sololetras(event)" value="{{ old('nom_res') }}" required>
                            @if ($errors->has('nom_res'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('nom_res') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="num_res" class="col-md-12 col-form-label text-md-rigth">Número de Empleado del Responsable</label>
                            <input type="text" class="form-control{{ $errors->has('num_res') ? ' is-invalid' : '' }} autocomplete" data-type="num_res" id="num_res" name="num_res" onKeyPress="return soloNumeros(event)" value="{{ old('num_res') }}" required>
                            @if ($errors->has('num_res'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('num_res') }}</strong>
                                </span>
                            @endif
                        </div>                       
                    </div>
                    <label><strong>Aplicaciones</strong></label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <label for="origen" class="col-md-12 col-form-label text-md-rigth">Aplicaciones para Asignar</label>
                            <select name="origen[]" id="origen" multiple="multiple" style="margin-bottom:15px;" class="form-control select_multiple">
                                @foreach ($data['app'] as $val)
                                <option value="{{$val->id}}">{{utf8_encode($val->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="row" id="btn-pasar">
                                <div class="col-md-12">
                                    <input type="button" id="pasar" class="btn btn-info" value="»">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="btn-quitar">
                                    <input type="button" id="quitar" class="btn btn-info" value="«">
                                </div>
                            </div>
                            <!--<input type="button" id="pasartodos" class="btn btn-info" value="Todos »">&nbsp;&nbsp;<input type="button" id="quitartodos" class="btn btn-info" value="« Todos">-->
                        </div>
                        <div class="col-md-4">
                            <label for="destino" class="col-md-12 col-form-label text-md-rigth">Aplicaciones Asignadas</label>
                            <select name="destino[]" id="destino" multiple="multiple" class="form-control{{ $errors->has('destino') ? ' is-invalid' : '' }} select_multiple" value="{{ old('destino[]') }}" required></select>
                            @if ($errors->has('destino'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('destino') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{route('home')}}" id="regresar" class="btn btn-warning">Regresar</a>
                            <button type="submit" class="btn btn-primary" id="enviar">Guardar</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">  
  function sololetras(e){
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
        especiales = "8-37-39-46";
        tecla_especial = false
        for(var i in especiales)
        {
            if(key == especiales[i])
            {
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial)
        {
            return false;
        }
    }
function soloNumeros(e){
    var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57);
}
//autocomplete script autorizador
$(document).on('focus','.autocomplete_txt', function()
{
    type = $(this).data('type');

    if(type =='num_auto')autoType='numero';
    if(type =='nom_auto')autoType='nombre';
    
    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('terceros.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type
                },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            };
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },
        select: function( event, ui)
        {
            var data = ui.item.data;
            if (data != "fail")
            {
                $('#nom_auto').val(data.nombre);
                $('#num_auto').val(data.numero);
            } else if(data == "fail") {
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
//autocomplete script Responsable
$(document).on('focus','.autocomplete', function()
{
    type = $(this).data('type');
    
    if(type =='nom_res')autoType='nombre';
    if(type =='num_res')autoType='numero';

    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('terceros.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type
                },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            };
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },
        select: function( event, ui)
        {
            var data = ui.item.data;
            if (data != "fail")
            {
                $('#nom_res').val(data.nombre);
                $('#num_res').val(data.numero);
            } else if(data == "fail") {
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
$().ready(function() 
{
	$('#pasar').click(function() { return !$('#origen option:selected').remove().appendTo('#destino'); });  
	$('#quitar').click(function() { return !$('#destino option:selected').remove().appendTo('#origen'); });
	$('#pasartodos').click(function() { $('#origen option').each(function() { $(this).remove().appendTo('#destino'); }); });
	$('#quitartodos').click(function() { $('#destino option').each(function() { $(this).remove().appendTo('#origen'); }); });
	//$('.submit').click(function() { $('#destino option').prop('selected', 'selected'); });
});
</script>
@endpush
