@if($demo->demo_two==1)
<p>RESULTADO DEL PROCESO DE CONCILIACI&Oacute;N</p>
 
<p>Informaci&oacute;n del reporte de conciliaci&oacute;n</p>
@elseif($demo->demo_two==2)
<p>RESULTADO DEL PROCESO DE BAJAS</p>
 
<p>Informaci&oacute;n del reporte de bajas</p>
@endif
<div>
    <table border="1">
        <thead bgcolor="#fcfcfc">
            <th><p><b>Num. Empleado</b></p></th>
            <th><p><b>Usuario</b></p></th>
            <th><p><b>Nombre completo</b></p></th>
            <th><p><b>Aplicaci&oacute;n</b></p></th>
        </thead>
        <tbody>
        @if(count($demo->demo_one)>0)
            @foreach($demo->demo_one as $algo)
                <tr>
                    <td align="center">{{ $algo["idemp"] }}</td>
                    <td align="center">{{ $algo["nombre"] }}</td>
                    <td align="center">{{ $algo["apellidos"] }}</td>
                    <td align="center">{{ $algo["alias"] }}</td>
                </tr>    
            @endforeach
        @else
            <tr>
                <td colspan="4">No se encontraron resultados</td>
            </tr>
        @endif    
        </tbody>
    </table>
</div>
<br/>
<i>{{ $demo->sender }}</i> 