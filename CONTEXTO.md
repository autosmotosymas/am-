# AMM — AutosMotosYMás.com.mx
## Archivo de contexto para Claude Code — leer antes de cualquier tarea

---

## ¿Qué es este proyecto?

Plataforma de venta de vehículos seminuevos para **Guadalajara / ZMG**.
- Solo agencias, lotes y distribuidores — **sin particulares**
- Modelo B2B2C: agencias pagan suscripción mensual, compradores navegan gratis
- Diferenciador clave: **certificación física** de cada vehículo por talleres verificadores aliados

---

## Stack técnico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 13 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS v3 + Alpine.js |
| Base de datos | MySQL 8 — DB: `amm_db` |
| Auth | Laravel Breeze (Blade) |
| Roles/permisos | Spatie Laravel Permission |
| Pagos | **Stripe** (NO Conekta — decisión tomada) |
| Build | Vite + npm |
| Cola de jobs | Database queue |
| Mail | SMTP cPanel — mco10.prodns.mx port 465 (smtps) |

---

## Decisiones de diseño tomadas (NO cambiar sin consultar)

- **Dark/Light mode**: clase `dark` en `<html>`. Config: `darkMode: 'class'` en tailwind
- **Container**: máx 1280px centrado — clase CSS `.container-amm`
- **Paleta**: Naranja `#E8710A` + Negro `#111111` (del logo)
- **Mobile first**: breakpoints Tailwind, diseño desde 390px hacia arriba
- **URLs semánticas con slugs**: `/autos/toyota-corolla-se-cvt-2022`
- **Route Model Binding** por slug en Vehiculo y Agencia
- **Sin React/Vue** — todo server-side rendering con Blade
- **Pagos con Stripe** — Checkout mode subscription + webhook handling
- **Tipografía**: Figtree (Google Fonts vía bunny.net) — text-base en todo el sistema
- **Secciones**: padding vertical 120px con clase `.section-py`

---

## Los 5 roles del sistema

| Rol | Acceso | Redirige a |
|-----|--------|-----------|
| `admin` | Todo el sistema | `/admin/dashboard` |
| `agencia` | Su inventario, leads, estadísticas | `/agencia/dashboard` |
| `capturador` | Solo captura de inventario desde móvil | `/captura` |
| `comprador` | Navegar, seguir autos, enviar leads | `/perfil` |
| `vendedor` | Registrar agencias, capturar inventario, gestionar fotos | `/vendedor/dashboard` |

---

## Base de datos — tablas

### Tablas del negocio
- `planes` — 2 tiers: Básico ($599/mes, stripe_price_id guardado) y Premium ($1,299/mes, stripe_price_id guardado)
- `verificadores` — talleres aliados que hacen inspecciones físicas
- `agencias` — lotes y distribuidores registrados
- `vehiculos` — inventario central (con slug, status, precio, specs)
- `vehiculo_fotos` — fotos por vehículo
- `certificaciones` — resultado de inspección física con checklist JSON
- `suscripciones` — contrato agencia↔plan con IDs de Stripe
- `pagos` — historial de cobros
- `leads` — mensajes de comprador → agencia
- `seguimientos` — "seguir este auto" con alertas de precio y status

### Tablas Laravel/paquetes
- `users`, `sessions`, `cache`, `jobs`
- `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions`

### IMPORTANTE — modelos con tabla explícita
Laravel pluraliza mal los nombres en español. Estos modelos tienen `protected $table`:
- `Plan` → `planes`
- `Certificacion` → `certificaciones`
- `Suscripcion` → `suscripciones`
- `Verificador` → `verificadores`

---

## Estados de un vehículo

```
borrador → publicado → [inspeccion_agendada] → certificado
                ↓                                    ↓
             pausado                              apartado → vendido
```

---

## Tiers de suscripción

