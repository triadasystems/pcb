@extends('layouts.app')

@section('content')
<!-- <input type="hidden" id="modulo" value="home" /> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Home</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12" style="margin-bottom:15px;">
                                Bienvenido {{ Auth::user()->name }}!
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('conciliacion') }}">
                                    Conciliación / Bajas
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('listausuarios') }}">
                                    Usuarios
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('index_perfil') }}">
                                    Perfiles
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('mails.index') }}">
                                    E-mails
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('modulos.index') }}">
                                    Módulos
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('permisos.index') }}">
                                    Permisos
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('relacionpm.index') }}">
                                    Módulo - perfil
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('listaConexiones') }}">
                                    Conexiones
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('viewEncrypt') }}">
                                    Encriptación
                                </div>
                            </div>
                            <!-- <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('modulos.index') }}">
                                    Módulos
                                </div>
                            </div>
                            <div class="col-lg-4 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('permisos.index') }}">
                                    Permisos
                                </div>
                            </div> -->
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
    $(document).ready(function(){
        $('.action-mnu').click(function(){
            var href = $(this).attr('data-url');
            
            window.location.href = href;
        });

        @if(session('msjError'))
            swal({
                type: 'warning',
                title: 'Advertencia',
                text: '{{ session("msjError") }}'
            });
        @endif

        @if(isset($msjError))
            swal({
                type: 'warning',
                title: 'Advertencia',
                text: '{{ $msjError }}'
            });
        @endif
    });
</script>
@endpush