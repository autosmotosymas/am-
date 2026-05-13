<x-mail::message>
# Tu suscripción vence pronto

Hola **{{ $suscripcion->agencia?->nombre }}**,

Tu plan **{{ $suscripcion->plan?->nombre }}** en AutosMotosYMás vence en **{{ $diasRestantes }} {{ $diasRestantes === 1 ? 'día' : 'días' }}** ({{ $suscripcion->fecha_vencimiento->format('d \d\e F \d\e Y') }}).

Si no renuevas, tu inventario dejará de aparecer en el sitio.

<x-mail::button :url="route('agencia.suscripcion.index')">
Renovar ahora
</x-mail::button>

Si ya realizaste tu pago puedes ignorar este mensaje.

AutosMotosYMás
</x-mail::message>
