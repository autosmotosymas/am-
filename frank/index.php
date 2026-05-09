<?php
/**
 * Punto de entrada principal del sitio.
 * 
 * Carga la configuración, incluye el header y footer,
 * y muestra el contenido principal.
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';
?>

<main>
    <h1>Bienvenido a Frank</h1>
    <p>Este es el sitio en construcción.</p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
