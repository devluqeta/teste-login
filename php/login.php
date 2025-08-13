<?php
// php/login.php
declare(strict_types=1);
require __DIR__ . '/conexao.php';

session_start();

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login-teste/index.php');
    exit;
}

// CSRF
$csrf = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf)) {
    flash('error', 'CSRF token inválido.');
    header('Location: /login-teste/index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    flash('warn', 'Preencha usuário e senha.');
    header('Location: /login-teste/index.php');
    exit;
}

// Busca usuário
$stmt = $pdo->prepare('SELECT id, username, password_hash, failed_attempts, locked_until
                       FROM users
                       WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

$now = new DateTimeImmutable('now');

if ($user) {
    // Verifica se bloqueio expirou
    if (!empty($user['locked_until'])) {
        $lockedUntil = new DateTimeImmutable($user['locked_until']);
        if ($lockedUntil > $now) {
            // ainda bloqueado
            header('Location: /login-teste/index.php?locked_until=' . $lockedUntil->getTimestamp());
            exit;
        } else {
            // bloqueio expirou -> resetar tentativas e locked_until
            $pdo->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?')
                ->execute([$user['id']]);
            $user['failed_attempts'] = 0;
            $user['locked_until'] = null;
        }
    }

    // Verifica senha
    if (password_verify($password, $user['password_hash'])) {
        // sucesso: zera tentativas e bloqueio
        $pdo->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?')
            ->execute([$user['id']]);

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];
        unset($_SESSION['csrf_token']);

        header('Location: /login-teste/pages/painel.php');
        exit;
    }

    // senha incorreta: incrementa tentativas
    $attempts = (int)$user['failed_attempts'] + 1;
    $lockedUntilSql = null;

    if ($attempts >= 3) {
        // bloqueia por 60 segundos
        $lockedUntil = new DateTimeImmutable('+60 seconds');
        $lockedUntilSql = $lockedUntil->format('Y-m-d H:i:s');
        $attempts = 3;
    }

    $pdo->prepare('UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?')
        ->execute([$attempts, $lockedUntilSql, $user['id']]);

    if ($lockedUntilSql) {
        header('Location: /login-teste/index.php?locked_until=' . $lockedUntil->getTimestamp());
        exit;
    } else {
        flash('error', 'Usuário ou senha inválidos. Tentativas restantes: ' . (3 - $attempts));
        header('Location: /login-teste/index.php');
        exit;
    }
} else {
    flash('error', 'Usuário ou senha inválidos.');
    header('Location: /login-teste/index.php');
    exit;
}
