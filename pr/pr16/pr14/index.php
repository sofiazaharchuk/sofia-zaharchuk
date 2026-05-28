<?php
$ip      = $_SERVER['REMOTE_ADDR'] ?? 'невідомо';
$apiUrl  = 'https://api.exchangerate-api.com/v4/latest/USD';
$data    = null;
$error   = null;

$json = @file_get_contents($apiUrl);
if ($json === false) {
    $error = 'Не вдалося отримати дані з API. Перевірте підключення до інтернету.';
} else {
    $data = json_decode($json, true);
    if (!isset($data['rates'])) {
        $error = 'Помилка обробки відповіді від API.';
        $data  = null;
    }
}

$currencies = [
    'UAH' => ['Гривня',        '🇺🇦', '#ffd700'],
    'EUR' => ['Євро',           '🇪🇺', '#003399'],
    'GBP' => ['Фунт стерлінгів','🇬🇧', '#cf142b'],
    'PLN' => ['Злотий',         '🇵🇱', '#dc143c'],
    'CHF' => ['Франк',          '🇨🇭', '#ff0000'],
    'JPY' => ['Єна',            '🇯🇵', '#bc002d'],
];

$updated = isset($data['date']) ? $data['date'] : '—';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>CurrencyFlow — Курси валют</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --bg:     #06080f;
            --s1:     #0b0e1a;
            --border: rgba(99,102,241,0.15);
            --shine:  rgba(255,255,255,0.05);
            --ind:    #6366f1;
            --vio:    #8b5cf6;
            --cyan:   #06b6d4;
            --green:  #10b981;
            --red:    #ef4444;
            --text:   #f1f5f9;
            --muted:  #64748b;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background:
                    radial-gradient(ellipse 55% 45% at 10% 15%, rgba(99,102,241,0.12), transparent 55%),
                    radial-gradient(ellipse 45% 40% at 90% 85%, rgba(6,182,212,0.07),  transparent 55%);
        }

        body::after {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                    linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(6,8,15,0.85);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 60px;
        }

        .logo {
            display: flex; align-items: center; gap: 10px;
        }

        .logo-mark {
            width: 30px; height: 30px; border-radius: 8px;
            background: linear-gradient(135deg, var(--ind), var(--cyan));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.8rem; color: #fff;
        }

        .logo-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700; font-size: 1rem; letter-spacing: -0.3px;
            background: linear-gradient(90deg, var(--text), var(--muted));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-info {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.68rem; color: var(--muted);
            display: flex; align-items: center; gap: 6px;
        }

        .nav-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 8px var(--green);
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

        .hero {
            position: relative; z-index: 1;
            padding: 70px 48px 50px;
        }

        .hero-chip {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.25);
            color: #818cf8; font-size: 0.7rem; font-weight: 500;
            letter-spacing: 2px; padding: 5px 14px; border-radius: 20px;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 800; line-height: 1.1; letter-spacing: -1.5px;
            margin-bottom: 12px;
        }

        .hero h1 em {
            font-style: normal;
            background: linear-gradient(135deg, #818cf8, var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub { font-size: 0.9rem; color: var(--muted); }

        .main {
            position: relative; z-index: 1;
            max-width: 1100px; margin: 0 auto;
            padding: 0 32px 80px;
        }

        .meta-row {
            display: flex; align-items: center; gap: 16px;
            margin-bottom: 32px; flex-wrap: wrap;
        }

        .meta-pill {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 8px; padding: 8px 14px;
            font-size: 0.75rem; color: var(--muted);
        }

        .meta-pill strong { color: var(--text); }

        .base-card {
            background: rgba(255,255,255,0.025);
            backdrop-filter: blur(28px);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 24px 28px;
            margin-bottom: 28px;
            position: relative; overflow: hidden;
        }

        .base-card::before {
            content: '';
            position: absolute; top: 0; left: 20px; right: 20px; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
        }

        .base-label {
            font-size: 0.65rem; letter-spacing: 3px;
            color: var(--muted); margin-bottom: 12px; text-transform: uppercase;
        }

        .base-rate {
            display: flex; align-items: baseline; gap: 10px;
        }

        .base-num {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 3.5rem; font-weight: 800;
            background: linear-gradient(135deg, var(--text), #94a3b8);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .base-curr {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.2rem; color: var(--muted);
        }

        .uah-big {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem; font-weight: 700;
            color: #fbbf24;
            margin-top: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .card {
            background: rgba(255,255,255,0.022);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 14px; padding: 20px 22px;
            position: relative; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        }

        .card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
        }

        .card-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px;
        }

        .card-flag { font-size: 1.6rem; }

        .card-name {
            font-size: 0.72rem; font-weight: 500;
            color: var(--muted); letter-spacing: 0.5px;
        }

        .card-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem; font-weight: 500;
            padding: 3px 10px; border-radius: 5px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--muted);
        }

        .card-rate {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.2rem; font-weight: 800;
            line-height: 1; margin-bottom: 4px;
        }

        .card-sub {
            font-size: 0.72rem; color: var(--muted);
        }

        .error-box {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: 12px; padding: 20px 24px;
            color: #fca5a5; font-size: 0.88rem;
            display: flex; align-items: center; gap: 12px;
        }

        .ip-section {
            margin-top: 32px;
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border);
            border-radius: 12px; padding: 18px 22px;
            display: flex; align-items: center; gap: 14px;
        }

        .ip-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }

        .ip-label { font-size: 0.68rem; color: var(--muted); margin-bottom: 3px; letter-spacing: 1px; }

        .ip-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95rem; font-weight: 500; color: var(--cyan);
        }

        footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--border);
            padding: 22px 48px;
            display: flex; justify-content: space-between; align-items: center;
        }

        .footer-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700; color: var(--muted); font-size: 0.88rem;
        }

        .footer-copy { font-size: 0.68rem; color: rgba(100,116,139,0.5); }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 2px; }

        @media (max-width: 640px) {
            nav, .hero, footer { padding-left: 18px; padding-right: 18px; }
            .main { padding: 0 16px 60px; }
            .hero { padding-top: 48px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-mark">₴</div>
        <div class="logo-name">CurrencyFlow</div>
    </div>
    <div class="nav-info">
        <div class="nav-dot"></div>
        LIVE · API EXCHANGERATE-API.COM
    </div>
</nav>

<div class="hero">
    <div class="hero-chip">Варіант 4 · file_get_contents() · json_decode()</div>
    <h1>Курси <em>валют</em><br/>до долара США</h1>
    <p class="hero-sub">Дані отримані з публічного REST API в реальному часі · Оновлено: <?= htmlspecialchars($updated) ?></p>
</div>

<div class="main">

    <?php if ($error): ?>
        <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
    <?php else: ?>

        <div class="meta-row">
            <div class="meta-pill">Джерело: <strong>exchangerate-api.com</strong></div>
            <div class="meta-pill">Базова валюта: <strong>USD</strong></div>
            <div class="meta-pill">Оновлено: <strong><?= htmlspecialchars($updated) ?></strong></div>
        </div>

        <div class="base-card">
            <div class="base-label">Базова валюта</div>
            <div class="base-rate">
                <div class="base-num">1</div>
                <div class="base-curr">USD</div>
            </div>
            <?php if (isset($data['rates']['UAH'])): ?>
                <div class="uah-big">= <?= number_format($data['rates']['UAH'], 2) ?> ₴</div>
            <?php endif; ?>
        </div>

        <div class="grid">
            <?php foreach ($currencies as $code => [$name, $flag, $color]): ?>
                <?php if (!isset($data['rates'][$code])) continue; ?>
                <?php $rate = $data['rates'][$code]; ?>
                <div class="card" style="--accent: <?= $color ?>">
                    <div style="position:absolute;top:0;left:0;right:0;height:2px;background:<?= $color ?>;opacity:0.7"></div>
                    <div class="card-head">
                        <div>
                            <div class="card-flag"><?= $flag ?></div>
                            <div class="card-name"><?= htmlspecialchars($name) ?></div>
                        </div>
                        <div class="card-code"><?= htmlspecialchars($code) ?></div>
                    </div>
                    <div class="card-rate" style="color:<?= $color ?>;text-shadow:0 0 20px <?= $color ?>44">
                        <?= number_format($rate, $code === 'JPY' ? 2 : 4) ?>
                    </div>
                    <div class="card-sub">1 USD = <?= number_format($rate, 2) ?> <?= htmlspecialchars($code) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <div class="ip-section">
        <div class="ip-icon">🌐</div>
        <div>
            <div class="ip-label">IP АДРЕСА КОРИСТУВАЧА</div>
            <div class="ip-value"><?= htmlspecialchars($ip) ?></div>
        </div>
    </div>

</div>

<footer>
    <div class="footer-brand">CurrencyFlow</div>
    <div class="footer-copy">© <?= date('Y') ?> · PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?> · file_get_contents() · json_decode()</div>
</footer>

</body>
</html>