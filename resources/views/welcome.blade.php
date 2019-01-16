<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Televisa</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                width: 80%;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            /*Welcome*/
            .cuadros-mnu-welcome {
                margin-top: 20px;
            }
            .action-mnu-welcome {
                margin:0 20px;
                background-color:#3490dc; 
                color: #FFF; 
                width:25%;
                float: left;
                padding:40px 22px;
                cursor: pointer;
            }
        </style>
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            $(document).ready(function(){
                $('.action-mnu-welcome').click(function(){
                    var href = $(this).attr('data-url');
                    
                    window.location.href = href;
                });
            });
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    <?php /*@else
                        <a href="{{ route('login') }}">Acceso</a>*/?>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    TELEVISA
                </div>
                @auth
                @else
                    <div class="row">
                        <div class="col-lg-4 cuadros-mnu-welcome">
                            <div class="text-center action-mnu-welcome" data-url="https://portalbloqueos.televisa.com.mx/BloqueoAccesos/#/Login">
                                Portal de Bloqueo de Internos
                            </div>
                        </div>
                        <div class="col-lg-4 cuadros-mnu-welcome">
                            <div class="text-center action-mnu-welcome" data-url="{{ route('login') }}">
                                Portal de Conciliación de Bajas (PCB)
                            </div>
                        </div>
                        <div class="col-lg-4 cuadros-mnu-welcome">
                            <div class="text-center action-mnu-welcome" data-url="http://10.7.15.207/televisa_triada/terceros/public/index.php/login">
                                Portal de Bloqueo de Terceros
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </body>
</html>
