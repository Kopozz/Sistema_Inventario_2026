<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Repuestos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #fbf9f6;
            --bg-sidebar: #f4eae1;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
            --text-main: #2e2620;
            --text-muted: #6b5c53;
            --color-primary: #d97706;
            --color-primary-hover: #b45309;
            --color-accent: #0891b2;
            --color-success: #16a34a;
            --color-danger: #dc2626;
            --border-subtle: #e5dcd3;
            --font-title: 'system-ui', -apple-system, sans-serif;
            --font-body: 'system-ui', -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
            font-family: var(--font-body);
            font-size: 0.925rem;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: var(--font-title);
            color: var(--text-main) !important;
            font-weight: 600;
        }

        /* Sidebar styling */
        .sidebar { 
            min-height: 100vh; 
            background-color: var(--bg-sidebar) !important;
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar .nav-link { 
            color: var(--text-muted) !important;
            font-family: var(--font-title);
            border-radius: 4px;
            margin: 4px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-left: 3px solid transparent;
            padding: 8px 0 !important;
        }

        .sidebar .nav-link i {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        .sidebar .nav-link:hover { 
            color: var(--text-main) !important; 
            background-color: rgba(0, 0, 0, 0.04);
        }

        .sidebar .nav-link:hover i {
            color: var(--color-primary);
        }

        .sidebar .nav-link.active { 
            color: var(--color-primary) !important; 
            background: rgba(217, 119, 6, 0.1) !important;
            border-left: 3px solid var(--color-primary) !important;
            font-weight: 600;
        }

        .sidebar .nav-link.active i {
            color: var(--color-primary) !important;
        }

        /* Header del Sidebar */
        .sidebar .border-bottom {
            border-bottom: 1px solid var(--border-subtle) !important;
        }

        .logo-img {
            border: 1px solid var(--border-subtle);
            background: #ffffff !important;
        }

        /* Footer del Sidebar */
        .sidebar-footer {
            border-top: 1px solid var(--border-subtle) !important;
            background-color: rgba(0, 0, 0, 0.03);
            flex-shrink: 0;
        }

        .sidebar-footer a {
            color: var(--text-main) !important;
        }

        .sidebar-footer .dropdown-toggle::after {
            display: none;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
        }

        /* Main content */
        .main-content { 
            margin-left: 0; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) { 
            .main-content { margin-left: 200px; } 
        }

        /* Navbar superior */
        .navbar {
            background-color: var(--bg-sidebar) !important;
            border-bottom: 1px solid var(--border-subtle);
            padding: 10px 20px !important;
        }

        .navbar-brand {
            font-size: 1.1rem;
        }

        /* Cards de contenido */
        .card {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
            border-radius: 6px !important;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.02) !important;
            border-bottom: 1px solid var(--border-subtle) !important;
            padding: 12px 16px !important;
        }

        .card-body {
            padding: 16px !important;
            color: var(--text-main) !important;
        }

        /* Inputs de formularios */
        .form-control, .form-select {
            background-color: var(--bg-input) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-main) !important;
            font-family: var(--font-body);
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.2) !important;
            outline: 0;
        }

        .form-control::placeholder {
            color: var(--text-muted) !important;
            opacity: 0.6;
        }

        .form-label {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.825rem;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        /* Botones estándar */
        .btn {
            font-family: var(--font-title);
            font-weight: 500;
            padding: 8px 16px;
            font-size: 0.875rem;
            border-radius: 4px;
        }

        .btn-primary {
            background-color: var(--color-primary) !important;
            border: 1px solid var(--color-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-hover) !important;
            border-color: var(--color-primary-hover) !important;
        }

        .btn-success {
            background-color: var(--color-success) !important;
            border: 1px solid var(--color-success) !important;
            color: #ffffff !important;
        }

        .btn-success:hover {
            background-color: #15803d !important;
            border-color: #15803d !important;
        }

        .btn-danger {
            background-color: var(--color-danger) !important;
            border: 1px solid var(--color-danger) !important;
            color: #ffffff !important;
        }

        .btn-danger:hover {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
        }

        .btn-outline-secondary {
            background-color: transparent !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-muted) !important;
        }

        .btn-outline-secondary:hover {
            background-color: rgba(0, 0, 0, 0.05) !important;
            color: var(--text-main) !important;
        }

        /* Tablas */
        .table {
            color: var(--text-main) !important;
        }

        .table > :not(caption) > * > * {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border-subtle) !important;
            padding: 10px 12px !important;
        }

        .table-light {
            background-color: rgba(0, 0, 0, 0.04) !important;
        }

        .table-light th {
            color: var(--text-muted) !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            border-bottom: 2px solid var(--border-subtle) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02) !important;
        }

        /* Alertas */
        .alert {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-main) !important;
            border-radius: 4px;
        }

        .alert-success {
            border-left: 3px solid var(--color-success) !important;
            background-color: #f0fdf4 !important;
            color: #166534 !important;
        }

        .alert-danger {
            border-left: 3px solid var(--color-danger) !important;
            background-color: #fef2f2 !important;
            color: #991b1b !important;
        }

        /* Badges */
        .badge {
            padding: 5px 8px !important;
            font-weight: 500 !important;
            font-size: 0.75rem !important;
            border-radius: 3px !important;
        }

        .bg-success {
            background-color: #dcfce7 !important;
            color: #15803d !important;
            border: 1px solid #bbf7d0 !important;
        }

        .bg-danger {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
            border: 1px solid #fecaca !important;
        }

        .bg-info {
            background-color: #ecfeff !important;
            color: #0891b2 !important;
            border: 1px solid #cffafe !important;
        }

        .bg-warning {
            background-color: #fef3c7 !important;
            color: #b45309 !important;
            border: 1px solid #fde68a !important;
        }

        /* Dashboard metrics cards */
        .row.g-3.mb-4 > div:nth-child(1) > .card {
            border-top: 3px solid var(--color-primary) !important;
        }
        .row.g-3.mb-4 > div:nth-child(2) > .card {
            border-top: 3px solid var(--color-success) !important;
        }
        .row.g-3.mb-4 > div:nth-child(3) > .card {
            border-top: 3px solid var(--color-danger) !important;
        }
        .row.g-3.mb-4 > div:nth-child(4) > .card {
            border-top: 3px solid var(--color-accent) !important;
        }

        .row.g-3.mb-4 .card-body {
            color: var(--text-main) !important;
        }
        .row.g-3.mb-4 .card-body a {
            color: var(--text-muted) !important;
        }
        .row.g-3.mb-4 .card-body a:hover {
            color: var(--text-main) !important;
        }
        .row.g-3.mb-4 .rounded-circle {
            background: rgba(0, 0, 0, 0.02) !important;
            border: 1px solid var(--border-subtle) !important;
        }
        .row.g-3.mb-4 div:nth-child(1) .rounded-circle i { color: var(--color-primary) !important; }
        .row.g-3.mb-4 div:nth-child(2) .rounded-circle i { color: var(--color-success) !important; }
        .row.g-3.mb-4 div:nth-child(3) .rounded-circle i { color: var(--color-danger) !important; }
        .row.g-3.mb-4 div:nth-child(4) .rounded-circle i { color: var(--color-accent) !important; }

        /* Paginación */
        .page-link {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-muted) !important;
        }

        .page-link:hover {
            background-color: rgba(0, 0, 0, 0.02) !important;
            color: var(--text-main) !important;
        }

        .page-item.active .page-link {
            background-color: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: #ffffff !important;
        }

        /* Dropdown */
        .dropdown-menu {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-subtle) !important;
        }

        .dropdown-item {
            color: var(--text-main) !important;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.04) !important;
            color: var(--text-main) !important;
        }

        #switch-currency-pen.active, #switch-currency-usd.active {
            background-color: var(--color-primary) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Sidebar -->
    <nav class="sidebar position-fixed d-none d-md-block" style="width: 200px;">
        <!-- Header del Sidebar -->
        <div class="p-3 border-bottom text-center">
            <div class="mb-2">
                <img src="<?= BASE_URL ?>logo.jpg" alt="Logo" class="logo-img" style="width: 130px; height: 130px; object-fit: contain; border-radius: 8px; border: 1px solid var(--border-subtle); padding: 5px; background: #ffffff;">
            </div>
            <div>
                <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.85rem;"><?= APP_NAME ?></h6>
            </div>
        </div>
        
        <!-- Menu del Sidebar -->
        <?php
        // Detectar sección activa para resaltar el ítem del menú
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        function sidebarActive(string $segment, string $currentPath): string {
            $path = rtrim($currentPath, '/');
            if ($segment === 'dashboard') {
                return (str_contains($path, '/dashboard') || $path === '' || $path === '/') ? 'active' : '';
            }
            return str_contains($path, '/' . $segment) ? 'active' : '';
        }
        ?>
        <div class="sidebar-menu">
            <ul class="nav nav-pills flex-column px-2 py-2">
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('dashboard', $currentPath) ?>" href="<?= BASE_URL ?>dashboard">
                        <i class="fas fa-tachometer-alt d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('usuarios', $currentPath) ?>" href="<?= BASE_URL ?>usuarios">
                        <i class="fas fa-users d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Usuarios</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('repuestos', $currentPath) ?>" href="<?= BASE_URL ?>repuestos">
                        <i class="fas fa-cogs d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Repuestos</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('inventario', $currentPath) ?>" href="<?= BASE_URL ?>inventario">
                        <i class="fas fa-boxes d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Inventario</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('proveedores', $currentPath) ?>" href="<?= BASE_URL ?>proveedores">
                        <i class="fas fa-truck d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Proveedores</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('ventas', $currentPath) ?>" href="<?= BASE_URL ?>ventas">
                        <i class="fas fa-shopping-cart d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Ventas</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-center py-2 rounded <?= sidebarActive('reportes', $currentPath) ?>" href="<?= BASE_URL ?>reportes">
                        <i class="fas fa-chart-bar d-block mb-1" style="font-size: 1.1em;"></i>
                        <span class="small">Reportes</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Footer del Sidebar -->
        <div class="sidebar-footer border-top p-2">
            <div class="dropdown text-center">
                <a class="nav-link dropdown-toggle text-dark py-2" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1" style="font-size: 1.2em;"></i>
                    <span class="small"><?= $_SESSION['user_name'] ?? 'Usuario' ?></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>usuarios/<?= $_SESSION['user_id'] ?? '' ?>"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>usuarios/<?= $_SESSION['user_id'] ?? '' ?>/cambiar-password"><i class="fas fa-key me-2"></i>Cambiar Contraseña</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-flex align-items-center w-100">
                    <img src="<?= BASE_URL ?>logo.jpg" alt="Logo" class="me-2 d-md-none" style="width: 35px; height: 35px; object-fit: contain; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    <span class="navbar-brand mb-0 h1"><?= $title ?? 'Sistema de Repuestos' ?></span>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="btn-group border p-1 rounded-3" style="background-color: var(--bg-sidebar); display: flex; gap: 4px;">
                            <button type="button" class="btn btn-sm px-3 py-1 border-0 rounded-2 text-dark fw-bold active" id="switch-currency-pen" onclick="setCurrency('PEN')" style="font-size: 0.75rem; transition: all 0.2s ease;">S/ PEN</button>
                            <button type="button" class="btn btn-sm px-3 py-1 border-0 rounded-2 text-muted fw-bold" id="switch-currency-usd" onclick="setCurrency('USD')" style="font-size: 0.75rem; transition: all 0.2s ease;">$ USD</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <?php endif; ?>

        <!-- Content -->
        <div class="container-fluid p-4">
            <?php if (isset($success) && $success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php 
            $filtered_content = $content ?? '';
            // Wrap Soles/Dollar numbers inside a targetable span class for real-time currency conversion
            $filtered_content = preg_replace_callback(
                '/(?:S\/\.?\s*|\$\s*)(\d{1,3}(?:,\d{3})*(?:\.\d{2}))/',
                function($matches) {
                    $formatted_number = $matches[1];
                    $numeric_value = str_replace(',', '', $formatted_number);
                    return '<span class="price-amount" data-soles="' . $numeric_value . '">S/ ' . $formatted_number . '</span>';
                },
                $filtered_content
            );
            echo $filtered_content;
            ?>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title"><?= APP_NAME ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>dashboard">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>usuarios">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>repuestos">
                        <i class="fas fa-cogs me-2"></i>Repuestos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>inventario">
                        <i class="fas fa-boxes me-2"></i>Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>proveedores">
                        <i class="fas fa-truck me-2"></i>Proveedores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>ventas">
                        <i class="fas fa-shopping-cart me-2"></i>Ventas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>reportes">
                        <i class="fas fa-chart-bar me-2"></i>Reportes
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const exchangeRate = 3.75; // 1 USD = 3.75 PEN
        
        function setCurrency(currency) {
            localStorage.setItem('preferred_currency', currency);
            updatePricesOnScreen(currency);
            
            const btnPen = document.getElementById('switch-currency-pen');
            const btnUsd = document.getElementById('switch-currency-usd');
            if (btnPen && btnUsd) {
                if (currency === 'USD') {
                    btnPen.classList.remove('active', 'text-white');
                    btnPen.classList.add('text-muted');
                    btnUsd.classList.remove('text-muted');
                    btnUsd.classList.add('active', 'text-white');
                } else {
                    btnUsd.classList.remove('active', 'text-white');
                    btnUsd.classList.add('text-muted');
                    btnPen.classList.remove('text-muted');
                    btnPen.classList.add('active', 'text-white');
                }
            }
        }
        
        function updatePricesOnScreen(currency) {
            const elements = document.querySelectorAll('.price-amount');
            elements.forEach(el => {
                const solesValue = parseFloat(el.getAttribute('data-soles'));
                if (isNaN(solesValue)) return;
                
                if (currency === 'USD') {
                    const usdValue = solesValue / exchangeRate;
                    el.innerHTML = '$ ' + usdValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    el.innerHTML = 'S/ ' + solesValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            });
        }

        // Initialize currency settings on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            const preferred = localStorage.getItem('preferred_currency') || 'PEN';
            setCurrency(preferred);
            
            // Set up MutationObserver to automatically format any dynamically updated or newly added price elements
            let observerActive = true;
            const observer = new MutationObserver(() => {
                if (!observerActive) return;
                observerActive = false; // Prevent recursion
                updatePricesOnScreen(localStorage.getItem('preferred_currency') || 'PEN');
                setTimeout(() => { observerActive = true; }, 10);
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</body>
</html>
