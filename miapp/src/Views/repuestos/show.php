<?php 
$content = ob_start(); 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-cog me-2"></i>Detalles del Repuesto</h2>
    <div>
        <a href="<?= BASE_URL ?>repuestos/<?= $repuesto->getId() ?>/editar" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Editar
        </a>
        <a href="<?= BASE_URL ?>repuestos" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-cog fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= htmlspecialchars($repuesto->getNombre()) ?></h5>
                        <p class="mb-0 text-muted"><?= htmlspecialchars($repuesto->getCodigo()) ?></p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-barcode me-2"></i>Código
                            </label>
                            <p class="form-control-plaintext">
                                <code class="text-primary fs-5"><?= htmlspecialchars($repuesto->getCodigo()) ?></code>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tags me-2"></i>Categoría
                            </label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info fs-6"><?= htmlspecialchars($repuesto->getCategoria()) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <?php if ($repuesto->getDescripcion()): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-align-left me-2"></i>Descripción
                    </label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($repuesto->getDescripcion()) ?></p>
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-dollar-sign me-2"></i>Precio de Compra
                            </label>
                            <p class="form-control-plaintext fs-5 text-success">
                                $<?= number_format($repuesto->getPrecioCompra(), 2) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Precio de Venta
                            </label>
                            <p class="form-control-plaintext fs-5 text-primary">
                                $<?= number_format($repuesto->getPrecioVenta(), 2) ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-boxes me-2"></i>Stock Actual
                            </label>
                            <p class="form-control-plaintext fs-4">
                                <span class="badge <?= $repuesto->getEstadoStockClase() ?> fs-6">
                                    <?= $repuesto->getStockActual() ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-exclamation-triangle me-2"></i>Stock Mínimo
                            </label>
                            <p class="form-control-plaintext fs-5"><?= $repuesto->getStockMinimo() ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-arrow-up me-2"></i>Stock Máximo
                            </label>
                            <p class="form-control-plaintext fs-5"><?= $repuesto->getStockMaximo() ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-toggle-on me-2"></i>Estado
                            </label>
                            <p class="form-control-plaintext">
                                <span class="badge <?= $repuesto->isActivo() ? 'bg-success' : 'bg-secondary' ?> fs-6">
                                    <?= $repuesto->isActivo() ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-chart-line me-2"></i>Margen de Ganancia
                            </label>
                            <p class="form-control-plaintext fs-5">
                                <span class="text-success"><?= number_format($repuesto->getMargenGanancia(), 2) ?>%</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-plus me-2"></i>Fecha de Creación
                            </label>
                            <p class="form-control-plaintext">
                                <?= date('d/m/Y H:i:s', strtotime($repuesto->getCreatedAt())) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-edit me-2"></i>Última Actualización
                            </label>
                            <p class="form-control-plaintext">
                                <?= date('d/m/Y H:i:s', strtotime($repuesto->getUpdatedAt())) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Imagen del Repuesto -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Imagen del Repuesto</h6>
                <?php if ($repuesto->getImagen()): ?>
                <button class="btn btn-sm btn-outline-secondary" onclick="openLightbox()" title="Ampliar imagen">
                    <i class="fas fa-search-plus"></i>
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body text-center p-2">
                <?php if ($repuesto->getImagen()): ?>
                    <?php 
                    $imgPath = $repuesto->getImagen();
                    $imgUrl = '';
                    if (strpos($imgPath, 'data:') === 0) {
                        $imgUrl = $imgPath;
                    } elseif (strlen($imgPath) > 200 && !preg_match('/^https?:\/\//', $imgPath)) {
                        $imgUrl = 'data:image/jpeg;base64,' . $imgPath;
                    } else {
                        $imgUrl = BASE_URL . ltrim($imgPath, '/');
                    }
                    ?>
                    <div style="background:#111; border-radius:8px; overflow:hidden; min-height:200px; display:flex; align-items:center; justify-content:center; cursor:zoom-in;" onclick="openLightbox()">
                        <img src="<?= $imgUrl ?>" 
                             alt="<?= htmlspecialchars($repuesto->getNombre()) ?>" 
                             id="mainRepuestoImg"
                             style="max-width:100%; max-height:360px; object-fit:contain; display:block;"
                             title="Clic para ampliar">
                    </div>
                    <small class="text-muted mt-1 d-block"><i class="fas fa-search-plus me-1"></i>Clic en la imagen para ampliar</small>
                <?php else: ?>
                    <div class="p-5 bg-light text-muted rounded d-flex flex-column align-items-center justify-content-center" style="min-height:200px;">
                        <i class="fas fa-image fa-4x mb-3 text-secondary"></i>
                        <span>Sin imagen asignada</span>
                        <a href="<?= BASE_URL ?>repuestos/<?= $repuesto->getId() ?>/editar" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fas fa-upload me-1"></i>Agregar imagen
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Estado del Stock -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estado del Stock</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <span class="badge <?= $repuesto->getEstadoStockClase() ?> fs-6">
                        <?= $repuesto->getEstadoStockNombre() ?>
                    </span>
                </div>
                
                <div class="progress mb-3" style="height: 20px;">
                    <?php 
                    $porcentaje = $repuesto->getStockMaximo() > 0 ? 
                        ($repuesto->getStockActual() / $repuesto->getStockMaximo()) * 100 : 0;
                    $claseProgreso = $repuesto->isStockCritico() ? 'bg-danger' : 
                                   ($repuesto->isStockBajo() ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="progress-bar <?= $claseProgreso ?>" 
                         style="width: <?= min($porcentaje, 100) ?>%">
                        <?= $repuesto->getStockActual() ?>
                    </div>
                </div>
                
                <small class="text-muted">
                    <?= $repuesto->getStockActual() ?> de <?= $repuesto->getStockMaximo() ?> unidades
                </small>
            </div>
        </div>
        
        <!-- Alertas -->
        <?php if ($repuesto->isStockCritico()): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Stock Crítico</strong><br>
            El stock está por debajo del límite crítico.
        </div>
        <?php elseif ($repuesto->isStockBajo()): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Stock Bajo</strong><br>
            El stock está por debajo del nivel mínimo.
        </div>
        <?php endif; ?>
        
        <!-- Acciones -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Acciones</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>repuestos/<?= $repuesto->getId() ?>/editar" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar Repuesto
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmDelete(<?= $repuesto->getId() ?>, '<?= htmlspecialchars($repuesto->getNombre()) ?>')">
                        <i class="fas fa-trash me-2"></i>Eliminar Repuesto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el repuesto <strong id="repuestoName"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="" id="deleteForm" class="d-inline">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if ($repuesto->getImagen()): ?>
<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background:#000; border:none;">
            <div class="modal-header" style="background:#111; border:none; padding:0.5rem 1rem;">
                <h6 class="modal-title text-white mb-0"><?= htmlspecialchars($repuesto->getNombre()) ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2" style="background:#000;">
                <img src="<?= $imgUrl ?>" 
                     alt="<?= htmlspecialchars($repuesto->getNombre()) ?>" 
                     style="max-width:100%; max-height:80vh; object-fit:contain;">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.appendChild(document.getElementById('deleteModal'));
    const lbModal = document.getElementById('lightboxModal');
    if (lbModal) document.body.appendChild(lbModal);
});

let _lightboxModalInstance = null;
let _deleteModalInstance = null;

function getLightboxModal() {
    const el = document.getElementById('lightboxModal');
    if (!el) return null;
    if (!_lightboxModalInstance) {
        _lightboxModalInstance = new bootstrap.Modal(el, { backdrop: true, keyboard: true });
        el.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        });
    }
    return _lightboxModalInstance;
}

function getDeleteModal() {
    const el = document.getElementById('deleteModal');
    if (!_deleteModalInstance) {
        _deleteModalInstance = new bootstrap.Modal(el, { backdrop: true, keyboard: true });
        el.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        });
    }
    return _deleteModalInstance;
}

function openLightbox() {
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    const m = getLightboxModal();
    if(m) m.show();
}

function confirmDelete(repuestoId, repuestoName) {
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.getElementById('repuestoName').textContent = repuestoName;
    document.getElementById('deleteForm').action = '<?= BASE_URL ?>repuestos/' + repuestoId + '/eliminar';
    getDeleteModal().show();
}
</script>

<?php 
$content = ob_get_clean();
include SRC_PATH . '/Views/layouts/app.php';
?>