| Feature | Básico $599/mes | Premium $1,299/mes |
|---------|-----------------|-------------------|
| Vehículos activos | 20 | 60 |
| Fotos por vehículo | 8 | 30 |
| Certificaciones incluidas | ✗ | 3/mes |
| Vehículos destacados | 0 | 5 |
| Badge agencia premium | ✗ | ✓ |
| Estadísticas avanzadas | ✗ | ✓ |

---

## Stripe — configuración actual

- **Modo**: TEST (pendiente cambiar a LIVE para producción real)
- `STRIPE_KEY` = pk_test_51GqLY5L42wZLmAlakJALM3E3x... (completa en .env del servidor)
- `STRIPE_SECRET` = sk_test_51GqLY5L42wZLmAlaEUE9D7l5... (completa en .env del servidor)
- `STRIPE_WEBHOOK_SECRET` = **PENDIENTE** — configurar webhook en Stripe Dashboard test mode → `https://autosmotosymas.mx/stripe/webhook`
- Plan Básico synced → `price_1TWkfNL42wZLmAlaKwwto21x`
- Plan Premium synced → `price_1TWkfNL42wZLmAlaHYYW6iru`
- Comando para sincronizar: `php artisan stripe:sync-plans`
- Webhooks a registrar: `checkout.session.completed`, `invoice.paid`, `invoice.payment_failed`, `customer.subscription.deleted`, `customer.subscription.updated`

---

## Mail — configuración actual

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mco10.prodns.mx
MAIL_PORT=465
MAIL_USERNAME=noreply@autosmotosymas.mx
MAIL_FROM_ADDRESS=noreply@autosmotosymas.mx
MAIL_FROM_NAME="AutosMotosYMás"
```
Correo de pruebas/dev: `developer@autosmotosymas.mx`

---

## Jobs de background (queue)

```
NotificarCambioPrecio    → cuando agencia guarda nuevo precio más bajo
NotificarCambioStatus    → cuando cambia status del vehículo (apartado/vendido)
NotificarNuevoLead       → nuevo mensaje de comprador → agencia
NotificarSuscripcionVence → cron diario 9am CDMX — avisa 7, 3 y 1 día antes
```

---

## PWA — Captura móvil

- `public/pwa/manifest.json` — scope `/captura`, display standalone
- `public/pwa/sw.js` — Cache-first assets, Network-first páginas
- `public/img/icons/` — 8 tamaños generados con GD (fondo naranja #E8710A)
- Comando para regenerar iconos: `php artisan pwa:icons`
- SW registrado en `resources/js/app.js`

---

## CSS — clases utilitarias (app.css)

```css
.container-amm   /* max-w-[1280px] mx-auto px-4 */
.section-py      /* py-[120px] */
.bg-page         /* bg-gray-50 dark:bg-[#111111] */
.bg-card         /* bg-white dark:bg-[#1a1a1a] */
.bg-card2        /* bg-gray-100 dark:bg-[#242424] */
.border-base     /* border-gray-200 dark:border-[#2e2e2e] */
.text-base       /* text-gray-900 dark:text-[#f0f0f0] */
.text-muted      /* text-gray-500 dark:text-[#888888] */
.btn-primary     /* naranja, text-base, px-6 py-3 rounded-xl */
.btn-outline     /* transparente con borde, text-base */
.btn-ghost       /* sin borde, hover sutil */
.input-amm       /* input para paneles (adapta dark/light) */
.input-amm-dark  /* input para auth (siempre dark) */
.label-amm       /* label para paneles */
.label-amm-dark  /* label para auth */
.badge-cert      /* verde — certificado */
.badge-nocert    /* ámbar — sin certificar */
.badge-apt       /* azul — apartado */
.badge-sold      /* rojo — vendido */
```

---

## Imágenes del sitio

```
public/img/
├── logo_amm.png              ← Logo oficial (200px en navbar/footer)
└── banners/
    ├── banner01.jpg           ← Hero slider slide 1
    ├── banner02.jpg           ← Hero slider slide 2
    ← banner03.jpg           ← Hero slider slide 3
    └── agencia.jpg            ← Fondo parallax sección CTA agencias
