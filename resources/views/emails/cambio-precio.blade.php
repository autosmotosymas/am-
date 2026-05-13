<x-mail::message>
# Bajó el precio de un vehículo que te interesó

El **{{ $vehiculo->anio }} {{ $vehiculo->marca }} {{ $vehiculo->modelo }}** que consultaste en AutosMotosYMás acaba de bajar de precio.

<x-mail::panel>
~~${{ number_format($precioAnterior, 0, '.', ',') }}~~
**${{ number_format($precioNuevo, 0, '.', ',') }} MXN** ✓
</x-mail::panel>

¡Aprovecha antes de que alguien más lo aparte!

<x-mail::button :url="route('vehiculo.show', $vehiculo)">
Ver vehículo
</x-mail::button>

AutosMotosYMás
</x-mail::message>
