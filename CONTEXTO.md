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
| Base de datos | MySQL 8 |
| Auth | Laravel Breeze (Blade) |
| Roles/permisos | Spatie Laravel Permission |
| Imágenes | Spatie MediaLibrary + Intervention Image |
| Slugs | Spatie Laravel Sluggable |
| SEO | Artesaos SEOTools |
| Push notifications | laravel-notification-channels/webpush |
| Build | Vite + npm |
| Cola de jobs | Database queue |

---

## Decisiones de diseño tomadas (NO cambiar sin consultar)

- **Dark/Light mode**: clase `dark` en `<html>`. Config: `darkMode: 'class'` en tailwind
- **Container**: máx 1280px centrado — clase CSS `.container-amm`
- **Paleta**: Naranja `#E8710A` + Negro `#111111` (del logo)
- **PWA**: manifest.json + Service Worker con Workbox (pendiente de implementar)
- **Mobile first**: breakpoints Tailwind, diseño desde 390px hacia arriba
- **URLs semánticas con slugs**: `/autos/toyota-corolla-se-cvt-2022` (NO `/ficha.php?id=2`)
- **Route Model Binding** por slug en Vehiculo y Agencia
- **Sin React/Vue** — todo server-side rendering con Blade

---

## Los 4 roles del sistema

| Rol | Acceso | Redirige a |
|-----|--------|-----------|
| `admin` | Todo el sistema | `/admin/dashboard` |
| `agencia` | Su inventario, leads, estadísticas | `/agencia/dashboard` |
| `capturador` | Solo captura de inventario desde móvil | `/captura` |
| `comprador` | Navegar, seguir autos, enviar leads | `/perfil` |

---

## Base de datos — 18 tablas creadas y migradas

### Tablas del negocio
- `planes` — 2 tiers: Básico ($599/mes) y Premium ($1,299/mes)
- `verificadores` — talleres aliados que hacen inspecciones físicas
- `agencias` — lotes y distribuidores registrados
- `vehiculos` — inventario central (con slug, status, precio, specs)
- `vehiculo_fotos` — fotos por vehículo (tipo: exterior/interior/motor/vin/doc)
- `certificaciones` — resultado de inspección física con checklist JSON
- `suscripciones` — contrato agencia↔plan con Conekta ID
- `pagos` — historial de cobros (tarjeta MX / OXXO via Conekta)
- `leads` — mensajes de comprador → agencia (formulario interno, sin WhatsApp)
- `seguimientos` — "seguir este auto" con alertas de precio y status
- `notificaciones` — cola de notifs (email + push PWA)

### Tablas Laravel/paquetes
- `users`, `sessions`, `cache`, `jobs`
- `media` (Spatie MediaLibrary)
- `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions`
- `push_subscriptions`

---

## Estados de un vehículo

```
borrador → publicado → [inspeccion_agendada] → certificado
                ↓                                    ↓
             pausado                              apartado → vendido
```

- `borrador`: capturado, pendiente de revisión de agencia
- `publicado`: visible con badge ámbar "Sin certificar"
- `certificado`: badge verde "✓ Certificado AutosMotosYMás"
- `apartado`: badge azul — en proceso de compra
- `vendido`: sale del catálogo activo
- `pausado`: agencia lo ocultó temporalmente

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

## Estructura de controllers

```
App/Http/Controllers/
├── Publico/
│   ├── HomeController
│   ├── BusquedaController
│   ├── VehiculoController     ← show() usa Route Model Binding por slug
│   ├── AgenciaController      ← show() usa Route Model Binding por slug
│   └── LeadController
├── Perfil/
│   ├── PerfilController
│   └── TemaController         ← AJAX para guardar dark/light en users.tema
├── Agencia/
│   ├── DashboardController
│   ├── VehiculoController     ← CRUD completo del inventario
│   ├── LeadController
│   └── EstadisticasController
├── Captura/
│   └── InventarioController   ← app móvil PWA de captura en campo
└── Admin/
    ├── DashboardController
    ├── AgenciaController
    ├── VerificadorController
    ├── CertificacionController
    └── SuscripcionController
```

---

## Estructura de vistas Blade