```

---

## Estructura de layouts

```
resources/views/layouts/
├── app.blade.php      ← Público (navbar + footer, dark mode, 1280px)
├── guest.blade.php    ← Auth (split: branding izq | form der, full-width bg)
├── agencia.blade.php  ← Panel agencia (sidebar + topbar, max-w-[1280px])
├── admin.blade.php    ← Panel admin (sidebar + topbar, max-w-[1280px])
├── captura.blade.php  ← PWA captura móvil
└── vendedor.blade.php ← PWA vendedor móvil (mismo patrón que captura, max-w-lg centrado)
```

---

## Repositorio GitHub

- URL: `https://github.com/autosmotosymas/am-`
- Branch principal: `main`
- Push con PAT (Personal Access Token) — no usar cuenta ideasreward2018

---

## Servidor / Deploy

### Hosting compartido (autosmotosymas.com.mx:2083)
- cPanel con PHP 8.2 disponible
- **SIN SSH ni Terminal** — NO viable para Laravel
- Usar solo para gestionar el correo (SMTP en mco10.prodns.mx)

### VPS DigitalOcean — **ACTIVO**
- IP: `159.89.239.220`
- OS: Ubuntu 24.04 LTS
- Usuario: `root`
- Stack instalado: Nginx 1.24 + PHP 8.4-FPM + MySQL 8.0 + Composer 2 + Node 20 + Certbot
- App en: `/var/www/amm`
- DB: `amm_db` / usuario: `amm_user` / password: `AmmDB@Prod2025!`
- Queue worker: servicio systemd `amm-queue.service` (activo)
- Scheduler: cron `* * * * *` corriendo `php8.4 artisan schedule:run`
- Certbot: certificado Let's Encrypt instalado, auto-renovación activa
- Cloudflare API token para renovación DNS: guardado en `/root/.secrets/cloudflare.ini`

### DNS — Cloudflare
- Dominio: `autosmotosymas.mx`
- A record: `autosmotosymas.mx` → `159.89.239.220` (Proxied)
- CNAME: `www` → `autosmotosymas.mx` (Proxied)
- SSL/TLS mode: Full (strict)
- Certificado Let's Encrypt válido hasta 2026-08-20

---

## Path del proyecto local

`/Users/alejandrolira/Sites/amm`

## Comandos útiles

```bash
php artisan serve              # servidor local puerto 8000
npm run dev                    # Vite en watch mode
php artisan migrate:status     # ver estado de tablas
php artisan queue:work         # procesar jobs
php artisan db:seed            # correr seeders
php artisan stripe:sync-plans  # sincronizar precios con Stripe
php artisan pwa:icons          # regenerar iconos PWA
lsof -ti:8000 | xargs kill     # matar proceso en puerto 8000
```

---

## URL de producción

**https://autosmotosymas.mx** — LIVE con SSL ✅

---

## ✅ Estado actual — LO QUE ESTÁ HECHO

### Backend
- [x] Laravel 13 + todos los paquetes instalados
- [x] 18 migraciones + migración Stripe columns corridas
- [x] Todos los modelos con relaciones y traits (HasSlug, etc.)
- [x] Seeders: PlanesSeeder con Básico y Premium
- [x] Spatie roles y permisos configurados (4 roles)
- [x] Stripe: StripeService, WebhookController, SuscripcionController
- [x] Stripe planes sincronizados con price IDs en BD
- [x] Jobs de notificación: CambioPrecio, CambioStatus, NuevoLead, SuscripcionVence
- [x] Scheduler configurado (daily 9am CDMX)
- [x] Mail SMTP configurado y probado
- [x] PWA: manifest.json, sw.js, iconos generados

