<x-app-layout title="Términos y Condiciones — AutosMotosYMás"
              meta_desc="Términos y condiciones de uso de la plataforma AutosMotosYMás. Conoce las reglas de uso del servicio.">

    <div class="container-amm py-14 max-w-3xl">

        <h1 class="text-3xl font-bold text-base mb-2">Términos y Condiciones</h1>
        <p class="text-sm text-muted mb-10">Última actualización: {{ date('d \d\e F \d\e Y') }}</p>

        <div class="space-y-8 text-base leading-relaxed">

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">1. Aceptación de los términos</h2>
                <p class="text-muted">
                    Al acceder y utilizar la plataforma <strong class="text-base">AutosMotosYMás.com.mx</strong> (en adelante,
                    "AMM" o "la Plataforma"), usted acepta quedar sujeto a estos Términos y Condiciones. Si no está de acuerdo
                    con alguno de ellos, le pedimos no utilizar el servicio.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">2. Descripción del servicio</h2>
                <p class="text-muted">
                    AMM es una plataforma digital que conecta a compradores de vehículos seminuevos con agencias
                    automotrices verificadas en Guadalajara y la Zona Metropolitana. AMM actúa como intermediario
                    tecnológico y no es parte de ninguna transacción de compraventa entre usuarios y agencias.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">3. Registro y cuentas</h2>
                <ul class="list-disc list-inside text-muted space-y-1">
                    <li>Para publicar inventario, las agencias deben registrarse y contar con una suscripción activa.</li>
                    <li>Usted es responsable de mantener la confidencialidad de sus credenciales de acceso.</li>
                    <li>AMM se reserva el derecho de suspender cuentas que incumplan estos términos.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">4. Suscripciones y pagos</h2>
                <p class="text-muted">
                    Los planes de suscripción para agencias se cobran de forma anticipada según la periodicidad
                    seleccionada (trimestral, semestral o anual). Los pagos se procesan de forma segura a través de
                    Stripe. No se realizan reembolsos por períodos parciales una vez activada la suscripción,
                    salvo en casos de error técnico imputable a AMM.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">5. Contenido publicado</h2>
                <p class="text-muted">
                    Las agencias son responsables de la veracidad, exactitud y legalidad de la información y
                    fotografías que publican. AMM se reserva el derecho de eliminar contenido que incumpla
                    políticas de uso o disposiciones legales aplicables, sin previo aviso.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">6. Limitación de responsabilidad</h2>
                <p class="text-muted">
                    AMM no garantiza la exactitud de la información proporcionada por las agencias y no es
                    responsable por daños directos o indirectos derivados de transacciones realizadas entre
                    usuarios y agencias. Se recomienda siempre verificar físicamente el estado del vehículo
                    antes de cualquier compra.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">7. Propiedad intelectual</h2>
                <p class="text-muted">
                    El diseño, marca, logotipos y contenido generado por AMM son propiedad exclusiva de
                    AutosMotosYMás. Queda prohibida su reproducción total o parcial sin autorización escrita.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">8. Ley aplicable</h2>
                <p class="text-muted">
                    Estos términos se rigen por las leyes de los Estados Unidos Mexicanos. Cualquier controversia
                    será sometida a la jurisdicción de los tribunales competentes de Guadalajara, Jalisco.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-base mb-3">9. Contacto</h2>
                <p class="text-muted">
                    Para dudas sobre estos términos, escríbenos a
                    <a href="mailto:hola@autosmotos.com.mx" class="text-brand-orange hover:underline">hola@autosmotos.com.mx</a>.
                </p>
            </section>

        </div>

        <div class="mt-10 pt-6 border-t border-base">
            <a href="{{ route('home') }}" class="text-brand-orange hover:underline text-sm">← Volver al inicio</a>
        </div>

    </div>

</x-app-layout>
