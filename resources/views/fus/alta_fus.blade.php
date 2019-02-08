@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="fuses" />
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
    <div class="row justify.content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('ALTA DE FUS')}}
                </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('newfus')}}">
                            @csrf
                            <input type="hidden" name="tercero" id="tercero" value="{{ $data['tercero']}}" />
                            <div class="form-group row">
                                <div class ="col-md-12">
                                    <label for="fus" class="col-md-8 col-form-label text-md-rigth">No. de FUS/RFC</label>
                                    <input type="text" id="fus" name="fus" class="form-control{{ $errors->has('fus') ? ' is-invalid' : '' }}" value="{{ old('fus') }}" onKeyPress="return soloNumeros(event)">
                                    @if ($errors->has('fus'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fus') }}</strong>
                                        </span>
                                    @endif 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="fecha_ini" class="col-md-8 col-form-label text-md-rigth">Fecha Inicial<span style="color: red;">*</span> : </label>
                                        <input class = "date form-control{{ $errors->has('fecha_ini') ? ' is-invalid' : '' }}" placeholder="dd-mm-yyyy" id="fecha_ini" name="fecha_ini" value="{{ old('fecha_ini') }}" required>
                                            @if ($errors->has('fecha_ini'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('fecha_ini') }}</strong>
                                                </span>
                                            @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_fin" class="col-md-8 col-form-label text-md-rigth">Fecha Final<span style="color: red;">*</span> : </label>
                                        <input class="date form-control{{ $errors->has('fecha_fin') ? ' is-invalid' : '' }}" placeholder="dd-mm-yyyy" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                                        @if ($errors->has('fecha_fin'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_fin') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <label><strong>Autorizador</strong></label>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="nom_auto" class="col-md-12 col-form-label text-md-rigth">Nombre Completo del Autorizador<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control{{ $errors->has('nom_auto') ? ' is-invalid' : '' }} autocomplete_txt" data-type="nom_auto" id="nom_auto"  name="nom_auto" onKeyPress="return sololetras(event)" value="{{ old('nom_auto') }}" required readonly="readonly">
                                        @if ($errors->has('nom_auto'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nom_auto') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="num_auto" class="col-md-12 col-form-label text-md-rigth">Número de Empleado del Autorizador<span style="color: red;">*</span></label>
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
                                    <label for="nom_res" class="col-md-12 col-form-label text-md-rigth">Nombre Completo del Responsable<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control{{ $errors->has('nom_res') ? ' is-invalid' : '' }} autocomplete" data-type="nom_res" id="nom_res" name="nom_res" onKeyPress="return sololetras(event)" value="{{ old('nom_res') }}" required readonly="readonly">
                                        @if ($errors->has('nom_res'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('nom_res') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="num_res" class="col-md-12 col-form-label text-md-rigth">Número de Empleado del Responsable<span style="color: red;">*</span></label>
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
                                    @if(old('destino'))
                                        @php
                                            $destino = old('destino');
                                            // echo '<pre>';print_r($destino);echo '</pre>';
                                        @endphp
                                    @endif
                                <div class="col-md-4">
                                    <label for="origen" class="col-md-12 col-form-label text-md-rigth">Aplicaciones para Asignar</label>
                                    <select name="origen[]" id="origen" multiple="multiple" style="margin-bottom:15px;" class="form-control select_multiple">
                                        @if(old('destino'))
                                            @php
                                                $origen = $data['app'];
                                                $destino = old('destino');
                                                $cDestino = count($destino);
                                                $count = 0;
                                            @endphp
                                            @foreach($origen as $ind => $val)
                                                @php
                                                    $show = 1;
                                                @endphp
                                                @foreach($destino as $index => $value)
                                                    @if($destino[$count] == $val->id)
                                                        @php
                                                            $show = 0;
                                                        @endphp
                                                    @endif
                                                    @php
                                                        if($count < $cDestino) {
                                                            $count = $count+1;
                                                            if($count == $cDestino) {
                                                                $count = 0;
                                                            }
                                                        }
                                                    @endphp
                                                @endforeach
                                                @if($show == 1)
                                                    <option value="{{$val->id}}">{{utf8_encode($val->name)}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($data['app'] as $val)
                                                <option value="{{$val->id}}">{{utf8_encode($val->name)}}</option>
                                            @endforeach
                                        @endif
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
                                    <label for="destino" class="col-md-12 col-form-label text-md-rigth">Aplicaciones Asignadas<span style="color: red;">*</span></label>
                                    <select name="destino[]" id="destino" multiple="multiple" class="form-control{{ $errors->has('destino') ? ' is-invalid' : '' }} select_multiple" required>
                                    @if(old('destino'))
                                        @php
                                            $origen = $data['app'];
                                        @endphp
                                        @foreach(old('destino') as $row => $value)
                                            @foreach($origen as $ind => $val)
                                                @if($val->id == $value)
                                                    <option selected value="{{$val->id}}">{{utf8_encode($val->name)}}</option>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                    </select>
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
                                    <a href="{{route('listar')}}" id="regresar" class="btn btn-warning">Regresar</a>
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
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 changeMonth: true,
changeYear: true,
 weekHeader: 'Sm',
 dateFormat: 'dd-mm-yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$( function() {
    $( "#fecha_ini" ).datepicker({ maxDate: 0}); //.attr('readonly', 'readonly'); para bloquear el input
  });
  $( function() {
    $( "#fecha_fin" ).datepicker({ minDate: 1 });
  });
  function soloNumeros(e){
    var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57);
}
$('#num_auto').click(function() {
                $('#nom_auto').val('');
            });

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
                //$('#nom_res').blur();
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