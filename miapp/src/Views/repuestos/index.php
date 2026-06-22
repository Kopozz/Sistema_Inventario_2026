<?php 
$content = ob_start(); 
?>

<style>
/* ── Repuesto thumbnail clickable ── */
.repuesto-thumb {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid var(--border-subtle, #e0e0e0);
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease;
    display: block;
}
.repuesto-thumb:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 18px rgba(0,0,0,0.18);
}
.no-img-thumb {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    border: 2px dashed var(--border-subtle, #ccc);
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #aaa;
    font-size: 1.6rem;
}

/* ── Preview Modal ── */
#previewModal .modal-dialog {
    max-width: 820px;
}
#previewModal .modal-body {
    padding: 0;
}
.preview-img-wrap {
    background: #111;
    border-radius: 0 0 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 280px;
    max-height: 420px;
    overflow: hidden;
}
.preview-img-wrap img {
    max-width: 100%;
    max-height: 420px;
    object-fit: contain;
    border-radius: 0;
}
.preview-specs {
    padding: 1.4rem 1.6rem;
}
.spec-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.42rem 0;
    border-bottom: 1px solid rgba(0,0,0,.06);
    font-size: 0.93rem;
}
.spec-row:last-child { border-bottom: none; }
.spec-label {
    color: #6c757d;
    font-weight: 500;
    min-width: 130px;
}
.spec-value { font-weight: 600; }
.preview-name {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.1rem;
}
.preview-code {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 1rem;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-cogs me-2"></i>Gestión de Repuestos</h2>
    <div>
        <a href="<?= BASE_URL ?>repuestos/stock-bajo" class="btn btn-warning me-2">
            <i class="fas fa-exclamation-triangle me-2"></i>Stock Bajo
        </a>
        <a href="<?= BASE_URL ?>repuestos/crear" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Repuesto
        </a>
    </div>
</div>

<!-- Filtros de búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>repuestos" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" 
                           value="<?= htmlspecialchars($search_term ?? '') ?>" 
                           placeholder="Buscar por nombre, código o descripción...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="categoria_id">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria->getId() ?>" 
                            <?= (($categoria_id ?? '') == $categoria->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria->getNombre()) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Buscar
                </button>
                <a href="<?= BASE_URL ?>repuestos" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de repuestos -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Repuestos</h5>
        <span class="badge bg-primary"><?= $total ?? 0 ?> repuestos</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($repuestos)): ?>
        <div class="text-center py-5">
            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay repuestos registrados</h5>
            <p class="text-muted">Comience creando el primer repuesto del sistema</p>
            <a href="<?= BASE_URL ?>repuestos/crear" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Repuesto
            </a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="vertical-align: middle;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Imagen</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($repuestos as $repuesto): ?>
                    <?php 
                    $imgPath = $repuesto->getImagen();
                    $imgUrl = '';
                    if ($imgPath) {
                        if (strpos($imgPath, 'data:') === 0) {
                            $imgUrl = $imgPath;
                        } elseif (strlen($imgPath) > 200 && !preg_match('/^https?:\/\//', $imgPath)) {
                            // Raw base64 string from previous uploads without data: prefix
                            $imgUrl = 'data:image/jpeg;base64,' . $imgPath;
                        } else {
                            $imgUrl = BASE_URL . ltrim($imgPath, '/');
                        }
                    }

                    $nombreEnc = htmlspecialchars($repuesto->getNombre());
                    $codigoEnc = htmlspecialchars($repuesto->getCodigo());
                    $catEnc    = htmlspecialchars($repuesto->getCategoria());
                    $descEnc   = htmlspecialchars($repuesto->getDescripcion() ?? '');
                    $pCompra   = number_format($repuesto->getPrecioCompra(), 2);
                    $pVenta    = number_format($repuesto->getPrecioVenta(), 2);
                    $stock     = $repuesto->getStockActual();
                    $sMin      = $repuesto->getStockMinimo();
                    $sMax      = $repuesto->getStockMaximo();
                    $margen    = number_format($repuesto->getMargenGanancia(), 2);
                    $estadoStockNombre = $repuesto->getEstadoStockNombre();
                    $estadoStockClase  = $repuesto->getEstadoStockClase();
                    $activo    = $repuesto->isActivo() ? 'Activo' : 'Inactivo';
                    $activoCls = $repuesto->isActivo() ? 'bg-success' : 'bg-secondary';
                    ?>
                    <tr>
                        <td>
                            <?php if ($imgUrl): ?>
                                <img 
                                    src="<?= $imgUrl ?>" 
                                    alt="<?= $nombreEnc ?>" 
                                    class="repuesto-thumb"
                                    onclick="openPreview(
                                        '<?= addslashes($imgUrl) ?>',
                                        '<?= addslashes($nombreEnc) ?>',
                                        '<?= addslashes($codigoEnc) ?>',
                                        '<?= addslashes($catEnc) ?>',
                                        '<?= addslashes($descEnc) ?>',
                                        '<?= $pCompra ?>',
                                        '<?= $pVenta ?>',
                                        '<?= $stock ?>',
                                        '<?= $sMin ?>',
                                        '<?= $sMax ?>',
                                        '<?= $margen ?>',
                                        '<?= addslashes($estadoStockNombre) ?>',
                                        '<?= $estadoStockClase ?>',
                                        '<?= addslashes($activo) ?>',
                                        '<?= $activoCls ?>',
                                        '<?= $repuesto->getId() ?>'
                                    )"
                                    title="Haz clic para ver detalles"
                                >
                            <?php else: ?>
                                <div class="no-img-thumb" 
                                     onclick="openPreview(
                                        '',
                                        '<?= addslashes($nombreEnc) ?>',
                                        '<?= addslashes($codigoEnc) ?>',
                                        '<?= addslashes($catEnc) ?>',
                                        '<?= addslashes($descEnc) ?>',
                                        '<?= $pCompra ?>',
                                        '<?= $pVenta ?>',
                                        '<?= $stock ?>',
                                        '<?= $sMin ?>',
                                        '<?= $sMax ?>',
                                        '<?= $margen ?>',
                                        '<?= addslashes($estadoStockNombre) ?>',
                                        '<?= $estadoStockClase ?>',
                                        '<?= addslashes($activo) ?>',
                                        '<?= $activoCls ?>',
                                        '<?= $repuesto->getId() ?>'
                                     )"
                                     style="cursor:pointer;" title="Sin imagen — clic para ver detalles">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <code class="text-primary"><?= $codigoEnc ?></code>
                        </td>
                        <td>
                            <div>
                                <strong><?= $nombreEnc ?></strong>
                                <?php if ($repuesto->getDescripcion()): ?>
                                <br><small class="text-muted"><?= htmlspecialchars(substr($repuesto->getDescripcion(), 0, 50)) ?>...</small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?= $catEnc ?></span>
                        </td>
                        <td>
                            <span class="text-success">S/ <?= $pCompra ?></span>
                        </td>
                        <td>
                            <span class="text-primary">S/ <?= $pVenta ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2"><?= $stock ?></span>
                                <span class="badge <?= $estadoStockClase ?>">
                                    <?= $estadoStockNombre ?>
                                </span>
                            </div>
                            <small class="text-muted">
                                Min: <?= $sMin ?> | 
                                Max: <?= $sMax ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge <?= $activoCls ?>">
                                <?= $activo ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Vista rápida"
                                        onclick="openPreview(
                                            '<?= addslashes($imgUrl) ?>',
                                            '<?= addslashes($nombreEnc) ?>',
                                            '<?= addslashes($codigoEnc) ?>',
                                            '<?= addslashes($catEnc) ?>',
                                            '<?= addslashes($descEnc) ?>',
                                            '<?= $pCompra ?>',
                                            '<?= $pVenta ?>',
                                            '<?= $stock ?>',
                                            '<?= $sMin ?>',
                                            '<?= $sMax ?>',
                                            '<?= $margen ?>',
                                            '<?= addslashes($estadoStockNombre) ?>',
                                            '<?= $estadoStockClase ?>',
                                            '<?= addslashes($activo) ?>',
                                            '<?= $activoCls ?>',
                                            '<?= $repuesto->getId() ?>'
                                        )">
                                    <i class="fas fa-expand-alt"></i>
                                </button>
                                <a href="<?= BASE_URL ?>repuestos/<?= $repuesto->getId() ?>" 
                                   class="btn btn-sm btn-outline-info" title="Ver detalle completo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>repuestos/<?= $repuesto->getId() ?>/editar" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(<?= $repuesto->getId() ?>, '<?= htmlspecialchars($repuesto->getNombre()) ?>')" 
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Paginación -->
<?php if (isset($pages) && $pages > 1): ?>
<nav aria-label="Paginación de repuestos" class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
            <a class="page-link" href="<?= BASE_URL ?>repuestos?page=<?= $i ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?><?= !empty($categoria_id) ? '&categoria_id=' . $categoria_id : '' ?>">
                <?= $i ?>
            </a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<!-- Modal de Vista Previa del Repuesto -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header py-2 px-3">
                <h6 class="modal-title mb-0" id="previewModalLabel">
                    <i class="fas fa-expand-alt me-2 text-primary"></i>Vista Previa del Repuesto
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Imagen grande -->
                    <div class="col-md-5 preview-img-wrap" id="previewImgWrap">
                        <img id="previewImg" src="" alt="Imagen del repuesto" style="max-width:100%; max-height:420px; object-fit:contain;">
                    </div>
                    <!-- Especificaciones -->
                    <div class="col-md-7 preview-specs">
                        <div class="preview-name" id="previewNombre"></div>
                        <div class="preview-code"><i class="fas fa-barcode me-1"></i><span id="previewCodigo"></span></div>

                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-tags me-2 text-info"></i>Categoría</span>
                            <span class="spec-value" id="previewCategoria"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-align-left me-2 text-secondary"></i>Descripción</span>
                            <span class="spec-value text-muted" id="previewDescripcion" style="font-weight:400; font-size:0.88rem; max-width:300px; text-align:right;"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-dollar-sign me-2 text-success"></i>Precio Compra</span>
                            <span class="spec-value text-success" id="previewPCompra"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-tag me-2 text-primary"></i>Precio Venta</span>
                            <span class="spec-value text-primary" id="previewPVenta"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-chart-line me-2 text-success"></i>Margen</span>
                            <span class="spec-value text-success" id="previewMargen"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-boxes me-2"></i>Stock Actual</span>
                            <span class="spec-value">
                                <span id="previewStock"></span>
                                &nbsp;<span id="previewStockBadge" class="badge"></span>
                            </span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-sliders-h me-2 text-muted"></i>Stock Min / Max</span>
                            <span class="spec-value" id="previewStockMinMax"></span>
                        </div>
                        <div class="spec-row">
                            <span class="spec-label"><i class="fas fa-toggle-on me-2"></i>Estado</span>
                            <span id="previewEstado" class="badge"></span>
                        </div>

                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <a id="previewBtnVer" href="#" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Ver Completo
                            </a>
                            <a id="previewBtnEditar" href="#" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                        </div>
                    </div>
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
                    <?= \App\Core\Csrf::field(); ?>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';

