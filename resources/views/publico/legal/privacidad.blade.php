<x-app-layout title="Aviso de Privacidad — AutosMotosYMás"
              meta_desc="Aviso de privacidad de AutosMotosYMás. Conoce cómo tratamos y protegemos tus datos personales.">

    <div class="container-amm py-14 max-w-3xl">

        <h1 class="text-3xl font-bold text-base mb-2">Aviso de Privacidad</h1>
        <p class="text-sm text-muted mb-10">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>

        <div class="prose prose-invert max-w-none space-y-8 text-base leading-relaxed">

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">1. Responsable del tratamiento</h2>
                <p class="text-muted">
                    <strong class="text-base">AutosMotosYMás.com.mx</strong> (en adelante, "AMM") es responsable del
                    tratamiento de los datos personales que usted nos proporcione a través de nuestro sitio web y
                    plataformas asociadas, con domicilio en Guadalajara, Jalisco, México.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">2. Datos personales que recabamos</h2>
                <p class="text-muted mb-3">Podemos recabar los siguientes datos personales:</p>
                <ul class="list-disc list-inside text-muted space-y-1">
                    <li>Nombre completo</li>
                    <li>Correo electrónico</li>
                    <li>Número de teléfono o WhatsApp</li>
                    <li>Datos de la agencia (nombre, dirección, RFC en caso de facturación)</li>
                    <li>Información de uso y navegación (cookies, dirección IP)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">3. Finalidades del tratamiento</h2>
                <p class="text-muted mb-3">Sus datos son utilizados para:</p>
                <ul class="list-disc list-inside text-muted space-y-1">
                    <li>Conectar compradores interesados con agencias de vehículos</li>
                    <li>Gestionar su cuenta y acceso a la plataforma</li>
                    <li>Procesar pagos de suscripción a través de Stripe</li>
                    <li>Enviar notificaciones relacionadas con su cuenta o solicitudes</li>
                    <li>Mejorar nuestros servicios mediante análisis estadístico anonimizado</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">4. Transferencia de datos</h2>
                <p class="text-muted">
                    AMM no vende ni transfiere sus datos personales a terceros sin su consentimiento,
                    excepto cuando sea necesario para la prestación del servicio (por ejemplo, procesadores de pago)
                    o por requerimiento legal de autoridad competente.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">5. Derechos ARCO</h2>
                <p class="text-muted">
                    Usted tiene derecho a Acceder, Rectificar, Cancelar u Oponerse al tratamiento de sus datos
                    personales (derechos ARCO). Para ejercer estos derechos, envíe su solicitud a
                    <a href="mailto:privacidad@autosmotos.com.mx" class="text-brand-orange hover:underline">privacidad@autosmotos.com.mx</a>.
                    Daremos respuesta en un plazo no mayor a 20 días hábiles.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">6. Cookies</h2>
                <p class="text-muted">
                    Este sitio utiliza cookies propias para el correcto funcionamiento de la sesión y cookies de
                    terceros para análisis de tráfico. Puede configurar su navegador para rechazar cookies,
                    aunque esto puede afectar la experiencia de uso.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">7. Cambios al aviso</h2>
                <p class="text-muted">
                    AMM se reserva el derecho de modificar este aviso en cualquier momento. Los cambios serán
                    publicados en esta misma página con la fecha de actualización correspondiente.
                </p>
            </section>

        </div>

        <div class="mt-10 pt-6 border-t border-base">
            <a href="{{ route('home') }}" class="text-brand-orange hover:underline text-sm">← Volver al inicio</a>
        </div>

    </div>

</x-app-layout>
