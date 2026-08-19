<x-mail::message>
# No pudimos procesar tu pago

Hola **{{ $suscripcion->agencia?->nombre }}**,

Intentamos renovar tu plan **{{ $suscripcion->plan?->nombre }}** por **${{ number_format($monto, 2) }} MXN** pero el cobro no fue exitoso.

**¿Qué pasa ahora?**

Stripe reintentará el cobro automáticamente en los próximos días. Si el pago sigue fallando, tu suscripción será cancelada y tu inventario dejará de aparecer en el sitio.

**Para evitar interrupciones**, actualiza tu método de pago lo antes posible:

<x-mail::button :url="route('agencia.suscripcion.index')">
Actualizar método de pago
</x-mail::button>

Si crees que es un error de tu banco, también puedes contactarnos respondiendo este correo.

AutosMotosYMás
</x-mail::message>
