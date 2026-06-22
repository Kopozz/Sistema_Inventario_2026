<?php
/**
 * Endpoint de Migración de Base de Datos
 * Sistema de Repuestos de Vehículos
 *
 * Este archivo es INDEPENDIENTE del framework (no usa Router ni Database class)
 * para poder ejecutarse incluso cuando hay errores de configuración.
 *
 * Acceso: GET /migrate-db?token=REPUESTOS2026MIGRATE
 *
 * ⚠️ ELIMINAR o proteger después de usar en producción.
 */

// Token de seguridad
define('MIGRATION_TOKEN', 'REPUESTOS2026MIGRATE');

$token = $_GET['token'] ?? '';
if ($token !== MIGRATION_TOKEN) {
    http_response_code(403);
    die(renderPage('403 - Acceso Denegado',
        '<div class="error">❌ Token inválido.<br>Usa: <code>/migrate-db?token=REPUESTOS2026MIGRATE</code></div>'
    ));
}

$results = [];
$errors  = [];
$tables  = [];
$envInfo = [];

// Leer configuración
$host     = getenv('DB_HOST')     ?: null;
$dbname   = getenv('DB_NAME')     ?: null;
$username = getenv('DB_USER')     ?: null;
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : null;

// Mostrar qué variables existen (sin mostrar valores sensibles)
$envInfo[] = 'DB_HOST    = ' . ($host     ? '✅ SET (' . $host   . ')' : '❌ NO CONFIGURADA');
$envInfo[] = 'DB_NAME    = ' . ($dbname   ? '✅ SET (' . $dbname . ')' : '❌ NO CONFIGURADA');
$envInfo[] = 'DB_USER    = ' . ($username ? '✅ SET (' . $username . ')' : '❌ NO CONFIGURADA');
$envInfo[] = 'DB_PASSWORD= ' . ($password !== null ? '✅ SET (oculta)' : '❌ NO CONFIGURADA');

