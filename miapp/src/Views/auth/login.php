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
            --bg-main: #0d1117;
            --bg-card: #161b22;
            --bg-input: #0d1117;
            --color-primary: #f08100;
            --color-primary-glow: rgba(240,129,0,0.25);
            --border-subtle: rgba(255,255,255,0.08);
            --text-main: #e2e8f0;
            --text-muted: #8b949e;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(ellipse at 20% 50%, rgba(240,129,0,0.07) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(102,192,244,0.04) 0%, transparent 50%);
            animation: bgPulse 8s ease-in-out infinite alternate;
            pointer-events: none;
        }
        @keyframes bgPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        .login-wrapper {
            width: 100%; max-width: 420px;
            padding: 1.5rem;
            position: relative; z-index: 1;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeDown 0.6s ease both;
        }
        .brand-logo {
            width: 84px; height: 84px;
            object-fit: contain;
            border-radius: 18px;
            background: rgba(255,255,255,0.05);
            padding: 10px;
            border: 1px solid var(--border-subtle);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5), 0 0 0 1px rgba(240,129,0,0.2);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .brand-logo:hover { transform: scale(1.04); }
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem; font-weight: 700;
            color: #ffffff; margin: 0;
        }
        .brand-sub {
            font-size: 0.82rem;
            color: var(--text-muted); margin: 0;
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 24px 64px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.03);
            animation: fadeUp 0.6s ease 0.1s both;
        }
        .login-card h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600; color: #ffffff;
            font-size: 1.25rem; margin-bottom: 0.25rem;
        }
        .login-card .subtitle {
            color: var(--text-muted);
            font-size: 0.85rem; margin-bottom: 1.75rem;
        }
        .form-label {
            color: var(--text-muted);
            font-size: 0.78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-control {
            background: var(--bg-input) !important;
            border: 1px solid var(--border-subtle) !important;
            color: var(--text-main) !important;
            border-radius: 8px; padding: 11px 16px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px var(--color-primary-glow) !important;
            outline: none;
        }
        .form-control::placeholder { color: #3a4556 !important; }
        .input-group-text {
            background: var(--bg-input) !important;
            border: 1px solid var(--border-subtle) !important;
            border-right: none !important;
            color: var(--text-muted) !important;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control {
            border-left: none !important;
            border-radius: 0 8px 8px 0 !important;
        }
        .btn-login {
            background: linear-gradient(135deg, #f08100 0%, #c96800 100%);
            border: none; color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 600; font-size: 0.95rem;
            padding: 12px; border-radius: 8px;
            width: 100%; cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(240,129,0,0.25);
            letter-spacing: 0.3px; margin-top: 0.5rem;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(240,129,0,0.4);
        }
        .btn-login:active { transform: translateY(0); }
        .alert-login {
            background: rgba(231,76,60,0.1);
            border: 1px solid rgba(231,76,60,0.3);
            border-left: 3px solid #e74c3c;
            border-radius: 8px; color: #fc8181;
            font-size: 0.875rem; padding: 10px 14px;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 8px;
        }
        .login-footer {
            text-align: center; margin-top: 1.5rem;
            color: var(--text-muted); font-size: 0.78rem;
            animation: fadeUp 0.6s ease 0.3s both;
        }
        .version-badge {
            display: inline-block;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border-subtle);
            border-radius: 20px; padding: 3px 10px;
            font-size: 0.72rem; color: var(--text-muted);
            margin-top: 0.5rem;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
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
</body>
</html>
