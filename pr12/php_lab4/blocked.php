<?php
// blocked.php — Сторінка блокування доступу
session_start();

// Якщо блокування вже минуло — на index
if (!isset($_COOKIE['blocked']) || (int)$_COOKIE['blocked'] <= time()) {
    // Очищаємо застарілі cookies блокування
    setcookie('blocked', '', time() - 1, '/');
    setcookie('block_reason', '', time() - 1, '/');
    header('Location: index.php');
    exit;
}

// Якщо вже авторизований — на secure.php (не має бути заблокованим)
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: secure.php');
    exit;
}

$unblockTimestamp = (int)$_COOKIE['blocked'];
$secondsLeft      = $unblockTimestamp - time();
$blockedAt        = isset($_SESSION['blocked_at'])
    ? date('H:i:s', $_SESSION['blocked_at'])
    : 'N/A';
$failedLogin = isset($_SESSION['failed_login'])
    ? htmlspecialchars($_SESSION['failed_login'])
    : 'UNKNOWN';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCESS BLOCKED — CYBERGATE</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blocked-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 12px;
            animation: blink 1.5s ease-in-out infinite;
            color: var(--danger);
        }
        .blocked-title {
            font-family: 'Orbitron', monospace;
            font-size: 20px;
            font-weight: 900;
            color: var(--danger);
            text-shadow: 0 0 30px var(--danger-glow);
            text-align: center;
            letter-spacing: 4px;
        }
        .blocked-sub {
            text-align: center;
            font-size: 11px;
            color: var(--text-dim);
            letter-spacing: 2px;
            margin-top: 6px;
            margin-bottom: 28px;
        }
        .progress-bar-wrap {
            width: 100%;
            height: 4px;
            background: rgba(255,56,96,0.15);
            margin: 16px 0 24px;
            position: relative;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--danger), #ff8099);
            box-shadow: 0 0 10px var(--danger-glow);
            transition: width 1s linear;
        }
        .timer-label {
            text-align: center;
            font-size: 10px;
            letter-spacing: 3px;
            color: var(--text-dim);
            text-transform: uppercase;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card" style="border-color: rgba(255,56,96,0.3);">
        <div class="card-corner tl" style="border-color: var(--danger);"></div>
        <div class="card-corner br" style="border-color: var(--danger);"></div>

        <div class="header">
            <div class="system-label">▸ SECURITY LOCKOUT — EVENT LOG #<?= rand(1000,9999) ?></div>
        </div>

        <div class="blocked-icon">⛔</div>
        <div class="blocked-title">ACCESS BLOCKED</div>
        <div class="blocked-sub">TEMPORARY SECURITY LOCKOUT INITIATED</div>

        <div style="display:flex; justify-content:center; margin-bottom:20px;">
            <div class="status-badge blocked">● ACCOUNT LOCKED</div>
        </div>

        <!-- Причина блокування -->
        <div class="alert alert-danger">
            ⚠ Ви заблоковані на 5 хвилин через неправильні спроби входу.<br>
            Перевищено ліміт: 3 невдалих спроби поспіль.
        </div>

        <!-- Дані про блокування -->
        <div class="info-grid" style="margin-bottom: 20px;">
            <div class="info-row">
                <span class="info-key">Заблоковано о</span>
                <span class="info-val"><?= $blockedAt ?></span>
            </div>
            <div class="info-row">
                <span class="info-key">Причина</span>
                <span class="info-val" style="color:var(--danger);">3/3 FAILED ATTEMPTS</span>
            </div>
            <div class="info-row">
                <span class="info-key">Логін</span>
                <span class="info-val"><?= strtoupper($failedLogin) ?></span>
            </div>
            <div class="info-row">
                <span class="info-key">Cookie TTL</span>
                <span class="info-val" style="color:var(--warning);">5 ХВИЛИН</span>
            </div>
        </div>

        <!-- Таймер зворотного відліку -->
        <div class="timer-label">РОЗБЛОКУВАННЯ ЧЕРЕЗ</div>
        <div class="timer-display" id="countdown">--:--</div>

        <!-- Прогрес-бар -->
        <div class="progress-bar-wrap">
            <div class="progress-bar-fill" id="progressBar" style="width:100%;"></div>
        </div>

        <div class="timestamp" id="unblockTime">
            РОЗБЛОКУВАННЯ: <?= date('H:i:s', $unblockTimestamp) ?>
        </div>

        <div id="unlockBtn" style="display:none; margin-top:20px;">
            <a href="index.php" class="btn btn-primary">▶ СПРОБУВАТИ ЗНОВУ</a>
        </div>
    </div>
</div>

<script>
const unblockAt   = <?= $unblockTimestamp ?> * 1000; // мілісекунди
const totalBlock  = 5 * 60 * 1000;                  // 5 хвилин у мс
const blockedAt   = unblockAt - totalBlock;

function pad(n) { return String(n).padStart(2, '0'); }

function update() {
    const now       = Date.now();
    const remaining = Math.max(0, unblockAt - now);
    const elapsed   = now - blockedAt;
    const progress  = Math.max(0, 100 - (elapsed / totalBlock) * 100);

    const mins = Math.floor(remaining / 60000);
    const secs = Math.floor((remaining % 60000) / 1000);
    document.getElementById('countdown').textContent = pad(mins) + ':' + pad(secs);
    document.getElementById('progressBar').style.width = progress.toFixed(1) + '%';

    if (remaining <= 0) {
        // Час вийшов — показуємо кнопку
        document.getElementById('countdown').textContent = '00:00';
        document.getElementById('countdown').style.color = 'var(--success)';
        document.getElementById('unlockBtn').style.display = 'block';
        clearInterval(timer);
    }
}

update();
const timer = setInterval(update, 1000);
</script>
</body>
</html>
