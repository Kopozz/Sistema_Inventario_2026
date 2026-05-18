<?php
// Cabeceras de seguridad robustas para evitar bloqueos de firewalls (como FortiGuard) y proteger contra ataques
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com; img-src 'self' data: https:;");

/**
 * Router Principal - Sistema de Repuestos de Vehículos
 * Punto de entrada único de la aplicación
 */

// Si la petición es para un archivo estático real, retornar false para que el servidor incorporado de PHP lo sirva directamente.
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($filePath)) {
        return false;
    }
}

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir constantes primero (necesarias para rutas usadas en el autoloader)
require_once __DIR__ . '/../config/constants.php';

// Incluir autoloader (usa SRC_PATH definido en constants)
require_once __DIR__ . '/../src/autoloader.php';

// Incluir configuración de base de datos (clase Database)
require_once __DIR__ . '/../config/database.php';

// Iniciar sesión
session_start();

// Obtener la ruta solicitada
$requestUri = urldecode($_SERVER['REQUEST_URI']);
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remover el directorio base si existe
$basePath = dirname($scriptName);
$basePath = str_replace('\\', '/', $basePath);
if ($basePath !== '/') {
    if (strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
}

// Limpiar la ruta
$requestUri = parse_url($requestUri, PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');
// Asegurar que siempre comience con /
if (empty($requestUri) || $requestUri[0] !== '/') {
    $requestUri = '/' . $requestUri;
}
// Normalizar posibles variantes
if ($requestUri === '/index.php') {
    $requestUri = '/';
}
// Si la ruta contiene index.php al inicio (como fallback cuando no hay mod_rewrite activo)
if (strpos($requestUri, '/index.php') === 0) {
    $requestUri = substr($requestUri, 10);
    if (empty($requestUri) || $requestUri[0] !== '/') {
        $requestUri = '/' . $requestUri;
    }
}

// Router simple
try {
    $router = new \App\Core\Router();
    $router->handleRequest($requestUri, $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo "Error: " . $e->getMessage();
    
    // En desarrollo, mostrar más detalles
    if (APP_DEBUG) {
        echo "<br>Archivo: " . $e->getFile();
        echo "<br>Línea: " . $e->getLine();
        echo "<br>Trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
}