```
resources/views/
├── layouts/
│   └── app.blade.php          ← Layout principal (PENDIENTE DE CREAR)
├── components/                ← Componentes reutilizables (PENDIENTE)
├── publico/
│   ├── home/
│   ├── busqueda/
│   ├── vehiculo/
│   └── agencia/
├── perfil/
├── agencia/
│   ├── dashboard/
│   ├── vehiculos/
│   ├── leads/
│   └── estadisticas/
├── captura/
└── admin/
    ├── dashboard/
    ├── agencias/
    ├── verificadores/
    └── suscripciones/
```

---

## SEO — URLs semánticas definidas

```php
// Cara pública
GET /                           → home
GET /busqueda                   → búsqueda con filtros
GET /autos/{vehiculo:slug}      → ficha del vehículo
GET /agencias/{agencia:slug}    → perfil de agencia
GET /autos/marca/{marca}        → categoría por marca (indexable)
GET /autos/tipo/{tipo}          → categoría por tipo
```

---

## Modelos — traits importantes

**Vehiculo.php** usa:
- `HasSlug` de Spatie — genera slug desde marca+modelo+version+anio
- `getRouteKeyName()` retorna `'slug'`
- `doNotGenerateSlugsOnUpdate()` — slug no cambia si editan el vehículo

**Agencia.php** usa:
- `HasSlug` de Spatie — genera slug desde nombre
- `getRouteKeyName()` retorna `'slug'`

---

## Pagos — Conekta

- Método principal: tarjeta MX + OXXO via **Conekta**
- Columna `conekta_sub_id` en `suscripciones` para ID del plan recurrente
- Columna `referencia_externa` en `pagos` para ID de cada cobro
- Webhooks de Conekta confirman pagos automáticamente
- Primeros clientes: cobro manual (columna `metodo = 'manual'` en `pagos`)

---

## Dark mode — implementación

- Tailwind: `darkMode: 'class'`
- Script anti-flash en `<head>` ANTES del CSS (lee localStorage)
- Toggle en navbar: guarda en `localStorage` + AJAX a `/perfil/tema` si logueado
- Columna `users.tema` = `enum('dark','light','system')` default `system`
- Multi-dispositivo: al login se lee de BD y aplica

---

## Jobs de background (queue)

```
NotificarCambioPrecio    → cuando agencia guarda nuevo precio
NotificarCambioStatus    → cuando cambia status del vehículo
NotificarNuevoLead       → nuevo mensaje de comprador → agencia
NotificarSuscripcionVence → cron diario 9am
```

---

## PWA (pendiente de implementar)

- `public/pwa/manifest.json` — nombre, colores, iconos
- `public/pwa/sw.js` — Service Worker con Workbox
- `public/img/icons/` — iconos 192px y 512px del logo
- Color tema: `#E8710A` / Fondo: `#111111`
- Display: `standalone`

---

## Estado actual del proyecto

### ✅ Completado
- Instalación Laravel 13 + todos los paquetes
- 18 migraciones corridas y verificadas
- Estructura de controllers, models, middleware, jobs, requests creada
- Seeders: `PlanesSeeder` con Básico y Premium cargados
- Tailwind v3 configurado con paleta de marca
- `app.css` con clases utilitarias del proyecto

### 🔄 Siguiente paso inmediato
**Crear `resources/views/layouts/app.blade.php`** — el layout principal con:
1. Script anti-flash dark mode en `<head>`
2. Navbar con logo, links, toggle dark/light, botón "Mi cuenta"
3. Slot de contenido con `.container-amm` (max 1280px)
4. Footer
5. Stack Alpine.js y Vite assets

### ⏳ Pendiente (en orden)
1. Layout principal Blade + componentes base
2. Middleware `CheckRol` y `AplicarTema`
3. Cara pública: Home, Búsqueda, Ficha vehículo
4. Auth: Login, Registro, Perfil del comprador
5. Portal agencia: Dashboard, CRUD inventario, Leads
6. App captura móvil (PWA)
7. Panel admin
8. PWA: manifest + service worker
9. Integración Conekta

---

## Path del proyecto
`/Users/alejandrolira/Sites/amm`

## Comandos útiles
```bash
php artisan serve          # servidor local
npm run dev                # Vite en watch mode
php artisan migrate:status # ver estado de tablas
php artisan queue:work     # procesar jobs
php artisan db:seed        # correr seeders
```
