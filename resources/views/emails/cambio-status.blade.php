<x-mail::message>
@if($statusNuevo === 'vendido')
# El vehículo que te interesó ya fue vendido
@else
# El vehículo que te interesó ya está apartado
@endif

El **{{ $vehiculo->anio }} {{ $vehiculo->marca }} {{ $vehiculo->modelo }}** que consultaste
@if($statusNuevo === 'vendido')
ya fue vendido.
@else
fue apartado por otro comprador.
@endif

No te preocupes, tenemos más opciones disponibles.

<x-mail::button :url="route('busqueda', ['marca' => $vehiculo->marca])">
Ver más {{ $vehiculo->marca }}
</x-mail::button>

AutosMotosYMás
</x-mail::message>
