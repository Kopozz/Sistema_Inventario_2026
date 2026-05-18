<?php 
$content = ob_start();
// URL de exportación con filtros actuales
$exportParams = http_build_query(array_filter([
    'tipo'        => $filtro_tipo,
    'fecha_inicio'=> $filtro_fecha_inicio,
    'fecha_fin'   => $filtro_fecha_fin,
]));
$exportUrl = BASE_URL . 'reportes/exportar-movimientos' . ($exportParams ? '?' . $exportParams : '');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-exchange-alt me-2 text-info"></i>Reporte de Movimientos</h2>
    <div class="d-flex gap-2">
        <a href="<?= $exportUrl ?>" class="btn btn-success">
            <i class="fas fa-file-csv me-1"></i>Exportar CSV
        </a>
        <a href="<?= BASE_URL ?>reportes" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Reportes
        </a>
    </div>
</div>

<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tipo de Movimiento</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tipos as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $filtro_tipo === $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Desde</label>
                <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filtro_fecha_inicio) ?>" class="form-control" />
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Hasta</label>
                <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filtro_fecha_fin) ?>" class="form-control" />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filtrar</button>
            </div>
            <div class="col-md-2">
                <a href="<?= BASE_URL ?>reportes/movimientos" class="btn btn-outline-secondary w-100"><i class="fas fa-times me-1"></i>Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de movimientos -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
        <h6 class="fw-bold mb-0"><i class="fas fa-list me-2"></i>Historial de Movimientos</h6>
        <span class="badge bg-info"><?= $total ?> registros</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($movimientos)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>Sin movimientos para los filtros aplicados</h5>
                <p class="mb-2">Intente ampliar el rango de fechas o cambiar el tipo.</p>
                <a href="<?= BASE_URL ?>inventario/entradas" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>Registrar Entrada
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Repuesto</th>
                            <th class="text-center">Cantidad</th>
                            <th>Motivo</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimientos as $m): 
                            $tipo  = method_exists($m,'getTipo') ? $m->getTipo() : ($m['tipo'] ?? '');
                            $cant  = method_exists($m,'getCantidad') ? $m->getCantidad() : ($m['cantidad'] ?? '');
                            $motiv = method_exists($m,'getMotivo') ? $m->getMotivo() : ($m['motivo'] ?? '');
                            $fecha = method_exists($m,'getFechaMovimiento') ? $m->getFechaMovimiento() : ($m['fecha_movimiento'] ?? '');
                            $mid   = method_exists($m,'getId') ? $m->getId() : ($m['id'] ?? '');
                            $rep   = method_exists($m,'getRepuesto') ? ($m->getRepuesto() ?: '—') : ($m['repuesto_nombre'] ?? '—');
                            $prov  = method_exists($m,'getProveedor') ? ($m->getProveedor() ?: '—') : ($m['proveedor_nombre'] ?? '—');
                            $usr   = method_exists($m,'getUsuario') ? ($m->getUsuario() ?: '—') : ($m['usuario_nombre'] ?? '—');
                            $color = $tipo === 'entrada' ? 'success' : ($tipo === 'salida' ? 'danger' : 'info');
                        ?>
                        <tr>
                            <td class="text-muted small"><?= $mid ?></td>
                            <td><span class="badge bg-<?= $color ?>"><?= ucfirst($tipo) ?></span></td>
                            <td><?= htmlspecialchars($rep) ?></td>
                            <td class="text-center fw-bold"><?= $cant ?></td>
                            <td><small class="text-muted"><?= htmlspecialchars($motiv) ?></small></td>
                            <td><small><?= htmlspecialchars($prov) ?></small></td>
                            <td><small><?= htmlspecialchars($usr) ?></small></td>
                            <td><small class="text-muted"><?= $fecha ? date('d/m/Y H:i', strtotime($fecha)) : '—' ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($pages > 1): ?>
            <div class="p-3">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>reportes/movimientos?page=<?= $i ?><?= $filtro_tipo ? '&tipo=' . urlencode($filtro_tipo) : '' ?><?= $filtro_fecha_inicio ? '&fecha_inicio=' . urlencode($filtro_fecha_inicio) : '' ?><?= $filtro_fecha_fin ? '&fecha_fin=' . urlencode($filtro_fecha_fin) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include SRC_PATH . '/Views/layouts/app.php';
?>
