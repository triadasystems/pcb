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
                <form method="POST" action="{{ route('insertar')}}">
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
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >
                        </div>
                        <div class="col-md-4">
                            <label for="paterno" class="col-md-8 col-form-label text-md-rigth">{{ __('Apellido paterno')}}</label>
                            <input id="a_paterno" type="text" class="form-control{{ $errors->has('a_paterno') ? ' is-invalid' : '' }}" name="a_paterno" value="{{ old('a_paterno') }}" required >
                        </div>
                        <div class="col-md-4">
                            <label for="materno" class="col-md-8 col-form-label text-md-rigth">{{ __('Apellido materno')}}</label>
                            <input id="a_materno" type="text" class="form-control{{ $errors->has('a_materno') ? ' is-invalid' : '' }}" name="a_materno" value="{{ old('a_materno') }}" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Fecha inicial : </label>
                            <input class = "date form-control" type = "date" id="fecha_ini" required>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha final :</label>
                            <input class="date form-control" type = "date" id="fecha_fin" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="provedor" class="col-md-12 col-form-label text-md-left">{{ __('Empresa a la que pertenece')}}</label><br>
                            <select id="empresa" class="form-control" required>
                                <option value="0">Selecciona la empresa</option>
                                @foreach ($data['empresa'] as $val)
                                <option value="{{ $val->id}}">{{ $val->name}}</option>                                
                                @endforeach
                            </select>
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
                            <input type="text" class="form-control autocomplete_txt" data-type="num_auto" id="num_auto" name="num_auto[]" onKeyPress="return soloNumeros(event)" required>
                            <div id="au"></div>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Nombre completo del autorizador')}}</label>
                            <input type="text" class="form-control autocomplete_txt" data-type="nom_auto" id="nom_auto"  name="nom_auto[]" onkeyup="sololetras()" required>
                        </div>
                    </div>
                    <label>{{ __('Responsable')}}</label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __('Número de empleado del responsable')}}</label>
                            <input type="text" class="form-control autocomplete" data-type="num_res" id="num_res" name="num_res[]" onKeyPress="return soloNumeros(event)" required>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Nombre completo del responsable')}}</label>
                            <input type="text" class="form-control autocomplete" data-type="nom_res" id="nom_res" name="nom_res[]" onkeyup="sololetras()" required>
                        </div>
                    </div>
                    <label>Aplicaciones</label>
                    <hr>
                    <div class="form-group row">
                    <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <label>Aplicaciones para asignar</label><br>
                            <select name="origen[]" id="origen" multiple="multiple" style="margin-bottom:15px;" class="form-control select_multiple">
                                @foreach ($data['app'] as $val)
                                <option value="{{$val->id}}">{{utf8_encode($val->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-center">
                        <br>
                        <br>
                        <input type="button" id="pasar" class="btn btn-info" value="»"><br><br><input type="button" id="quitar" class="btn btn-info" value="«"><br/>
                            <!--<input type="button" id="pasartodos" class="btn btn-info" value="Todos »">&nbsp;&nbsp;<input type="button" id="quitartodos" class="btn btn-info" value="« Todos">-->
                        </div>
                        <div class="col-md-4">
                            <label>Aplicaciones asignadas</label><br>
                            <select name="destino[]" id="destino" multiple="multiple" class="form-control select_multiple" required></select>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="enviar">Agregar</button>
                </form>
                
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

function sololetras()
{
    var x = document.getElementById("nom_auto");
  x.value = x.value.toUpperCase();
}

function soloNumeros(e){
    var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57);
}
//autocomplete script autorizador
$(document).on('focus','.autocomplete_txt', function()
{
    type=$(this).data('type');

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
                    var array =$.map(data, function(item){
                        return{
                            label: item[autoType],
                            value: item[autoType],
                            data: item
                        }
                    });
                    response(array)
                }
            });
        },
        select: function( event, ui)
        {
            var data=ui.item.data;
            id_arr= $(this).attr('id');
            id=id_arr.split("_");
            elementId= id[id.length-1];
            $('#num_auto').val(data.numero);
            $('#nom_auto').val(data.nombre);
        }
    });
});
//autocomplete script Responsable
$(document).on('focus','.autocomplete', function()
{
    type=$(this).data('type');

    if(type =='num_res')autoType='numero';
    if(type =='nom_res')autoType='nombre';
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
                    var array =$.map(data, function(item){
                        return{
                            label: item[autoType],
                            value: item[autoType],
                            data: item
                        }
                    });
                    response(array)
                }
            });
        },
        select: function( event, ui)
        {
            var data=ui.item.data;
            id_arr= $(this).attr('id');
            id=id_arr.split("_");
            elementId= id[id.length-1];
            $('#num_res').val(data.numero);
            $('#nom_res').val(data.nombre);
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
