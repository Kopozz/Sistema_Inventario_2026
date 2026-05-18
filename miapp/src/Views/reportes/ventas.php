<?php 
$content = ob_start();
// Construir URL de exportación preservando filtros de fecha actuales
$exportUrl = BASE_URL . 'reportes/exportar-ventas'
    . (!empty($fecha_inicio) ? '?fecha_inicio=' . urlencode($fecha_inicio) : '')
    . (!empty($fecha_fin)    ? (!empty($fecha_inicio) ? '&' : '?') . 'fecha_fin=' . urlencode($fecha_fin) : '');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-line me-2 text-primary"></i>Reporte de Ventas</h2>
    <div class="d-flex gap-2">
        <a href="<?= $exportUrl ?>" class="btn btn-success">
            <i class="fas fa-file-csv me-1"></i>Exportar CSV
        </a>
        <a href="<?= BASE_URL ?>ventas" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Ventas
        </a>
    </div>
</div>

<?php if (!empty($success)): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check me-2"></i><?= htmlspecialchars($success) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Desde</label>
                <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>" class="form-control" />
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Hasta</label>
                <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>" class="form-control" />
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filtrar</button>
            </div>
            <div class="col-md-2">
                <a href="<?= BASE_URL ?>reportes/ventas" class="btn btn-outline-secondary w-100"><i class="fas fa-times me-1"></i>Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Resumen Diario -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-calendar-day me-2 text-primary"></i>Resumen Diario</h6>
                <span class="badge bg-primary"><?= count($resumenDiario) ?> días</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Fecha</th><th class="text-end">Ventas</th><th class="text-end">Subtotal</th><th class="text-end">Desc.</th><th class="text-end fw-bold">Total</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($resumenDiario)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i>Sin datos para el período seleccionado</td></tr>
                            <?php else: ?>
                                <?php $grandTotal = 0; foreach ($resumenDiario as $r): $grandTotal += ($r['monto'] ?? 0); ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['fecha']) ?></td>
                                    <td class="text-end"><?= (int)$r['cantidad'] ?></td>
                                    <td class="text-end">S/ <?= number_format($r['subtotal']   ?? 0, 2) ?></td>
                                    <td class="text-end text-danger">S/ <?= number_format($r['descuentos'] ?? 0, 2) ?></td>
                                    <td class="text-end fw-bold text-success">S/ <?= number_format($r['monto'] ?? 0, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-light fw-bold">
                                    <td colspan="4" class="text-end">TOTAL PERÍODO:</td>
                                    <td class="text-end text-success">S/ <?= number_format($grandTotal, 2) ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Semanal -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-calendar-week me-2 text-secondary"></i>Resumen Semanal</h6>
                <span class="badge bg-secondary">Últimas semanas</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Semana</th><th class="text-end">Ventas</th><th class="text-end fw-bold">Total</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($resumenSemanal)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i>Sin datos</td></tr>
                            <?php else: ?>
                                <?php foreach ($resumenSemanal as $r): ?>
                                <tr>
                                    <td><small><?= htmlspecialchars($r['desde']) ?> → <?= htmlspecialchars($r['hasta']) ?></small></td>
                                    <td class="text-end"><?= (int)$r['cantidad'] ?></td>
                                    <td class="text-end fw-bold text-success">S/ <?= number_format($r['monto'] ?? 0, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include SRC_PATH . '/Views/layouts/app.php';
?>