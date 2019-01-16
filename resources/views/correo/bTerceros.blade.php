<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notificación de baja de Autorizadores o Responsables de terceros.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <p>Notificación de baja de Autorizadores o Responsables de terceros.</p>
    <p>Los usuarios listados a continuación serán dados de baja; favor de realizar el cambio correspondiente, de no ser atendida esta solicitud
    y realizar el cambio a mas tardar en 15 días naturales se daran de baja todos los usuarios terceros correspondientes a estos autorizadores/responsables </p>
    <div>
        <table border="1">
            <thead bgcolor="#fcfcfc">
                <th><p><b>Número de empleado</b></p></th>
                <th><p><b>Nombre</b></p></th>
                <th><p><b>Tipo de cargo</b></p></th>
            </thead>
            <tbody>
                @foreach($data->data as $val)
                <tr>
                    <td align="center">{{ $val["numero"]}}</td>
                    <td align="center">{{ $val["nombre"]}}</td>
                    @switch($val["tipo"])
                        @case(1)
                            @php
                            $dato='Autorizador';
                            @endphp
                        @break
                        @case(2)
                            @php
                            $dato='Revisor';
                            @endphp
                        @break
                        @case(3)
                            @php
                            $dato='Autorizador/Revisor';
                            @endphp
                        @break
                    @endswitch
                    <td align="center">{{ $dato }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
