<?php
// login.php — Форма входу з підрахунком спроб
session_start();

// Константи (у реальному проекті — у конфігу або БД)
define('VALID_LOGIN', 'admin');
define('VALID_PASSWORD', '1234');
define('MAX_ATTEMPTS', 3);
define('BLOCK_DURATION', 5 * 60); // 5 хвилин у секундах

// Якщо вже авторизований → на secure.php
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: secure.php');
    exit;
}

// Якщо заблокований → на blocked.php
if (isset($_COOKIE['blocked']) && (int)$_COOKIE['blocked'] > time()) {
    header('Location: blocked.php');
    exit;
}

// Ініціалізуємо лічильник спроб у сесії
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

$error = '';
$success = '';

// Обробка форми входу (POST-запит)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Перевіряємо облікові дані
    if ($login === VALID_LOGIN && $password === VALID_PASSWORD) {
        // Успішний вхід
        $_SESSION['authenticated'] = true;
        $_SESSION['username']      = $login;
        $_SESSION['login_time']    = time();
        $_SESSION['login_attempts'] = 0; // Скидаємо лічильник спроб

        // Зберігаємо дані останнього входу у cookie (якщо вже є — оновлюємо)
        setcookie('last_login_user', $login, time() + (30 * 24 * 3600), '/');
        setcookie('last_login_time', time(), time() + (30 * 24 * 3600), '/');

        header('Location: secure.php');
        exit;
    } else {
        // Невдала спроба
        $_SESSION['login_attempts']++;
        $attemptsLeft = MAX_ATTEMPTS - $_SESSION['login_attempts'];

        if ($_SESSION['login_attempts'] >= MAX_ATTEMPTS) {
            // Досягнуто ліміту — блокуємо через cookie
            $unblockTime = time() + BLOCK_DURATION;
            setcookie('blocked', $unblockTime, $unblockTime, '/');
            setcookie('block_reason', 'too_many_attempts', $unblockTime, '/');

            // Фіксуємо час і кількість спроб у сесії для аудиту
            $_SESSION['blocked_at']      = time();
            $_SESSION['failed_login']    = $login; // зберігаємо логін що використовувався

            header('Location: blocked.php');
            exit;
        } else {
            $error = "AUTHENTICATION FAILED — ACCESS DENIED [{$_SESSION['login_attempts']}/{MAX_ATTEMPTS}] · {$attemptsLeft} " . ($attemptsLeft === 1 ? 'ATTEMPT' : 'ATTEMPTS') . " REMAINING";
        }
    }
}

$attempts    = $_SESSION['login_attempts'];
$lastCookieUser = $_COOKIE['last_login_user'] ?? null;
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE LOGIN — AUTH SYSTEM</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-hint {
            margin-top: 20px;
            padding: 12px 16px;
            background: rgba(0,212,255,0.04);
            border: 1px solid rgba(0,212,255,0.1);
            font-size: 11px;
            color: var(--text-dim);
            letter-spacing: 1px;
        }
        .login-hint span { color: var(--accent); }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-corner tl"></div>
        <div class="card-corner br"></div>

        <div class="header">
            <div class="system-label">▸ SECURE ACCESS TERMINAL v2.4</div>
            <div class="logo">CYB<span>ER</span>GATE</div>
            <div class="subtitle">AUTHENTICATION REQUIRED</div>
        </div>

        <div class="divider"></div>

        <?php if ($error): ?>
        <div class="alert alert-danger">
            ⚠ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($lastCookieUser && !$error): ?>
        <div class="alert alert-success">
            ✓ RETURNING USER DETECTED: <strong><?= htmlspecialchars($lastCookieUser) ?></strong>
        </div>
        <?php endif; ?>

        <!-- Трекер спроб -->
        <?php if ($attempts > 0): ?>
        <div class="attempt-tracker">
            <span class="label">Attempts</span>
            <div class="attempt-dots">
                <?php for ($i = 0; $i < MAX_ATTEMPTS; $i++): ?>
                    <div class="attempt-dot <?= $i < $attempts ? 'used' : '' ?>"></div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <div class="form-group">
                <label class="form-label" for="login">▸ USER IDENTIFIER</label>
                <input
                    class="form-control"
                    type="text"
                    id="login"
                    name="login"
                    placeholder="ENTER LOGIN..."
                    value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">▸ ACCESS CODE</label>
                <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="ENTER PASSWORD..."
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 8px;">
                ⬡ AUTHENTICATE
            </button>
        </form>

        <!-- Підказка для навчальної роботи -->
        <div class="login-hint">
            ◈ DEMO CREDENTIALS — login: <span>admin</span> · password: <span>1234</span>
        </div>

        <div class="timestamp" id="clock"></div>
    </div>
</div>

<script>
// Живий годинник
function updateClock() {
    const now = new Date();
    const ts = now.toISOString().replace('T', ' ').substring(0, 19);
    document.getElementById('clock').textContent = '⌚ SYSTEM TIME: ' + ts + ' UTC';
}
updateClock();
setInterval(updateClock, 1000);
</script>
</body>
</html>
