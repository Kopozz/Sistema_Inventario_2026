<?php
namespace App\Controllers;

use App\Services\VentaService;
use App\Services\InventarioService;
use App\Services\RepuestoService;
use App\Core\Flash;

class ReporteController {
    private $ventaService;
    private $inventarioService;
    private $repuestoService;
    private $authController;

    public function __construct() {
        $this->ventaService    = new VentaService();
        $this->inventarioService = new InventarioService();
        $this->repuestoService = new RepuestoService();
        $this->authController  = new AuthController();
    }

    // ─────────────────────────────────────────
    //  VISTAS NORMALES
    // ─────────────────────────────────────────

    public function index() {
        $this->authController->requirePermission('view_reportes');
        header('Location: ' . BASE_URL . 'reportes/ventas');
        exit;
    }

    public function ventas() {
        $this->authController->requirePermission('view_reportes');
        $fechaInicio   = $_GET['fecha_inicio'] ?? '';
        $fechaFin      = $_GET['fecha_fin']    ?? '';
        $resumenDiario  = $this->ventaService->resumenDiario($fechaInicio, $fechaFin);
        $resumenSemanal = $this->ventaService->resumenSemanal();
        $this->render('reportes/ventas', [
            'title'         => 'Reporte de Ventas',
            'resumenDiario'  => $resumenDiario,
            'resumenSemanal' => $resumenSemanal,
            'fecha_inicio'  => $fechaInicio,
            'fecha_fin'     => $fechaFin,
            'success'       => Flash::get('success'),
            'error'         => Flash::get('error'),
        ]);
    }

    public function stockBajo() {
        $this->authController->requirePermission('view_reportes');
        $page     = (int)($_GET['page'] ?? 1);
        try {
            $result   = $this->inventarioService->findStockBajoReporte($page);
            $this->render('reportes/stock-bajo', [
                'title'        => 'Reporte de Stock Bajo',
                'repuestos'    => $result['repuestos'],
                'total'        => $result['total'],
                'pages'        => $result['pages'],
                'current_page' => $result['current_page'],
                'error'        => Flash::get('error'),
            ]);
        } catch (\Exception $e) {
            $this->render('reportes/stock-bajo', [
                'title'        => 'Reporte de Stock Bajo',
                'repuestos'    => [],
                'total'        => 0,
                'pages'        => 0,
                'current_page' => 1,
                'error'        => $e->getMessage(),
            ]);
        }
    }

    public function movimientos() {
        $this->authController->requirePermission('view_reportes');
        $page        = (int)($_GET['page'] ?? 1);
        $tipo        = $_GET['tipo']         ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin    = $_GET['fecha_fin']    ?? '';
        try {
            if (!empty($tipo)) {
                $result = $this->inventarioService->getMovimientosByTipo($tipo, $page);
            } elseif (!empty($fechaInicio) && !empty($fechaFin)) {
                $result = $this->inventarioService->getMovimientosByFechaRange($fechaInicio, $fechaFin, $page);
            } else {
                $result = $this->inventarioService->getAllMovimientos($page);
            }
            $tipos = $this->inventarioService->getTiposMovimiento();
            $this->render('reportes/movimientos', [
                'title'               => 'Reporte de Movimientos',
                'movimientos'         => $result['movimientos'],
                'total'               => $result['total'],
                'pages'               => $result['pages'],
                'current_page'        => $result['current_page'],
                'tipos'               => $tipos,
                'filtro_tipo'         => $tipo,
                'filtro_fecha_inicio' => $fechaInicio,
                'filtro_fecha_fin'    => $fechaFin,
                'error'               => Flash::get('error'),
            ]);
        } catch (\Exception $e) {
            $this->render('reportes/movimientos', [
                'title'               => 'Reporte de Movimientos',
                'movimientos'         => [],
                'total'               => 0,
                'pages'               => 0,
                'current_page'        => 1,
                'tipos'               => [],
                'filtro_tipo'         => '',
                'filtro_fecha_inicio' => '',
                'filtro_fecha_fin'    => '',
                'error'               => $e->getMessage(),
            ]);
        }
    }

    // ─────────────────────────────────────────
    //  EXPORTACIONES CSV (RF18)
    // ─────────────────────────────────────────

