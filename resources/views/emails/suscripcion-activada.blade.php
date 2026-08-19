<x-mail::message>
# ¡Tu plan está activo!

Hola **{{ $suscripcion->agencia?->nombre }}**,

Tu pago fue procesado exitosamente. Aquí está tu comprobante:

<x-mail::panel>
**Plan:** {{ $suscripcion->plan?->nombre }} ({{ $suscripcion->plan?->duracion_label }})
**Monto pagado:** ${{ number_format($suscripcion->precio_pagado, 2) }} MXN
**Inicio:** {{ $suscripcion->fecha_inicio?->format('d/m/Y') }}
**Válido hasta:** {{ $suscripcion->fecha_vencimiento?->format('d/m/Y') }}
**Renovación automática:** cada {{ $suscripcion->plan?->meses }} {{ $suscripcion->plan?->meses === 1 ? 'mes' : 'meses' }}
</x-mail::panel>

Tu inventario ya está visible en el sitio. Puedes empezar a subir vehículos desde tu portal.

<x-mail::button :url="route('agencia.vehiculos.index')">
Ir a mi inventario
</x-mail::button>

Si tienes alguna pregunta sobre tu suscripción, responde este correo y con gusto te ayudamos.

AutosMotosYMás
</x-mail::message>
