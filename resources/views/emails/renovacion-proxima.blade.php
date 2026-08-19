<x-mail::message>
# Tu suscripción se renueva pronto

Hola **{{ $suscripcion->agencia?->nombre }}**,

Te avisamos que tu plan **{{ $suscripcion->plan?->nombre }} ({{ $suscripcion->plan?->duracion_label }})** en AutosMotosYMás **se renovará automáticamente el {{ $fechaRenovacion }}** con un cargo de:

## ${{ number_format($monto, 2) }} MXN

El cobro se realizará a la tarjeta registrada en tu cuenta. Si no quieres que se renueve, cancela antes de esa fecha y conservarás el acceso hasta que venza el período actual.

<x-mail::button :url="route('agencia.suscripcion.index')">
Ver mi suscripción
</x-mail::button>

<x-mail::button :url="route('agencia.suscripcion.cancelar')" color="error">
Cancelar renovación
</x-mail::button>

Si no haces nada, se cobrará automáticamente y tu inventario seguirá publicado sin interrupciones.

AutosMotosYMás
</x-mail::message>
