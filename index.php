<?php
declare(strict_types=1);

ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,
    'samesite' => 'Strict',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Flash messages
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Mensagens de login
$authRequired = isset($_GET['auth']) ? 'Faça login para continuar.' : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Login Moderno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <?php include './includes/favicon.php'; ?>

</head>

<body class="login-bg">
    <div class="login-card">
        <div class="brand">
            <div class="logo">🔐</div>
            <h1>Área Segura</h1>
            <p>Faça login para acessar o Painel de Informações</p>
        </div>

        <!-- Flash message -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Mensagem de autenticação -->
        <?php if ($authRequired): ?>
            <div class="alert alert-warn"><?php echo htmlspecialchars($authRequired); ?></div>
        <?php endif; ?>

        <!-- Mensagem de bloqueio -->
        <div class="alert alert-error" id="locked-message" style="display:none;">
            Login bloqueado! Tente novamente em <span id="locked-seconds">0</span> segundo(s).
        </div>

        <!-- Formulário de login -->
        <form action="php/login.php" method="post" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="input-group">
                <input type="text" name="username" id="username" autocomplete="username" required>
                <label for="username">Usuário</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" autocomplete="current-password" required>
                <label for="password">Senha</label>
            </div>
            <button class="btn-primary" type="submit">Entrar</button>
        </form>

        <footer class="login-footer">
            <small>Para testes de Login</small>
        </footer>
    </div>

    <script src="./js/index.js"></script>
</body>

</html>