### Frontend — Vistas públicas
- [x] Home: hero slider infinito (3 banners + clon), secciones 120px padding, CTA agencias con parallax y overlay
- [x] Búsqueda: filtros sidebar (móvil drawer + desktop), chips filtros activos, ordenamiento, paginación
- [x] Ficha de vehículo: galería con thumbnails, especificaciones, certificación, formulario lead, agencia card, relacionados
- [x] Perfil de agencia pública: header con contacto, inventario en grid

### Frontend — Auth
- [x] Login, Register, Forgot Password, Reset Password, Verify Email, Confirm Password — todos en estilo AMM (split layout)

### Frontend — Paneles
- [x] Layout agencia: sidebar con nav, topbar, dark mode toggle
- [x] Layout admin: sidebar con nav, topbar
- [x] Agencia: dashboard, inventario CRUD, leads, estadísticas, suscripción, éxito pago
- [x] Admin: dashboard, agencias (index/show/create), verificadores (index/show/form), certificaciones (index/create/edit), suscripciones (index/create/edit)
- [x] Captura PWA: index y nuevo vehículo
- [x] Perfil del comprador

### Frontend — Paneles vendedor (NUEVO — jun 2026)
- [x] Layout vendedor: PWA móvil, bottom nav, back button, header action slot
- [x] Dashboard vendedor: lista de agencias propias
- [x] Alta de agencia: crea Agencia + User (rol agencia) en un solo form con email/password
- [x] Show/edit agencia: status, stats, planes Stripe, editar perfil (logo/banner/desc), cambiar acceso (email/password del cliente)
- [x] Inventario de vehículos: lista con foto, info, status badges y botones de cambio de status
- [x] Alta de vehículo: form completo con catálogo marca/modelo (config/catalogo.php) + Alpine.js selectores en cascada
- [x] Editar vehículo: form pre-llenado, con tarjeta de acceso a gestión de fotos
- [x] Gestión de fotos vendedor: drag-and-drop SortableJS (vanilla JS), agregar, eliminar, principal
- [x] Admin: panel de vendedores (index/create/store/show/destroy)

### Frontend — Paneles agencia (mejoras jun 2026)
- [x] Gestión de fotos por vehículo: drag-and-drop SortableJS, botón 📷 en inventario
- [x] Leads del dashboard clickeables: van a leads.index con ancla al lead específico

### Frontend — Componentes
- [x] `tarjeta-vehiculo` — card de vehículo reutilizable
- [x] `paginacion` — paginación estilizada
- [x] `chip-filtro` — chips de filtros activos con X para quitar
- [x] `_agencia-card` — card de agencia en ficha de vehículo
- [x] `selector-vehiculo` — selectores en cascada tipo/marca/modelo/año (Alpine.js, usa config/catalogo.php)

