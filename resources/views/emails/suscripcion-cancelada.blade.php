<x-mail::message>
# Renovación cancelada

Hola **{{ $suscripcion->agencia?->nombre }}**,

Tu renovación automática ha sido cancelada. No se realizará ningún cobro adicional.

<x-mail::panel>
**Plan:** {{ $suscripcion->plan?->nombre }} ({{ $suscripcion->plan?->duracion_label }})
**Acceso hasta:** {{ $suscripcion->fecha_vencimiento?->format('d \d\e F \d\e Y') }}
</x-mail::panel>

Conservas acceso completo a tu portal y tu inventario seguirá publicado hasta esa fecha. Después, tu perfil dejará de aparecer en el sitio.

Si cambias de opinión, puedes reactivar tu plan en cualquier momento.

<x-mail::button :url="route('agencia.suscripcion.index')">
Reactivar mi plan
</x-mail::button>

AutosMotosYMás
</x-mail::message>
