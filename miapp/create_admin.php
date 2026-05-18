<?php
/**
 * Script para crear usuario administrador
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/src/Core/Database.php';

$hash = '$2y$10$l7OGHlQGFrGGB5TjhxbcR.oGY3D5QIgy9noJfPA5eYZFCUnNR0qam'; // admin123

try {
    $db = \App\Core\Database::getInstance();
    
    // Verificar si ya existe el usuario admin
    $stmt = $db->query("SELECT id FROM usuarios WHERE email = ?", ['admin@repuestos.com']);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        // Actualizar contraseña del usuario existente
        $stmt = $db->query(
            "UPDATE usuarios SET password = ?, activo = 1 WHERE email = ?",
            [$hash, 'admin@repuestos.com']
        );
        echo "✓ Usuario admin actualizado correctamente\n";
    } else {
        // Crear nuevo usuario admin
        $stmt = $db->query(
            "INSERT INTO usuarios (nombre, email, password, rol, activo, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            ['Administrador', 'admin@repuestos.com', $hash, 'administrador', 1]
        );
        echo "✓ Usuario admin creado correctamente\n";
    }
    
    echo "\nCredenciales de acceso:\n";
    echo "Email: admin@repuestos.com\n";
    echo "Password: admin123\n\n";
    echo "Ahora puedes iniciar sesión en: http://localhost:8000/\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}