    public function exportarVentas() {
        $this->authController->requirePermission('view_reportes');
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin    = $_GET['fecha_fin']    ?? '';
        $datos = $this->ventaService->resumenDiario($fechaInicio, $fechaFin);

        $filename = 'reporte_ventas_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo "\xEF\xBB\xBF"; // BOM UTF-8 para Excel

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Fecha', 'Cantidad Ventas', 'Subtotal (S/)', 'Descuentos (S/)', 'Total (S/)'], ';');
        $totVentas = 0; $totMonto = 0;
        foreach ($datos as $r) {
            fputcsv($out, [
                $r['fecha'],
                (int)$r['cantidad'],
                number_format($r['subtotal']   ?? 0, 2, '.', ''),
                number_format($r['descuentos'] ?? 0, 2, '.', ''),
                number_format($r['monto']      ?? 0, 2, '.', ''),
            ], ';');
            $totVentas += (int)$r['cantidad'];
            $totMonto  += ($r['monto'] ?? 0);
        }
        fputcsv($out, ['TOTAL', $totVentas, '', '', number_format($totMonto, 2, '.', '')], ';');
        fclose($out);
        exit;
    }

    public function exportarStock() {
        $this->authController->requirePermission('view_reportes');
        // Reutilizamos RepuestoRepository directamente para obtener todos sin paginar
        $db = \App\Core\Database::getInstance();
        $sql = "SELECT r.codigo, r.nombre, c.nombre as categoria,
                       r.stock_actual, r.stock_minimo, r.stock_maximo,
                       r.precio_compra, r.precio_venta
                FROM repuestos r
                LEFT JOIN categorias c ON r.categoria_id = c.id
                WHERE r.activo = 1 AND r.stock_actual <= r.stock_minimo
                ORDER BY r.stock_actual ASC";
        try {
            $stmt = $db->query($sql);
            $datos = $stmt->fetchAll();
        } catch (\Exception $e) {
            $datos = [];
        }

        $filename = 'reporte_stock_bajo_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo "\xEF\xBB\xBF";

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Código', 'Nombre', 'Categoría', 'Stock Actual', 'Stock Mínimo', 'Stock Máximo', 'Precio Compra (S/)', 'Precio Venta (S/)'], ';');
        foreach ($datos as $r) {
            fputcsv($out, [
                $r['codigo'],
                $r['nombre'],
                $r['categoria'],
                $r['stock_actual'],
                $r['stock_minimo'],
                $r['stock_maximo'],
                number_format($r['precio_compra'], 2, '.', ''),
                number_format($r['precio_venta'],  2, '.', ''),
            ], ';');
        }
        fclose($out);
        exit;
    }

    public function exportarMovimientos() {
        $this->authController->requirePermission('view_reportes');
        $tipo        = $_GET['tipo']         ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin    = $_GET['fecha_fin']    ?? '';

        try {
            if (!empty($tipo)) {
                $result = $this->inventarioService->getMovimientosByTipo($tipo, 1, 9999);
            } elseif (!empty($fechaInicio) && !empty($fechaFin)) {
                $result = $this->inventarioService->getMovimientosByFechaRange($fechaInicio, $fechaFin, 1, 9999);
            } else {
                $result = $this->inventarioService->getAllMovimientos(1, 9999);
            }
            $movimientos = $result['movimientos'];
        } catch (\Exception $e) {
            $movimientos = [];
        }

        $filename = 'reporte_movimientos_' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        echo "\xEF\xBB\xBF";

        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Tipo', 'Repuesto', 'Cantidad', 'Motivo', 'Proveedor', 'Usuario', 'Fecha', 'Observaciones'], ';');
        foreach ($movimientos as $m) {
            $tipo_text = method_exists($m, 'getTipo') ? $m->getTipo() : ($m['tipo'] ?? '');
            $cant      = method_exists($m, 'getCantidad') ? $m->getCantidad() : ($m['cantidad'] ?? '');
            $motivo    = method_exists($m, 'getMotivo') ? $m->getMotivo() : ($m['motivo'] ?? '');
            $obs       = method_exists($m, 'getObservaciones') ? $m->getObservaciones() : ($m['observaciones'] ?? '');
            $fecha     = method_exists($m, 'getFechaMovimiento') ? $m->getFechaMovimiento() : ($m['fecha_movimiento'] ?? '');
            $id        = method_exists($m, 'getId') ? $m->getId() : ($m['id'] ?? '');
            $rep       = method_exists($m, 'getRepuesto') ? ($m->getRepuesto() ?: '') : ($m['repuesto_nombre'] ?? '');
            $prov      = method_exists($m, 'getProveedor') ? ($m->getProveedor() ?: '') : ($m['proveedor_nombre'] ?? '');
            $usr       = method_exists($m, 'getUsuario') ? ($m->getUsuario() ?: '') : ($m['usuario_nombre'] ?? '');
            fputcsv($out, [$id, ucfirst($tipo_text), $rep, $cant, $motivo, $prov, $usr, $fecha, $obs], ';');
        }
        fclose($out);
        exit;
    }

    // ─────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────

    private function render($view, $data = []) {
        extract($data);
        $viewPath = SRC_PATH . '/Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo 'Vista no encontrada: ' . $view;
        }
    }
}