// Mover los modales al <body> para evitar conflictos de z-index u overflow de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    document.body.appendChild(document.getElementById('previewModal'));
    document.body.appendChild(document.getElementById('deleteModal'));
});

// ── Singleton Modal instances ────────────────────────────────────────────────
let _previewModalInstance = null;
let _deleteModalInstance  = null;

function getPreviewModal() {
    const el = document.getElementById('previewModal');
    // Si ya existe una instancia de Bootstrap, reutilizarla
    if (!_previewModalInstance) {
        _previewModalInstance = new bootstrap.Modal(el, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        // Limpiar al cerrar para no tener backdrops huérfanos
        el.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        });
    }
    return _previewModalInstance;
}

function getDeleteModal() {
    const el = document.getElementById('deleteModal');
    if (!_deleteModalInstance) {
        _deleteModalInstance = new bootstrap.Modal(el, {
            backdrop: true,
            keyboard: true
        });
        el.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        });
    }
    return _deleteModalInstance;
}
// ────────────────────────────────────────────────────────────────────────────

function openPreview(imgUrl, nombre, codigo, categoria, descripcion, pCompra, pVenta,
                     stock, sMin, sMax, margen, estadoStockNombre, estadoStockClase, activo, activoCls, id) {

    // Limpiar backdrops residuales antes de abrir
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');

    const wrap = document.getElementById('previewImgWrap');

    if (imgUrl) {
        wrap.style.background = '#111';
        wrap.innerHTML = `<img src="${imgUrl}" alt="Imagen del repuesto" style="max-width:100%; max-height:420px; object-fit:contain;">`;
    } else {
        wrap.style.background = '#f8f9fa';
        wrap.innerHTML = '<div class="text-center text-muted p-5"><i class="fas fa-image fa-4x mb-2"></i><br>Sin imagen</div>';
    }

    document.getElementById('previewNombre').textContent    = nombre;
    document.getElementById('previewCodigo').textContent    = codigo;
    document.getElementById('previewCategoria').textContent = categoria;
    document.getElementById('previewDescripcion').textContent = descripcion || '—';
    document.getElementById('previewPCompra').textContent   = 'S/ ' + pCompra;
    document.getElementById('previewPVenta').textContent    = 'S/ ' + pVenta;
    document.getElementById('previewMargen').textContent    = margen + '%';
    document.getElementById('previewStock').textContent     = stock;
    document.getElementById('previewStockMinMax').textContent = 'Mín ' + sMin + '  /  Máx ' + sMax;

    const stockBadge = document.getElementById('previewStockBadge');
    stockBadge.textContent = estadoStockNombre;
    stockBadge.className   = 'badge ' + estadoStockClase;

    const estadoBadge = document.getElementById('previewEstado');
    estadoBadge.textContent = activo;
    estadoBadge.className   = 'badge ' + activoCls;

    document.getElementById('previewBtnVer').href    = BASE_URL + 'repuestos/' + id;
    document.getElementById('previewBtnEditar').href = BASE_URL + 'repuestos/' + id + '/editar';

    getPreviewModal().show();
}

function confirmDelete(repuestoId, repuestoName) {
    // Limpiar backdrops residuales
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');

    document.getElementById('repuestoName').textContent = repuestoName;
    document.getElementById('deleteForm').action = BASE_URL + 'repuestos/' + repuestoId + '/eliminar';
    getDeleteModal().show();
}
</script>

<?php 
$content = ob_get_clean();
include SRC_PATH . '/Views/layouts/app.php';
?>
