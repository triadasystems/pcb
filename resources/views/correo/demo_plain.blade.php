RESULTADO DEL PROCESO
Informacion del reporte
 
Informacion del reporte
Num. Empleado              Nombre completo              Aplicación     
@foreach($demo->demo_one as $algo)
{{ $algo["idemp"] }}    {{ $algo["apellidos"] }}      {{ $algo["nombre"] }}
@endforeach 

