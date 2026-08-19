<x-mail::message>
# ¡Tu agencia está lista!

Hola **{{ $agencia->nombre }}**,

Tu cuenta en **AutosMotosYMás** ha sido creada. Aquí están tus datos de acceso:

<x-mail::panel>
**Correo:** {{ $agencia->email }}
**Contraseña:** {{ $passwordPlano }}
</x-mail::panel>

Con tu cuenta puedes:
- Subir y administrar tu inventario de vehículos
- Recibir leads de compradores interesados
- Ver estadísticas de tus anuncios

El siguiente paso es elegir el plan que mejor se adapte a tu agencia para activar tu perfil en el sitio.

<x-mail::button :url="route('login')">
Entrar a mi portal
</x-mail::button>

Te recomendamos cambiar tu contraseña la primera vez que entres.

AutosMotosYMás
</x-mail::message>
