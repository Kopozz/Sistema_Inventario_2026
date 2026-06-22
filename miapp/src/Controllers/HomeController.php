<?php
/**
 * HomeController - Punto de entrada inicial
 * Redirige según estado de autenticación.
 */
namespace App\Controllers;

class HomeController {
    public function index() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        } else {
            $this->redirect('/dashboard');
        }
    }

    public function migrar() {
        try {
            $db = \App\Core\Database::getInstance();
            
            // Check if column already exists
            $stmt = $db->query("SHOW COLUMNS FROM repuestos LIKE 'imagen'");
            $column = $stmt->fetch();
            
            if (!$column) {
                $db->query("ALTER TABLE repuestos ADD COLUMN imagen VARCHAR(255) NULL AFTER activo");
                echo "<h2>Base de datos actualizada con éxito. La columna 'imagen' ha sido agregada a la tabla 'repuestos'.</h2>";
            } else {
                echo "<h2>La base de datos ya está actualizada. La columna 'imagen' ya existe.</h2>";
            }
            echo "<p><a href='" . BASE_URL . "'>Ir al Inicio</a></p>";
        } catch (\Exception $e) {
            echo "<h2>Error al actualizar la base de datos:</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        }
    }

    private function redirect($path) {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }
}
