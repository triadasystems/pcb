<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'PBC') }}</title>
        
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <!-- jquery-ui -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
       
        
        <!-- Adicionales datatables -->
        <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('js/adicionales_datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('js/adicionales_datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('js/adicionales_datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('js/adicionales_datatables/vfs_fonts.js') }}"></script>

        <!-- <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> -->
        <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/general.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
        <!-- Adicionales datatables -->
        <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
        <!-- css jquery ui -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}">
        <!-- <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet"> -->
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        {{ config('app.name', 'PBC') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>
                        
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Acceso') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Registro') }}</a>
                                @endif
                            </li> -->
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    PCB <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('conciliacion') }}">
                                        {{ __('Conciliación / Bajas') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('listausuarios') }}">
                                        {{ __('Usuarios') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('index_perfil') }}">
                                        {{ __('Perfiles') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('mails.index') }}">
                                        {{ __('E-mails') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('modulos.index') }}">
                                        {{ __('Módulos') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('permisos.index') }}">
                                        {{ __('Permisos') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('relacionpm.index') }}">
                                        {{ __('Módulo - Perfil') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('listaConexiones') }}">
                                        {{ __('Conexiones') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('viewEncrypt') }}">
                                        {{ __('Herramienta de encriptación') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Terceros <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('listar') }}">
                                        {{ __('Terceros') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('motivosbajas') }}">
                                        {{ __('Motivos de Bajas') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('proveedores') }}">
                                        {{ __('Proveedores') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('mesacontrol') }}">
                                        {{ __('Mesa de Control') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('settings') }}">
                                        {{ __('Configuraciones') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('bajasdiarias') }}">
                                        {{ __('Rep. Bajas Diarias') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('tercerosactivos') }}">
                                        {{ __('Rep. Usuarios Activos') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('responsables') }}">
                                        {{ __('Reporte Responsables') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('trazabilidad') }}">
                                        {{ __('Reporte Trazabilidad') }}
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }} <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
                
            <main class="py-4">
                @yield('content')
            </main>
        </div>
        <div id="loading_pes" class="loading_pes_hide">
            <div class="container content-loading-barprogress">
                <div class="row" id="logotelevisa">
                    <div class="col-lg-7 offset-lg-3 col-md-7 offset-md-3 col-sm-12 col-xs-12">
                        <img src="{{ asset('images/loading.png') }}" class="loading-logo"/>
                    </div>
                </div>
                <div class="row dvibarprogress-hide" id="dvibarprogress">
                    <div class="col-lg-7 offset-lg-3 col-md-7 offset-md-3 col-sm-12 col-xs-12">
                        <div class="progress bar-progress-conciliacion">
                            <div class="progress-bar progress-bar-striped active txt-bar-progress" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- App scripts -->
        @auth
            <script>
                sessionStorage.desactivaLogout = 1;

                // En caso de form con ajax y sweetalert2
                function mostrarError(id = null) {
                    $("#"+id).addClass("error-msj-activo");
                    $("#"+id).removeClass("error-msj-inactivo");
                }
                function ocultarError(id = null) {
                    $("#"+id).removeClass("error-msj-activo");
                    $("#"+id).addClass("error-msj-inactivo");
                } // En caso de form con ajax y sweetalert2
                
                function logout() {
                    if(sessionStorage.desactivaLogout == 1) {
                        document.getElementById('logout-form').submit();
                    }
                }

                function mostrarLoading() {
                    $("#dvibarprogress").removeClass("dvibarprogress-hide");
                    $("#loading_pes").removeClass("loading_pes_hide");
                    $("#loading_pes").addClass("loading_pes_show");
                }
                function ocultarLoading() {
                    $("#dvibarprogress").addClass("dvibarprogress-hide");
                    $("#loading_pes").addClass("loading_pes_hide");
                    $("#loading_pes").removeClass("loading_pes_show");

                }
                function mostrarLoadingMail() {
                    $("#loading_pes").removeClass("loading_pes_hide");
                    $("#loading_pes").addClass("loading_pes_show");
                }
                function ocultarLoadingMail() {
                    $("#loading_pes").addClass("loading_pes_hide");
                    $("#loading_pes").removeClass("loading_pes_show");

                }

                $(document).ready(function(){
                    $('.btn-eye').click(function(){
                        var tipo = $(this).attr('data-tipo');
                        var className = $('#icon-'+tipo).attr('class'); 

                        if (className == 'far fa-eye') {
                            $('#icon-'+tipo).removeClass('fa-eye');
                            $('#icon-'+tipo).addClass('fa-eye-slash');
                            $('#db_'+tipo).get(0).type = 'text';
                        } else if(className == 'far fa-eye-slash') {
                            $('#icon-'+tipo).removeClass('fa-eye-slash');
                            $('#icon-'+tipo).addClass('fa-eye');
                            $('#db_'+tipo).get(0).type = 'password';
                        }
                    });

                    var mouseStop = null;
                    var Time = 600000; // Tiempo en milisegundos que espera para efectuarse la funcion

                    $(document).on('mousemove', function() {
                        clearTimeout(mouseStop);
                        mouseStop = setTimeout(logout,Time);
                    });

                    $('#regresar').on('click', function(){
                        var url = "{{ URL::to('/home') }}";
                        
                        $( location ).attr("href", url);
                    });

                    var id = "{{ Auth::user()->id }}";
                    var modulo = $('#modulo').val();
                    var url = "{{ url('validatemodules') }}";

                    if(modulo != undefined) { 
                        $.ajax({
                            type: 'GET',
                            url: url+'/'+id+'/'+modulo,
                            async: false 
                        }).done(function(response){
                            if(response[0] == 'failed') {
                                swal({
                                    title: 'Acceso Denegado',
                                    text: "Su perfil no cuenta con permisos para acceder a este sitio",
                                    type: 'warning',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: 'Regresar'
                                }).then((result) => {
                                    if (result.value) {
                                        window.location.href = "{{ route('home') }}";
                                    }
                                })

                            }
                        });  
                    }
                });
            </script>
        @endauth
        @stack('scripts')
    </body>
</html>