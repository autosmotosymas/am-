<x-mail::message>
# Nuevo contacto en tu vehículo

Alguien está interesado en tu **{{ $lead->vehiculo?->anio }} {{ $lead->vehiculo?->marca }} {{ $lead->vehiculo?->modelo }}**.

<x-mail::panel>
**{{ $lead->nombre }}**
📞 {{ $lead->telefono }}
@if($lead->email)
✉️ {{ $lead->email }}
@endif
@if($lead->mensaje)

*"{{ $lead->mensaje }}"*
@endif
</x-mail::panel>

**Tipo de contacto preferido:** {{ ucfirst($lead->tipo) }}

Responde lo antes posible para no perder este lead.

<x-mail::button :url="route('agencia.leads.index')">
Ver en mi portal
</x-mail::button>

AutosMotosYMás
</x-mail::message>
