@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>
<input type="hidden" id="modulo" value="tcslistaactivos" />
<input type="hidden" id="formAjax" value="1" />
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de externos activos
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaTerceros">Alta de Externos Activos</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="terceros-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre | Gafete</th>
                                        <th>E-mail</th>
                                        <th>Empresa</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Autocomplete
    $(document).on('focus','.autocomplete_txt, .autocomplete_txt2', function() {
        var type = $(this).data('type');
        var tipoResponsable = $(this).attr("data-responsable");

        if(type =='num_auto' || type =='num_res') {
            autoType='numero';
        }
        if(type =='nom_auto' || type =='nom_res') { 
            autoType='nombre';
        }
        
        $(this).autocomplete({
            minLength:0,
            source: function(request, response) {
                $.ajax({
                    url:"{{ route('sustitucion.autocomplete') }}",
                    dataType: "json",
                    data:{ term: request.term, type: type },
                    success: function(data) {
                        var array = $.map(data, function(item){
                            var response = "";
                            if(item[autoType] !== undefined && item[autoType] !== undefined) {
                                response = {
                                    label: item[autoType],
                                    value: item[autoType],
                                    data: item
                                }
                            } else if(item != "") {
                                response = {
                                    label: item,
                                    value: item,
                                    data: "fail"
                                };
                            }
                            return response;
                        });
                        console.log(array);
                        response(array);
                    }
                });
            },
            select: function( event, ui) {
                var data = ui.item.data;
                if (data != "fail") {
                    switch (tipoResponsable) {
                        case "auto":
                            $('#nomAuto').val(data.nombre);
                            $('#numAuto').val(data.numero);    
                            break;
                        case "resp":
                            $('#nomResp').val(data.nombre);
                            $('#numResp').val(data.numero);
                            break;
                    }
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
        });
    });
    // FIN Auto complete
    $(document).ready(function() {          
        @if (session('confirmacion'))
            swal(
                'Tercero registrado',
                'La operación se ha realizado con éxito. El ID del tercero es: {{session("confirmacion")}}',
                'success'
            )
        @endif
        @if (session('error_alta'))
            swal(
                'Advertencia',
                'La operación no se ha llevado acabo ya que el limite de subfijo ha sido superado, favor de ponerse en contacto con el administrador del sistema',
                'warning'
            )
        @endif 
        @if (session('validacionCalculo'))
            swal(
                'Tercero registrado',
                'La operación no se puede realizar se ha llegado al limite de id de externos, favor de ponerse en contacto con el administrador.',
                'success'
            )
        @endif                         
        $('#altaTerceros').click(function()
        {
            var url = '{!!route('terceros.alta')!!}';
            $( location).attr("href",url);
        });
               
        var table = $('#terceros-table').DataTable({
            language: {
                url: "{{ asset('json/Spanish.json') }}"
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('terceros.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'datos_tercero', name: 'datos_tercero' },
                { data: 'correo', name: 'correo' },
                { data: 'empresa', name: 'empresa' },
                {
                    render: function (data, type, row) {
                        var html = '';
                        html = '<div class="row">'+
                                    '<div class="col-lg-12 text-center">'+
                                        '<a class="btn btn-primary" href="../fus/lista/'+row.ident+'">Ver FUS. <i class="fas fa-user-friends"></i></a>'+
                                    '</div>'+
                                '</div>';

                        return html;
                    }
                },
                {
                    render: function (data, type, row) {
                        var html = '';
                        html = '<div class="row">'+
                            '<div class="col-lg-5 text-center">'+
                                '<a class="btn btn-primary" href="../fus/agregar/'+row.ident+'">Alta de FUS. <i class="glyphicon glyphicon-plus"></i></a>'+
                            '</div>'+
                        '</div>';
                        return html;
                    }
                },
            ]
        });
    });
</script>
@endpush
