<x-mail::message>
# Tu plan se renovó exitosamente

Hola **{{ $suscripcion->agencia?->nombre }}**,

Tu plan **{{ $suscripcion->plan?->nombre }}** en AutosMotosYMás se renovó automáticamente. Tu inventario sigue publicado sin interrupciones.

<x-mail::panel>
**Plan:** {{ $suscripcion->plan?->nombre }} ({{ $suscripcion->plan?->duracion_label }})
**Monto cobrado:** ${{ number_format($monto, 2) }} MXN
**Nuevo vencimiento:** {{ $suscripcion->fecha_vencimiento?->format('d/m/Y') }}
</x-mail::panel>

<x-mail::button :url="route('agencia.suscripcion.index')">
Ver mi suscripción
</x-mail::button>

Si no deseas que se renueve el siguiente período, puedes cancelar desde tu portal antes de la próxima fecha de renovación.

AutosMotosYMás
</x-mail::message>
