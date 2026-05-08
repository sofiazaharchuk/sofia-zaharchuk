<?php
// secure.php — Захищена сторінка (тільки для авторизованих)
session_start();

// Захист: якщо немає авторизації — назад до index
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: index.php');
    exit;
}

$username  = htmlspecialchars($_SESSION['username'] ?? 'USER');
$loginTime = $_SESSION['login_time'] ?? time();
$sessionId = session_id();

// Дані з cookie
$lastUser = isset($_COOKIE['last_login_user']) ? htmlspecialchars($_COOKIE['last_login_user']) : 'N/A';
$lastTime = isset($_COOKIE['last_login_time'])
    ? date('Y-m-d H:i:s', (int)$_COOKIE['last_login_time'])
    : 'N/A';

// Тривалість поточної сесії
$duration = time() - $loginTime;
$durationStr = gmdate('H:i:s', $duration);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE TERMINAL — <?= strtoupper($username) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .session-id {
            font-size: 10px;
            color: var(--text-dim);
            word-break: break-all;
            letter-spacing: 1px;
        }
        .welcome-text {
            font-family: 'Orbitron', monospace;
            font-size: 18px;
            font-weight: 700;
            color: var(--success);
            text-shadow: 0 0 20px rgba(0,230,118,0.4);
            text-align: center;
            margin-bottom: 6px;
        }
        .welcome-sub {
            text-align: center;
            font-size: 11px;
            color: var(--text-dim);
            letter-spacing: 2px;
            margin-bottom: 28px;
        }
        .data-section {
            margin-bottom: 20px;
        }
        .data-title {
            font-size: 10px;
            letter-spacing: 3px;
            color: var(--text-dim);
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(0,212,255,0.1);
        }
        .data-title span {
            color: var(--accent);
            margin-right: 6px;
        }
    </style>
</head>
<body>
<div class="container" style="max-width: 520px;">
    <div class="card">
        <div class="card-corner tl"></div>
        <div class="card-corner br"></div>

        <div class="header">
            <div class="system-label">▸ SECURE TERMINAL — ACCESS GRANTED</div>
            <div class="logo">CYB<span>ER</span>GATE</div>
        </div>

        <div class="divider"></div>

        <div style="display:flex; justify-content:center; margin-bottom:24px;">
            <div class="status-badge online">● AUTHORIZED SESSION ACTIVE</div>
        </div>

        <div class="welcome-text">WELCOME, <?= strtoupper($username) ?></div>
        <div class="welcome-sub">IDENTITY VERIFIED · FULL ACCESS GRANTED</div>

        <!-- Дані сесії -->
        <div class="data-section">
            <div class="data-title"><span>■</span> SESSION DATA (PHP $_SESSION)</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-key">Username</span>
                    <span class="info-val"><?= strtoupper($username) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Login Time</span>
                    <span class="info-val"><?= date('H:i:s', $loginTime) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Duration</span>
                    <span class="info-val" id="duration"><?= $durationStr ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Session ID</span>
                    <span class="info-val session-id"><?= substr($sessionId, 0, 20) ?>...</span>
                </div>
            </div>
        </div>

        <!-- Дані cookie -->
        <div class="data-section">
            <div class="data-title"><span>■</span> COOKIE DATA ($_COOKIE)</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-key">Saved User</span>
                    <span class="info-val"><?= strtoupper($lastUser) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Last Login</span>
                    <span class="info-val"><?= $lastTime ?></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Cookie TTL</span>
                    <span class="info-val">30 DAYS</span>
                </div>
            </div>
        </div>

        <a href="logout.php" class="btn btn-danger" style="margin-top: 8px;">
            ⏻ TERMINATE SESSION
        </a>

        <div class="timestamp" id="clock"></div>
    </div>
</div>

<script>
// Живий годинник + лічильник тривалості сесії
let sessionSeconds = <?= $duration ?>;

function pad(n) { return String(n).padStart(2, '0'); }

function tick() {
    sessionSeconds++;
    const h = Math.floor(sessionSeconds / 3600);
    const m = Math.floor((sessionSeconds % 3600) / 60);
    const s = sessionSeconds % 60;
    document.getElementById('duration').textContent = pad(h) + ':' + pad(m) + ':' + pad(s);

    const now = new Date();
    const ts = now.toISOString().replace('T', ' ').substring(0, 19);
    document.getElementById('clock').textContent = '⌚ SYSTEM TIME: ' + ts + ' UTC';
}
tick();
setInterval(tick, 1000);
</script>
</body>
</html>