if (!$host || !$dbname || !$username) {
    $errors[] = '❌ Faltan variables de entorno de la base de datos. Configúralas en Vercel (ver instrucciones abajo).';
} else {
    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
            $username,
            $password ?? '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT            => 10,
            ]
        );
        $results[] = ['type' => 'success', 'msg' => "✅ Conexión exitosa → {$host}/{$dbname}"];

        // MIGRACIÓN 1: columna imagen en repuestos
        $n = (int) $pdo->query("
            SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'repuestos'
              AND COLUMN_NAME  = 'imagen'
        ")->fetchColumn();

        if ($n === 0) {
            $pdo->exec("ALTER TABLE repuestos ADD COLUMN imagen MEDIUMTEXT DEFAULT NULL AFTER activo");
            $results[] = ['type' => 'success', 'msg' => "✅ Migración 1: columna 'imagen' agregada a 'repuestos'"];
        } else {
            $results[] = ['type' => 'info', 'msg' => "ℹ️  Migración 1: columna 'imagen' ya existe — sin cambios"];
        }

        // MIGRACIÓN 2: columna activo en usuarios
        $n = (int) $pdo->query("
            SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'usuarios'
              AND COLUMN_NAME  = 'activo'
        ")->fetchColumn();

        if ($n === 0) {
            $pdo->exec("ALTER TABLE usuarios ADD COLUMN activo BOOLEAN DEFAULT TRUE AFTER rol");
            $results[] = ['type' => 'success', 'msg' => "✅ Migración 2: columna 'activo' agregada a 'usuarios'"];
        } else {
            $results[] = ['type' => 'info', 'msg' => "ℹ️  Migración 2: columna 'activo' ya existe — sin cambios"];
        }

        // Tablas
        $tables = $pdo->query("
            SELECT TABLE_NAME, TABLE_ROWS, DATA_LENGTH, INDEX_LENGTH
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            ORDER BY TABLE_NAME
        ")->fetchAll();

    } catch (Exception $e) {
        $errors[] = "❌ Error de BD: " . $e->getMessage();
    }
}

// ── Render ──────────────────────────────────────────────────────────────────
$body = '';

// Variables de entorno
$body .= '<div class="card"><strong>Variables de Entorno:</strong>';
foreach ($envInfo as $line) {
    $class = str_contains($line, '✅') ? 'success' : 'error';
    $body .= '<div class="result ' . $class . '">' . htmlspecialchars($line) . '</div>';
}
$body .= '</div>';

// Resultados migración
if (!empty($results) || !empty($errors)) {
    $body .= '<div class="card"><strong>Resultados de Migración:</strong>';
    foreach ($results as $r) {
        $body .= '<div class="result ' . $r['type'] . '">' . htmlspecialchars($r['msg']) . '</div>';
    }
    foreach ($errors as $e) {
        $body .= '<div class="result error">' . htmlspecialchars($e) . '</div>';
    }
    $body .= '</div>';
}

// Tablas
if (!empty($tables)) {
    $body .= '<div class="card"><strong>Tablas en la BD:</strong><table><thead><tr><th>Tabla</th><th>Filas</th><th>Datos</th></tr></thead><tbody>';
    foreach ($tables as $t) {
        $body .= '<tr><td>' . htmlspecialchars($t['TABLE_NAME']) . '</td><td>' . number_format((int)$t['TABLE_ROWS']) . '</td><td>' . number_format((int)($t['DATA_LENGTH'] / 1024), 1) . ' KB</td></tr>';
    }
    $body .= '</tbody></table></div>';
}

// Instrucciones si faltan env vars
if (!$host || !$dbname || !$username) {
    $body .= <<<HTML
<div class="card instructions">
    <strong>📋 Cómo configurar las variables de entorno en Vercel:</strong>
    <ol>
        <li>Entra a <a href="https://vercel.com/dashboard" target="_blank">vercel.com/dashboard</a></li>
        <li>Selecciona tu proyecto <strong>rectificadora-repuestos</strong></li>
        <li>Ve a <strong>Settings → Environment Variables</strong></li>
        <li>Agrega las siguientes variables:</li>
    </ol>
    <table>
        <thead><tr><th>Nombre</th><th>Valor (tu dato de AlwaysData)</th></tr></thead>
        <tbody>
            <tr><td><code>DB_HOST</code></td><td>El host MySQL de AlwaysData (ej: mysql-tuusuario.alwaysdata.net)</td></tr>
            <tr><td><code>DB_NAME</code></td><td>El nombre de tu base de datos</td></tr>
            <tr><td><code>DB_USER</code></td><td>Tu usuario de MySQL</td></tr>
            <tr><td><code>DB_PASSWORD</code></td><td>Tu contraseña de MySQL</td></tr>
        </tbody>
    </table>
    <p>Después de agregar las variables, Vercel hará un nuevo deploy automático.<br>
    Luego recarga esta página para ejecutar la migración.</p>
</div>
HTML;
}

$body .= '<p style="color:#475569;font-size:.8rem;margin-top:20px;">Ejecutado: ' . gmdate('Y-m-d H:i:s') . ' UTC</p>';

echo renderPage('🔧 Migración de Base de Datos', $body);

// ── Helper ───────────────────────────────────────────────────────────────────
function renderPage(string $title, string $body): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{$title}</title>
<style>
*{box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;max-width:900px;margin:40px auto;padding:0 20px;background:#0f172a;color:#e2e8f0}
h1{color:#38bdf8;border-bottom:2px solid #334155;padding-bottom:10px;margin-bottom:4px}
h2{color:#64748b;font-size:.9rem;margin:0 0 20px}
.card{background:#1e293b;border:1px solid #334155;border-radius:8px;padding:20px;margin:16px 0}
.result{padding:8px 12px;margin:5px 0;border-radius:4px;font-family:monospace;font-size:.88rem;word-break:break-all}
.success{background:#064e3b;border-left:4px solid #10b981}
.info{background:#1e3a5f;border-left:4px solid #3b82f6}
.error{background:#7f1d1d;border-left:4px solid #ef4444}
table{width:100%;border-collapse:collapse;margin-top:12px}
th{background:#334155;padding:10px;text-align:left;font-size:.78rem;color:#94a3b8}
td{padding:8px 10px;border-bottom:1px solid #334155;font-size:.83rem;font-family:monospace}
code{background:#334155;padding:2px 6px;border-radius:3px}
a{color:#38bdf8}
.instructions ol{margin:12px 0;padding-left:20px;line-height:1.8}
</style>
</head>
<body>
<h1>{$title}</h1>
<h2>Sistema de Repuestos de Vehículos</h2>
{$body}
</body>
</html>
HTML;
}
