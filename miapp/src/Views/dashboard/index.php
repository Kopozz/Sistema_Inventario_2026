<?php 
$content = ob_start();

// Preparar datos para Chart.js (PHP → JSON seguro)
$cat_labels = [];
$cat_data   = [];
$cat_colors = ['#f08100','#66c0f4','#2ecc71','#e74c3c','#9b59b6','#f1c40f','#1abc9c','#34495e'];
foreach (($chart_categorias ?? []) as $i => $row) {
    $cat_labels[] = $row['categoria'];
    $cat_data[]   = (int)$row['total'];
}

$mov_labels    = [];
$mov_entradas  = [];
$mov_salidas   = [];
$mov_ajustes   = [];
foreach (($chart_movimientos ?? []) as $fecha => $tipos) {
    $mov_labels[]   = date('d/m', strtotime($fecha));
    $mov_entradas[] = $tipos['entrada'];
    $mov_salidas[]  = $tipos['salida'];
    $mov_ajustes[]  = $tipos['ajuste'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</h2>
    <div class="text-muted small">
        <i class="fas fa-calendar me-1"></i>
        <span id="current-datetime"><?= date('d/m/Y H:i:s') ?></span>
    </div>
</div>

<!-- ─── Métricas Principales ─── -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#4361ee,#3a0ca3)">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 small opacity-75 fw-semibold text-uppercase ls-1">Total Repuestos</p>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['total_repuestos'] ?? 0) ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,0.2)">
                        <i class="fas fa-cogs fa-lg"></i>
                    </div>
                </div>
                <p class="mb-0 mt-2 small opacity-75">
                    <i class="fas fa-arrow-right me-1"></i><a href="<?= BASE_URL ?>repuestos" class="text-white text-decoration-none">Ver catálogo</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#06d6a0,#0b8a5c)">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 small opacity-75 fw-semibold text-uppercase">Ventas del Mes</p>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['ventas_mes'] ?? 0) ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,0.2)">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                    </div>
                </div>
                <p class="mb-0 mt-2 small opacity-75">
                    <i class="fas fa-arrow-right me-1"></i><a href="<?= BASE_URL ?>ventas" class="text-white text-decoration-none">Ver ventas</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#f72585,#b5179e)">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 small opacity-75 fw-semibold text-uppercase">Stock Bajo</p>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['stock_bajo'] ?? 0) ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,0.2)">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                </div>
                <p class="mb-0 mt-2 small opacity-75">
                    <i class="fas fa-arrow-right me-1"></i><a href="<?= BASE_URL ?>repuestos/stock-bajo" class="text-white text-decoration-none">Ver alertas</a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#4cc9f0,#0077b6)">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 small opacity-75 fw-semibold text-uppercase">Usuarios Activos</p>
                        <h3 class="fw-bold mb-0"><?= number_format($stats['usuarios_activos'] ?? 0) ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(255,255,255,0.2)">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <p class="mb-0 mt-2 small opacity-75">
                    <i class="fas fa-arrow-right me-1"></i><a href="<?= BASE_URL ?>usuarios" class="text-white text-decoration-none">Gestionar</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ─── Gráficos ─── -->
<div class="row g-4 mb-4">
    <!-- Doughnut: Repuestos por Categoría -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Repuestos por Categoría</h6>
                <small class="text-muted">Distribución actual del inventario</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:260px">
                <?php if (empty($cat_data) || array_sum($cat_data) === 0): ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Sin repuestos registrados aún</p>
                        <a href="<?= BASE_URL ?>repuestos/crear" class="btn btn-sm btn-primary mt-2">Crear primer repuesto</a>
                    </div>
                <?php else: ?>
                    <canvas id="chartCategorias" style="max-height:260px"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barras: Tendencia Movimientos 7 días -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Movimientos — Últimos 7 días</h6>
                <small class="text-muted">Entradas, salidas y ajustes de inventario</small>
            </div>
            <div class="card-body" style="min-height:260px">
                <?php if (empty($mov_labels)): ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted flex-column">
                        <i class="fas fa-history fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Sin movimientos en los últimos 7 días</p>
                        <a href="<?= BASE_URL ?>inventario/entradas" class="btn btn-sm btn-success mt-2">Registrar entrada</a>
                    </div>
                <?php else: ?>
                    <canvas id="chartMovimientos" style="max-height:260px"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ─── Tablas Inferiores ─── -->
<div class="row g-4 mb-4">
    <!-- Stock Bajo -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Repuestos con Stock Bajo</h6>
                <a href="<?= BASE_URL ?>repuestos/stock-bajo" class="btn btn-sm btn-outline-warning">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($repuestos_stock_bajo)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light"><tr><th>Código</th><th>Nombre</th><th class="text-center">Stock</th><th class="text-center">Mínimo</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($repuestos_stock_bajo, 0, 5) as $repuesto): ?>
                                <tr>
                                    <td><code class="text-primary"><?= htmlspecialchars($repuesto['codigo']) ?></code></td>
                                    <td><?= htmlspecialchars($repuesto['nombre']) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $repuesto['stock_actual'] <= STOCK_CRITICO_LIMIT ? 'bg-danger' : 'bg-warning text-dark' ?>">
                                            <?= $repuesto['stock_actual'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-muted"><?= $repuesto['stock_minimo'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="mb-0">¡Todo el stock está en niveles óptimos!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Movimientos Recientes -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-history me-2 text-info"></i>Movimientos Recientes</h6>
                <a href="<?= BASE_URL ?>inventario" class="btn btn-sm btn-outline-info">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($movimientos_recientes)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light"><tr><th>Tipo</th><th>Repuesto</th><th class="text-center">Cant.</th><th>Fecha</th></tr></thead>
                            <tbody>
                                <?php foreach ($movimientos_recientes as $mov): ?>
                                <tr>
                                    <td>
                                        <?php $tc = $mov['tipo'] === 'entrada' ? 'success' : ($mov['tipo'] === 'salida' ? 'danger' : 'info'); ?>
                                        <span class="badge bg-<?= $tc ?>"><?= ucfirst($mov['tipo']) ?></span>
                                    </td>
                                    <td class="small"><?= htmlspecialchars($mov['repuesto_codigo'] ?? $mov['repuesto_nombre'] ?? 'N/A') ?></td>
                                    <td class="text-center"><?= $mov['cantidad'] ?></td>
                                    <td class="small text-muted"><?= date('d/m H:i', strtotime($mov['fecha_movimiento'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Sin movimientos recientes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Ventas Recientes -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
        <h6 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2 text-success"></i>Ventas Recientes</h6>
        <a href="<?= BASE_URL ?>ventas" class="btn btn-sm btn-outline-success">Ver todas</a>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($ventas_recientes)): ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Número</th><th>Cliente</th><th class="text-end">Total</th><th class="text-center">Estado</th><th>Fecha</th></tr></thead>
                    <tbody>
                        <?php foreach ($ventas_recientes as $venta): ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>ventas/<?= $venta['id'] ?>" class="text-decoration-none fw-semibold"><?= htmlspecialchars($venta['numero_venta']) ?></a></td>
                            <td><?= htmlspecialchars($venta['cliente_nombre'] ?: 'Cliente General') ?></td>
                            <td class="text-end fw-bold text-success">S/ <?= number_format($venta['total'], 2) ?></td>
                            <td class="text-center">
                                <span class="badge <?= strtolower($venta['estado']) === 'completada' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= ucfirst($venta['estado']) ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($venta['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p class="mb-0">No hay ventas registradas</p>
                <a href="<?= BASE_URL ?>ventas/crear" class="btn btn-sm btn-success mt-2">Registrar primera venta</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ─── Scripts: Chart.js + reloj ─── -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Reloj en tiempo real
function updateDateTime() {
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    document.getElementById('current-datetime').textContent =
        `${pad(now.getDate())}/${pad(now.getMonth()+1)}/${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}
updateDateTime();
setInterval(updateDateTime, 1000);

// ── Gráfico 1: Doughnut — Repuestos por Categoría ──
<?php if (!empty($cat_data) && array_sum($cat_data) > 0): ?>
(function(){
    const ctx = document.getElementById('chartCategorias');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($cat_labels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                data: <?= json_encode($cat_data) ?>,
                backgroundColor: <?= json_encode(array_slice($cat_colors, 0, count($cat_data))) ?>,
                borderWidth: 2,
                borderColor: '#1c2430',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { 
                        font: { size: 11, family: 'Outfit' }, 
                        color: '#94a3b8',
                        padding: 12, 
                        usePointStyle: true 
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.raw} repuesto${ctx.raw !== 1 ? 's' : ''}`
                    }
                }
            },
            animation: { animateRotate: true, duration: 900 }
        }
    });
})();
<?php endif; ?>

