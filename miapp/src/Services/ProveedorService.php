<?php
/**
 * ProveedorService - Sistema de Repuestos de Vehículos
 * Capa de Negocio - Lógica de negocio para proveedores
 */

namespace App\Services;

use App\Models\Proveedor;
use App\Repositories\ProveedorRepository;

class ProveedorService {
    private $proveedorRepository;
    
    public function __construct() {
        $this->proveedorRepository = new ProveedorRepository();
    }
    
    /**
     * Obtener todos los proveedores con paginación (RF11)
     */
    public function getAllProveedores($page = 1, $limit = ITEMS_POR_PAGINA) {
        $proveedores = $this->proveedorRepository->findAll($page, $limit);
        $total = $this->proveedorRepository->count();
        
        return [
            'proveedores' => $proveedores,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }
    
    /**
     * Buscar proveedores
     */
    public function searchProveedores($term, $page = 1, $limit = ITEMS_POR_PAGINA) {
        $proveedores = $this->proveedorRepository->search($term, $page, $limit);
        $total = $this->proveedorRepository->countSearch($term);
        
        return [
            'proveedores' => $proveedores,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page,
            'search_term' => $term
        ];
    }
    
    /**
     * Obtener proveedor por ID
     */
    public function getProveedorById($id) {
        if (empty($id) || !is_numeric($id)) {
            throw new \Exception('ID de proveedor inválido');
        }
        
        $proveedor = $this->proveedorRepository->findById($id);
        if (!$proveedor) {
            throw new \Exception('Proveedor no encontrado');
        }
        
        return $proveedor;
    }
    
    /**
     * Crear nuevo proveedor (RF11)
     */
    public function createProveedor($data) {
        // Validar datos requeridos
        if (empty($data['nombre'])) {
            throw new \Exception('El nombre del proveedor es requerido');
        }
        // Sanitizar teléfono (opcional simple)
        if (!empty($data['telefono'])) {
            $tel = preg_replace('/[^0-9+\-()\s]/', '', $data['telefono']);
            $data['telefono'] = $tel;
        }
        
        // Verificar si el nombre ya existe
        if ($this->proveedorRepository->nombreExists($data['nombre'])) {
            throw new \Exception('Ya existe un proveedor con ese nombre');
        }
        
        // Verificar si el email ya existe (si se proporciona)
        if (!empty($data['email']) && $this->proveedorRepository->emailExists($data['email'])) {
            throw new \Exception('Ya existe un proveedor con ese email');
        }
        
        // Crear instancia del proveedor
        $proveedor = new Proveedor();
        $proveedor->setNombre($data['nombre'])
                  ->setContacto($data['contacto'] ?? '')
                  ->setTelefono($data['telefono'] ?? '')
                  ->setEmail($data['email'] ?? '')
                  ->setDireccion($data['direccion'] ?? '')
                  ->setActivo($data['activo'] ?? true);
        
        // Validar el proveedor
        $errors = $proveedor->validate();
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
        
        // Guardar en la base de datos
        return $this->proveedorRepository->create($proveedor);
    }
    
    /**
     * Actualizar proveedor (RF11)
     */
    public function updateProveedor($id, $data) {
        // Obtener proveedor existente
        $proveedor = $this->getProveedorById($id);
        
        // Actualizar datos
        if (isset($data['nombre'])) {
            // Verificar si el nuevo nombre ya existe (excluyendo el proveedor actual)
            if ($data['nombre'] !== $proveedor->getNombre() && 
                $this->proveedorRepository->nombreExists($data['nombre'], $id)) {
                throw new \Exception('Ya existe un proveedor con ese nombre');
            }
            $proveedor->setNombre($data['nombre']);
        }
        
        if (isset($data['contacto'])) {
            $proveedor->setContacto($data['contacto']);
        }
        
        if (isset($data['telefono'])) {
            $tel = preg_replace('/[^0-9+\-()\s]/', '', $data['telefono']);
            $proveedor->setTelefono($tel);
        }
        
        if (isset($data['email'])) {
            // Verificar si el nuevo email ya existe (excluyendo el proveedor actual)
            if ($data['email'] !== $proveedor->getEmail() && 
                $this->proveedorRepository->emailExists($data['email'], $id)) {
                throw new \Exception('Ya existe un proveedor con ese email');
            }
            $proveedor->setEmail($data['email']);
        }
        
        if (isset($data['direccion'])) {
            $proveedor->setDireccion($data['direccion']);
        }
        
        if (isset($data['activo'])) {
            $proveedor->setActivo($data['activo']);
        }
        
        // Validar el proveedor actualizado
        $errors = $proveedor->validate();
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
        
        // Guardar cambios
        return $this->proveedorRepository->update($proveedor);
    }
    
    /**
     * Eliminar proveedor (RF11)
     */
    public function deleteProveedor($id) {
        $this->getProveedorById($id);
        
        return $this->proveedorRepository->delete($id);
    }
    
    /**
     * Obtener proveedores con estadísticas
     */
    public function getProveedoresWithStats($page = 1, $limit = ITEMS_POR_PAGINA) {
        $proveedores = $this->proveedorRepository->findAllWithStats($page, $limit);
        $total = $this->proveedorRepository->count();
        
        return [
            'proveedores' => $proveedores,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }
    
    /**
     * Obtener estadísticas de proveedores
     */
    public function getProveedorStats() {
        return $this->proveedorRepository->getDetailedStats();
    }
    
    /**
     * Obtener proveedores más utilizados
     */
    public function getProveedoresMasUtilizados($limit = 10) {
        return $this->proveedorRepository->getMasUtilizados($limit);
    }
    
    /**
     * Obtener todos los proveedores para selección (RF12)
     */
    public function getAllProveedoresForSelect() {
        return $this->proveedorRepository->findAll(1, 1000); // Obtener todos
    }
    
    /**
     * Verificar si un proveedor puede ser eliminado
     */
    public function canDeleteProveedor($id) {
        try {
            $proveedor = $this->getProveedorById($id);
            
            // Verificar si tiene movimientos de inventario asociados
            $sql = "SELECT COUNT(*) as count FROM movimientos_inventario WHERE proveedor_id = ?";
            $db = \App\Core\Database::getInstance();
            $stmt = $db->query($sql, [$id]);
            $result = $stmt->fetch();
            
            return (int)$result['count'] === 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Obtener historial de compras de un proveedor
     */
    public function getHistorialCompras($proveedorId, $page = 1, $limit = ITEMS_POR_PAGINA) {
        $proveedor = $this->getProveedorById($proveedorId);
        
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT m.*, r.nombre as repuesto_nombre, r.codigo as repuesto_codigo,
                       u.nombre as usuario_nombre
                FROM movimientos_inventario m
                LEFT JOIN repuestos r ON m.repuesto_id = r.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.proveedor_id = ? AND m.tipo = 'entrada'
                ORDER BY m.fecha_movimiento DESC 
                LIMIT ? OFFSET ?";
        
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query($sql, [$proveedorId, $limit, $offset]);
        $data = $stmt->fetchAll();
        
        // Contar total
        $countSql = "SELECT COUNT(*) as total FROM movimientos_inventario WHERE proveedor_id = ? AND tipo = 'entrada'";
        $countStmt = $db->query($countSql, [$proveedorId]);
        $total = $countStmt->fetch()['total'];
        
        return [
            'proveedor' => $proveedor,
            'movimientos' => $data,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }
}