### Sistema de diseño
- [x] Tailwind con paleta de marca (#E8710A + #111111)
- [x] Dark/light mode completo con anti-flash script
- [x] Todos los textos en text-base (jerarquía consistente)
- [x] Todos los layouts limitados a 1280px
- [x] Clases utilitarias: section-py, input-amm, label-amm, btn-*, badges

---

## ⏳ PENDIENTE — En orden de prioridad

### 1. Sandbox / pruebas — COMPLETADO ✅
- [x] Servidor VPS DigitalOcean activo con stack completo
- [x] Dominio autosmotosymas.mx apuntando al VPS con SSL
- [x] Stripe en modo TEST con claves + webhook configurados
- [x] Webhook funcionando end-to-end: pago → suscripción activa → agencia.activo = true
- [x] Usuario admin en producción: alejandro@vml.mx
- [x] Agencia de prueba (Cuacua Automotriz) registrada, suscripción activa, autos capturados

### 2. Stripe — pasar a LIVE (post-pruebas)
- [ ] Cambiar a claves LIVE de Stripe (reemplazar pk_test / sk_test)
- [ ] Registrar webhook LIVE: `https://autosmotosymas.mx/stripe/webhook`
- [ ] Copiar `STRIPE_WEBHOOK_SECRET` LIVE al .env del servidor
- [ ] Sincronizar planes en producción: `php artisan stripe:sync-plans`

### 3. Contenido inicial
- [ ] Dar de alta agencias reales
- [ ] Capturar inventario inicial de vehículos

### 3. Auto-registro de agencias (Opción A — pendiente)
- [ ] Flujo de auto-registro: agencia llena form público → paga Stripe → accede a su panel
- [ ] Sin depender del vendedor para el alta

### 4. Certificaciones
- [ ] UI para solicitar certificación desde panel agencia
- [ ] Flujo verificador: agenda → inspección → resultado con checklist

### 5. Funcionalidad adicional (post-MVP)
- [ ] Seguimientos de vehículos (tabla `seguimientos` existe, falta UI y lógica)
- [ ] Notificaciones push PWA (tabla existe, falta implementación)
- [ ] Categorías por marca y tipo (rutas SEO: `/autos/marca/{marca}`)
- [ ] Páginas legales: aviso de privacidad, términos y condiciones
- [ ] Stripe LIVE: cambiar claves test → live en producción

---

## 🔧 Configuración .env clave

### Local
```env
APP_ENV=local
APP_URL=http://127.0.0.1:8000
DB_DATABASE=amm_db
QUEUE_CONNECTION=database
STRIPE_KEY=pk_test_51GqLY5L42wZLmAlakJALM3E3x...
STRIPE_SECRET=sk_test_51GqLY5L42wZLmAlaEUE9D7l5...
STRIPE_WEBHOOK_SECRET=           ← usar Stripe CLI para test local
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mco10.prodns.mx
MAIL_PORT=465
```

### Servidor (VPS 159.89.239.220)
```env
APP_ENV=production
APP_URL=https://autosmotosymas.mx
DB_DATABASE=amm_db
DB_USERNAME=amm_user
DB_PASSWORD=AmmDB@Prod2025!
QUEUE_CONNECTION=database
STRIPE_KEY=pk_test_51GqLY5L42wZLmAlakJALM3E3x...  ← cambiar a LIVE
STRIPE_SECRET=sk_test_51GqLY5L42wZLmAlaEUE9D7l5... ← cambiar a LIVE
STRIPE_WEBHOOK_SECRET=           ← PENDIENTE webhook test
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=mco10.prodns.mx
MAIL_PORT=465
MAIL_PASSWORD=                   ← PENDIENTE
```

### Comandos en servidor
```bash
# Conectar al servidor
ssh root@159.89.239.220

# Editar .env
nano /var/www/amm/.env && cd /var/www/amm && php8.4 artisan config:cache

# Ver logs
tail -f /var/www/amm/storage/logs/laravel.log

# Estado del queue worker
systemctl status amm-queue

# Deploy estándar (sin migraciones)
cd /var/www/amm && git pull && php8.4 artisan config:cache && php8.4 artisan route:cache && php8.4 artisan view:clear

# Deploy con migraciones
cd /var/www/amm && git pull && php8.4 artisan migrate --force && php8.4 artisan config:cache && php8.4 artisan route:cache && php8.4 artisan view:clear && npm run build && systemctl restart amm-queue

# IMPORTANTE: NO existe php artisan view:cache — usar view:clear
```

### Notas técnicas importantes
- `view:cache` NO existe en Laravel — siempre usar `view:clear`
- Route model binding usa **slug** en Agencia y Vehiculo — URLs de JS deben usar `{{ $model->slug }}`
- **Alpine.js y SortableJS no mezclar** — Alpine en contenedor padre bloquea drag events de Sortable
- Spatie middleware aliases deben estar en `bootstrap/app.php` (no en Kernel — Laravel 11)
- CSRF exclusión del webhook en `bootstrap/app.php`: `$middleware->validateCsrfTokens(except: ['stripe/webhook'])`
