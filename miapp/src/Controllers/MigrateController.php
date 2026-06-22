<?php
/**
 * MigrateController - Sistema de Repuestos de Vehículos
 * Script de migración seguro para actualizar el esquema de la BD en producción.
 *
 * Acceso: GET /migrate?token=REPUESTOS2026MIGRATE
 *
 * ⚠️  IMPORTANTE: Una vez ejecutada la migración exitosamente,
 *     eliminar o proteger esta ruta en producción.
 */

namespace App\Controllers;

class MigrateController {

    // Token de seguridad (cambiar si se desea más seguridad)
    private const TOKEN = 'REPUESTOS2026MIGRATE';

    public function run() {
        // Verificar token
        $token = $_GET['token'] ?? '';
        if ($token !== self::TOKEN) {
            http_response_code(403);
            echo $this->html('403 Acceso Denegado',
                '<div class="error">❌ Token inválido. Usa: <code>/migrate?token=' . self::TOKEN . '</code></div>');
            return;
        }

        $results = [];
        $errors  = [];
        $tables  = [];

        try {
            // Conectar directamente a la BD usando env vars
            $host     = getenv('DB_HOST')     ?: 'localhost';
            $dbname   = getenv('DB_NAME')     ?: 'repuestos_vehiculos';
            $username = getenv('DB_USER')     ?: 'root';
            $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';

            $pdo = new \PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );
            $results[] = ['type' => 'success', 'msg' => "✅ Conexión exitosa → {$host}/{$dbname}"];

            // -----------------------------------------------
            // MIGRACIÓN 1: columna `imagen` en `repuestos`
            // -----------------------------------------------
            $stmt = $pdo->query("
                SELECT COUNT(*) AS n FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME   = 'repuestos'
                  AND COLUMN_NAME  = 'imagen'
            ");
            if ((int)$stmt->fetchColumn() === 0) {
                $pdo->exec("ALTER TABLE repuestos ADD COLUMN imagen MEDIUMTEXT DEFAULT NULL AFTER activo");
                $results[] = ['type' => 'success', 'msg' => "✅ Migración 1: columna 'imagen' agregada a 'repuestos'"];
            } else {
                $results[] = ['type' => 'info', 'msg' => "ℹ️  Migración 1: columna 'imagen' ya existe en 'repuestos'"];
            }

            // -----------------------------------------------
            // MIGRACIÓN 2: columna `activo` en `usuarios`
            // -----------------------------------------------
            $stmt = $pdo->query("
                SELECT COUNT(*) AS n FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME   = 'usuarios'
                  AND COLUMN_NAME  = 'activo'
            ");
            if ((int)$stmt->fetchColumn() === 0) {
                $pdo->exec("ALTER TABLE usuarios ADD COLUMN activo BOOLEAN DEFAULT TRUE AFTER rol");
                $results[] = ['type' => 'success', 'msg' => "✅ Migración 2: columna 'activo' agregada a 'usuarios'"];
            } else {
                $results[] = ['type' => 'info', 'msg' => "ℹ️  Migración 2: columna 'activo' ya existe en 'usuarios'"];
            }

            // -----------------------------------------------
            // Estado de tablas
            // -----------------------------------------------
            $stmt = $pdo->query("
                SELECT TABLE_NAME, TABLE_ROWS, DATA_LENGTH, INDEX_LENGTH
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                ORDER BY TABLE_NAME
            ");
            $tables = $stmt->fetchAll();

        } catch (\Exception $e) {
            $errors[] = "❌ Error: " . $e->getMessage();
        }

        // -----------------------------------------------
        // Renderizar resultado
        // -----------------------------------------------
        $bodyHtml = '<div class="card"><strong>Resultados:</strong>';
        foreach ($results as $r) {
            $bodyHtml .= '<div class="result ' . $r['type'] . '">' . htmlspecialchars($r['msg']) . '</div>';
        }
        foreach ($errors as $e) {
            $bodyHtml .= '<div class="result error">' . htmlspecialchars($e) . '</div>';
        }
        $bodyHtml .= '</div>';

        if (!empty($tables)) {
            $bodyHtml .= '<div class="card"><strong>Estado de la base de datos:</strong><table><thead><tr><th>Tabla</th><th>Filas</th><th>Datos</th><th>Índices</th></tr></thead><tbody>';
            foreach ($tables as $t) {
                $bodyHtml .= '<tr><td>' . htmlspecialchars($t['TABLE_NAME']) . '</td><td>' . number_format((int)$t['TABLE_ROWS']) . '</td><td>' . number_format((int)($t['DATA_LENGTH'] / 1024), 1) . ' KB</td><td>' . number_format((int)($t['INDEX_LENGTH'] / 1024), 1) . ' KB</td></tr>';
            }
            $bodyHtml .= '</tbody></table></div>';
        }

        $bodyHtml .= '<div class="warning">⚠️ <strong>IMPORTANTE:</strong> Después de verificar que la migración fue exitosa, ya no necesitas volver a ejecutar este script. El sistema funcionará normalmente.</div>';
        $bodyHtml .= '<p style="color:#475569;font-size:0.8rem;margin-top:30px;">Ejecutado: ' . date('Y-m-d H:i:s') . ' UTC</p>';

        echo $this->html('🔧 Migración de Base de Datos', $bodyHtml);
    }

    private function html(string $title, string $body): string {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>{$title} - Sistema Repuestos</title>
    <style>
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;max-width:800px;margin:40px auto;padding:0 20px;background:#0f172a;color:#e2e8f0}
        h1{color:#38bdf8;border-bottom:2px solid #334155;padding-bottom:10px}
        h2{color:#94a3b8;font-size:1rem}
        .card{background:#1e293b;border:1px solid #334155;border-radius:8px;padding:20px;margin:16px 0}
        .result{padding:8px 12px;margin:6px 0;border-radius:4px;font-family:monospace;font-size:.9rem}
        .success{background:#064e3b;border-left:4px solid #10b981}
        .info{background:#1e3a5f;border-left:4px solid #3b82f6}
        .error{background:#7f1d1d;border-left:4px solid #ef4444}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th{background:#334155;padding:10px;text-align:left;font-size:.8rem;color:#94a3b8}
        td{padding:8px 10px;border-bottom:1px solid #334155;font-size:.85rem;font-family:monospace}
        .warning{background:#451a03;border:1px solid #d97706;border-radius:6px;padding:14px;margin-top:20px;font-size:.85rem;color:#fbbf24}
        code{background:#334155;padding:2px 6px;border-radius:3px;font-family:monospace}
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
}
