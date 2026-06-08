<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Iniciar Sesión' ?> — <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #fbf9f6;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
            --color-primary: #d97706;
            --color-primary-hover: #b45309;
            --color-primary-glow: rgba(217, 119, 6, 0.15);
            --border-subtle: #e5dcd3;
            --text-main: #2e2620;
            --text-muted: #6b5c53;
            --color-danger: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'system-ui', -apple-system, sans-serif;
            position: relative;
        }
        .login-wrapper {
            width: 100%; max-width: 400px;
            padding: 1.5rem;
            position: relative; z-index: 1;
            animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .brand-logo {
            width: 80px; height: 80px;
            object-fit: contain;
            border-radius: 8px;
            background: #ffffff;
            padding: 10px;
            border: 1px solid var(--border-subtle);
            margin-bottom: 0.75rem;
        }
        .brand-name {
            font-size: 1.1rem; font-weight: 700;
            color: var(--text-main); margin: 0;
        }
        .brand-sub {
            font-size: 0.82rem;
            color: var(--text-muted); margin: 0;
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 1.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .login-card h5 {
            font-weight: 600; color: var(--text-main);
            font-size: 1.2rem; margin-bottom: 0.25rem;
        }
        .login-card .subtitle {
            color: var(--text-muted);
            font-size: 0.85rem; margin-bottom: 1.5rem;
        }
        .form-label {
            color: var(--text-muted);
            font-size: 0.78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-control {
            background: var(--bg-input) !important;
            border: 1px solid #d1d5db !important;
            color: var(--text-main) !important;
            border-radius: 4px; padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 2px var(--color-primary-glow) !important;
            outline: none;
        }
        .form-control::placeholder { color: #9ca3af !important; }
        .input-group-text {
            background: #f3f4f6 !important;
            border: 1px solid #d1d5db !important;
            border-right: none !important;
            color: var(--text-muted) !important;
            border-radius: 4px 0 0 4px;
        }
        .input-group .form-control {
            border-left: none !important;
            border-radius: 0 4px 4px 0 !important;
        }
        .btn-login {
            background: var(--color-primary) !important;
            border: 1px solid var(--color-primary) !important;
            color: #fff !important;
            font-weight: 600; font-size: 0.95rem;
            padding: 10px; border-radius: 4px;
            width: 100%; cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-login:hover {
            background: var(--color-primary-hover) !important;
            border-color: var(--color-primary-hover) !important;
        }
        .alert-login {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid var(--color-danger);
            border-radius: 4px; color: #b91c1c;
            font-size: 0.875rem; padding: 10px 14px;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 8px;
        }
        .login-footer {
            text-align: center; margin-top: 1.25rem;
            color: var(--text-muted); font-size: 0.78rem;
        }
        .version-badge {
            display: inline-block;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border-subtle);
            border-radius: 20px; padding: 3px 10px;
            font-size: 0.72rem; color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* --- Animaciones --- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spinner-min {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <!-- Global Loader (Top Progress Bar + Minimalist center spinner) -->
    <div id="global-progress-bar" style="position: fixed; top: 0; left: 0; height: 3px; background-color: var(--color-primary); z-index: 99999; width: 0; transition: width 0.3s ease, opacity 0.3s ease; opacity: 1;"></div>
    <div id="global-spinner-overlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(251, 249, 246, 0.7); display: flex; align-items: center; justify-content: center; z-index: 99998; opacity: 1; transition: opacity 0.25s ease;">
        <div class="spinner-min" style="width: 24px; height: 24px; border: 2px solid rgba(0,0,0,0.06); border-top-color: var(--color-primary); border-radius: 50%; animation: spin 0.6s linear infinite;"></div>
    </div>
    <div class="login-wrapper">
        <div class="brand-header">
            <img src="<?= BASE_URL ?>logo.jpg" alt="Logo" class="brand-logo">
            <h1 class="brand-name"><?= APP_NAME ?></h1>
            <p class="brand-sub">Sistema de Gestión de Inventario</p>
        </div>

        <div class="login-card">
            <h5>Bienvenido de vuelta</h5>
            <p class="subtitle">Ingresa tus credenciales para acceder al sistema</p>

            <?php if (isset($error) && $error): ?>
            <div class="alert-login">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>login">
                <?= \App\Core\Csrf::field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="usuario@empresa.com" required autocomplete="email">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Ingresar al Sistema
                </button>
            </form>
        </div>

        <div class="login-footer">
            <span>Rectificadora de Repuestos &copy; <?= date('Y') ?></span><br>
            <span class="version-badge">v<?= APP_VERSION ?></span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const progressBar = document.getElementById('global-progress-bar');
            const spinnerOverlay = document.getElementById('global-spinner-overlay');
            if (progressBar && spinnerOverlay) {
                progressBar.style.width = '100%';
                setTimeout(() => {
                    spinnerOverlay.style.opacity = '0';
                    progressBar.style.opacity = '0';
                    setTimeout(() => {
                        spinnerOverlay.style.display = 'none';
                        progressBar.style.display = 'none';
                    }, 250);
                }, 200);
            }

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = `<span class="spinner-min me-2" style="width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.2); border-top-color: #ffffff; border-radius: 50%; animation: spin 0.6s linear infinite; display: inline-block;"></span>Ingresando...`;
                    }
                });
            });
        });

        window.addEventListener('beforeunload', () => {
            const progressBar = document.getElementById('global-progress-bar');
            const spinnerOverlay = document.getElementById('global-spinner-overlay');
            if (progressBar && spinnerOverlay) {
                spinnerOverlay.style.display = 'flex';
                progressBar.style.display = 'block';
                spinnerOverlay.style.opacity = '1';
                progressBar.style.opacity = '1';
                progressBar.style.width = '0%';
                setTimeout(() => { progressBar.style.width = '70%'; }, 10);
            }
        });
    </script>
</body>
</html>
