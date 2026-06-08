<?php 
$content = ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Reporte de Stock Bajo</h2>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>reportes/exportar-stock" class="btn btn-success">
            <i class="fas fa-file-csv me-1"></i>Exportar CSV
        </a>
        <a href="<?= BASE_URL ?>repuestos/stock-bajo" class="btn btn-outline-warning">
            <i class="fas fa-bell me-1"></i>Ver Alertas Avanzadas
        </a>
        <a href="<?= BASE_URL ?>reportes" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Reportes
        </a>
    </div>
</div>

<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Resumen estadístico -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-warning bg-opacity-10 border-start border-warning border-4">
            <div class="card-body">
                <p class="text-muted small mb-1 fw-semibold text-uppercase">Total con Stock Bajo</p>
                <h3 class="fw-bold text-warning mb-0"><?= $total ?></h3>
                <small class="text-muted">repuestos por debajo del mínimo</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-danger bg-opacity-10 border-start border-danger border-4">
            <div class="card-body">
                <p class="text-muted small mb-1 fw-semibold text-uppercase">Estado Crítico (≤<?= STOCK_CRITICO_LIMIT ?>)</p>
                <h3 class="fw-bold text-danger mb-0">
                    <?= count(array_filter($repuestos ?? [], fn($r) => $r->getStockActual() <= STOCK_CRITICO_LIMIT)) ?>
                </h3>
                <small class="text-muted">requieren reposición urgente</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-opacity-10 border-start border-info border-4">
            <div class="card-body">
                <p class="text-muted small mb-1 fw-semibold text-uppercase">Exportado el</p>
                <h3 class="fw-bold text-info mb-0 fs-5"><?= date('d/m/Y H:i') ?></h3>
                <small class="text-muted">última actualización</small>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de repuestos -->
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center pt-3">
        <h6 class="fw-bold mb-0"><i class="fas fa-list me-2"></i>Repuestos con Stock Insuficiente</h6>
        <span class="badge bg-warning text-dark"><?= $total ?> registros</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($repuestos)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5>¡Excelente! No hay repuestos con stock bajo</h5>
                <p class="mb-0">Todos los repuestos tienen niveles de stock óptimos.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Stock Mínimo</th>
                            <th class="text-center">Stock Máximo</th>
                            <th class="text-center">Déficit</th>
                            <th class="text-center">Severidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repuestos as $r): 
                            $deficit = $r->getStockMinimo() - $r->getStockActual();
                            $esCritico = $r->getStockActual() <= STOCK_CRITICO_LIMIT;
                        ?>
                        <tr>
                            <td><code class="text-primary"><?= htmlspecialchars($r->getCodigo()) ?></code></td>
                            <td><strong><?= htmlspecialchars($r->getNombre()) ?></strong></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($r->getCategoria() ?? '—') ?></span></td>
                            <td class="text-center">
                                <span class="badge <?= $esCritico ? 'bg-danger' : 'bg-warning text-dark' ?> fs-6">
                                    <?= $r->getStockActual() ?>
                                </span>
                            </td>
                            <td class="text-center text-muted"><?= $r->getStockMinimo() ?></td>
                            <td class="text-center text-muted"><?= $r->getStockMaximo() ?></td>
                            <td class="text-center">
                                <span class="text-danger fw-bold">-<?= max(0, $deficit) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($esCritico): ?>
                                    <span class="badge bg-danger"><i class="fas fa-skull-crossbones me-1"></i>CRÍTICO</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation me-1"></i>BAJO</span>
                                <?php endif; ?>
                            </td>
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
                            <a class="page-link" href="<?= BASE_URL ?>reportes/stock-bajo?page=<?= $i ?>"><?= $i ?></a>
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
