<?php
/**
 * Configuración de la base de datos.
 * 
 * Define constantes para la conexión PDO.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'frank_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Obtiene una instancia de PDO conectada a la base de datos.
 *
 * @return PDO
 */
function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    return $pdo;
}