// ── Gráfico 2: Barras — Tendencia Movimientos 7 días ──
<?php if (!empty($mov_labels)): ?>
(function(){
    const ctx = document.getElementById('chartMovimientos');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($mov_labels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [
                {
                    label: 'Entradas',
                    data: <?= json_encode($mov_entradas) ?>,
                    backgroundColor: 'rgba(46,204,113,0.15)',
                    borderColor: '#2ecc71',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Salidas',
                    data: <?= json_encode($mov_salidas) ?>,
                    backgroundColor: 'rgba(231,76,60,0.15)',
                    borderColor: '#e74c3c',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Ajustes',
                    data: <?= json_encode($mov_ajustes) ?>,
                    backgroundColor: 'rgba(102,192,244,0.15)',
                    borderColor: '#66c0f4',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: { 
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#94a3b8', font: { family: 'Inter' } }
                },
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, color: '#94a3b8', font: { family: 'Inter' } }, 
                    grid: { color: 'rgba(255,255,255,0.05)' } 
                }
            },
            plugins: {
                legend: { 
                    position: 'top', 
                    labels: { 
                        usePointStyle: true, 
                        color: '#94a3b8',
                        font: { size: 11, family: 'Outfit' } 
                    } 
                }
            },
            animation: { duration: 800 }
        }
    });
})();
<?php endif; ?>
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>