<?php
$prices = [
    'MacBook Pro 14"'    => 89999,
    'iPhone 15 Pro'      => 54999,
    'iPad Air'           => 32999,
    'AirPods Pro'        => 12999,
    'Apple Watch Ultra'  => 34999,
    'Magic Keyboard'     => 8999,
];

$reg   = ['login' => '', 'password' => '', 'confirm' => ''];
$order = ['name' => '', 'email' => '', 'product' => '', 'qty' => ''];
$regErrors = $orderErrors = [];
$regOk = $orderOk = false;
$orderSummary = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $login    = trim(htmlspecialchars((string)($_POST['login']    ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirm  = (string)($_POST['confirm']  ?? '');

    $reg = ['login' => $login, 'password' => '', 'confirm' => ''];

    if ($login === '') {
        $regErrors['login'] = 'Логін є обовʼязковим';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $regErrors['login'] = 'Логін може містити лише літери, цифри та _';
    } elseif (mb_strlen($login) < 3) {
        $regErrors['login'] = 'Мінімум 3 символи';
    }

    if ($password === '') {
        $regErrors['password'] = 'Пароль є обовʼязковим';
    } elseif (mb_strlen($password) < 8) {
        $regErrors['password'] = 'Мінімум 8 символів';
    }

    if ($confirm === '') {
        $regErrors['confirm'] = 'Підтвердіть пароль';
    } elseif ($password !== $confirm) {
        $regErrors['confirm'] = 'Паролі не збігаються';
    }

    $regOk = empty($regErrors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    $name    = trim(htmlspecialchars((string)($_POST['o_name']    ?? '')));
    $email   = trim((string)($_POST['o_email']   ?? ''));
    $product = (string)($_POST['product']  ?? '');
    $qty     = filter_var($_POST['qty'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 100]]);

    $order = ['name' => $name, 'email' => $email, 'product' => $product, 'qty' => $_POST['qty'] ?? ''];

    if ($name === '') $orderErrors['name'] = 'Вкажіть імʼя покупця';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $orderErrors['email'] = 'Невірний формат email';
    }

    if (!array_key_exists($product, $prices)) {
        $orderErrors['product'] = 'Оберіть товар зі списку';
    }

    if ($qty === false) {
        $orderErrors['qty'] = 'Кількість: від 1 до 100';
    }

    $orderOk = empty($orderErrors);

    if ($orderOk) {
        $orderSummary = [
            'name'    => $name,
            'email'   => htmlspecialchars($email),
            'product' => htmlspecialchars($product),
            'qty'     => (int) $qty,
            'price'   => $prices[$product],
            'total'   => $prices[$product] * (int) $qty,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>NEXUS//OS — Terminal Interface</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --black:   #020408;
            --dark:    #040c10;
            --panel:   rgba(0,255,100,0.03);
            --green:   #00ff64;
            --green2:  #00cc50;
            --green3:  rgba(0,255,100,0.15);
            --cyan:    #00ffe5;
            --red:     #ff3355;
            --amber:   #ffb800;
            --text:    #c8ffd4;
            --muted:   #3a6b47;
            --border:  rgba(0,255,100,0.2);
            --glow:    0 0 20px rgba(0,255,100,0.25);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--black);
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            min-height: 100vh;
            overflow-x: hidden;
            cursor: crosshair;
        }

        /* SCANLINES */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 999; pointer-events: none;
            background: repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(0,0,0,0.08) 2px,
                    rgba(0,0,0,0.08) 4px
            );
        }

        /* GRID */
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                    linear-gradient(rgba(0,255,100,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0,255,100,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* GLOW ORB */
        .orb {
            position: fixed; border-radius: 50%; filter: blur(100px);
            pointer-events: none; z-index: 0; opacity: 0.12;
        }
        .orb1 { width: 600px; height: 600px; background: var(--green);  top: -200px; left: -200px; }
        .orb2 { width: 400px; height: 400px; background: var(--cyan);   bottom: -100px; right: -100px; }

        /* NAV */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(2,4,8,0.92);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px; height: 56px;
            backdrop-filter: blur(10px);
        }

        .sys-id {
            font-family: 'Orbitron', monospace;
            font-weight: 900; font-size: 0.85rem;
            letter-spacing: 4px;
            color: var(--green);
            text-shadow: var(--glow);
            animation: flicker 8s ease-in-out infinite;
        }

        @keyframes flicker {
            0%,95%,100% { opacity: 1; }
            96% { opacity: 0.4; }
            97% { opacity: 1; }
            98% { opacity: 0.6; }
            99% { opacity: 1; }
        }

        .sys-status {
            display: flex; align-items: center; gap: 20px;
            font-size: 0.68rem; color: var(--muted); letter-spacing: 2px;
        }

        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 8px var(--green);
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.4; transform: scale(0.7); }
        }

        /* HERO */
        .hero {
            position: relative; z-index: 1;
            padding: 64px 40px 48px;
            border-bottom: 1px solid var(--border);
        }

        .hero-cmd {
            font-size: 0.72rem; color: var(--muted);
            letter-spacing: 2px; margin-bottom: 18px;
        }

        .hero-cmd::before { content: '> '; color: var(--green); }

        .hero h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 900; line-height: 1.05;
            letter-spacing: -1px;
            color: var(--green);
            text-shadow: 0 0 40px rgba(0,255,100,0.4), 0 0 80px rgba(0,255,100,0.15);
        }

        .hero h1 span { color: var(--cyan); text-shadow: 0 0 30px rgba(0,255,229,0.4); }

        .hero-sub {
            margin-top: 14px; font-size: 0.82rem;
            color: var(--muted); letter-spacing: 1px; line-height: 1.8;
        }

        .hero-sub::before { content: '// '; color: var(--green2); }

        .blink {
            display: inline-block; width: 10px; height: 1.1em;
            background: var(--green); vertical-align: middle;
            animation: blink 1s step-end infinite;
            box-shadow: 0 0 8px var(--green);
        }

        @keyframes blink { 0%,100%{ opacity:1; } 50%{ opacity:0; } }

        /* MAIN */
        .terminal {
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto;
            padding: 40px 40px 100px;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 24px; align-items: start;
        }

        /* WINDOW */
        .win {
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--panel);
            backdrop-filter: blur(8px);
            box-shadow:
                    0 0 0 1px rgba(0,255,100,0.06),
                    0 20px 60px rgba(0,0,0,0.8),
                    inset 0 0 60px rgba(0,255,100,0.02);
            position: relative; overflow: hidden;
        }

        .win::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--green), transparent);
            opacity: 0.6;
        }

        /* TITLE BAR */
        .win-bar {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            background: rgba(0,255,100,0.03);
        }

        .win-dots { display: flex; gap: 6px; }

        .dot {
            width: 10px; height: 10px; border-radius: 50%;
            cursor: pointer; transition: filter 0.2s;
        }

        .dot-r { background: #ff3355; box-shadow: 0 0 6px #ff3355; }
        .dot-y { background: #ffb800; box-shadow: 0 0 6px #ffb800; }
        .dot-g { background: #00ff64; box-shadow: 0 0 6px #00ff64; }
        .dot:hover { filter: brightness(1.4); }

        .win-title {
            font-family: 'Orbitron', monospace;
            font-size: 0.6rem; letter-spacing: 3px;
            color: var(--muted); flex: 1; text-align: center;
        }

        .win-body { padding: 24px 22px; }

        /* SECTION HEADER */
        .sec-header {
            margin-bottom: 22px;
        }

        .sec-label {
            font-size: 0.6rem; letter-spacing: 4px;
            color: var(--muted); text-transform: uppercase;
            margin-bottom: 6px;
        }

        .sec-label::before { content: '# '; color: var(--green); }

        .sec-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.3rem; font-weight: 700;
            color: var(--green);
            letter-spacing: 1px;
            text-shadow: 0 0 20px rgba(0,255,100,0.3);
        }

        /* SUCCESS */
        .alert-ok {
            background: rgba(0,255,100,0.06);
            border: 1px solid rgba(0,255,100,0.3);
            border-left: 3px solid var(--green);
            border-radius: 2px;
            padding: 12px 16px; margin-bottom: 20px;
            font-size: 0.8rem; color: var(--green);
            letter-spacing: 0.5px;
        }

        .alert-ok::before { content: '[OK] '; font-weight: 700; }

        /* FIELD */
        .field { margin-bottom: 16px; }

        .field label {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.68rem; letter-spacing: 2px;
            color: var(--muted); margin-bottom: 7px;
            text-transform: uppercase;
        }

        .field label::before { content: '$'; color: var(--green); }

        .inp {
            width: 100%;
            background: rgba(0,0,0,0.5);
            border: 1px solid var(--border);
            border-radius: 2px;
            padding: 11px 14px;
            color: var(--text);
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.88rem;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            caret-color: var(--green);
        }

        .inp:focus {
            outline: none;
            border-color: var(--green);
            background: rgba(0,255,100,0.04);
            box-shadow: 0 0 0 2px rgba(0,255,100,0.1), var(--glow);
            color: var(--green);
        }

        .inp::placeholder { color: var(--muted); }

        .inp-err {
            border-color: rgba(255,51,85,0.6);
            background: rgba(255,51,85,0.04);
            box-shadow: 0 0 0 2px rgba(255,51,85,0.08);
        }

        .inp-ok {
            border-color: rgba(0,255,100,0.4);
            background: rgba(0,255,100,0.03);
        }

        .err-msg {
            margin-top: 5px; font-size: 0.7rem;
            color: var(--red); letter-spacing: 0.5px;
        }

        .err-msg::before { content: '[ERR] '; }

        select.inp {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2300ff64'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        select.inp option {
            background: #040c10;
            color: var(--text);
        }

        /* BUTTON */
        .btn {
            width: 100%; padding: 13px;
            background: transparent;
            border: 1px solid var(--green);
            border-radius: 2px;
            font-family: 'Orbitron', monospace;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 4px; text-transform: uppercase;
            color: var(--green);
            cursor: crosshair;
            transition: all 0.2s;
            text-shadow: 0 0 10px rgba(0,255,100,0.4);
            position: relative; overflow: hidden;
            margin-top: 4px;
        }

        .btn::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(0,255,100,0.08), transparent);
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }

        .btn:hover {
            background: rgba(0,255,100,0.08);
            box-shadow: var(--glow), inset 0 0 20px rgba(0,255,100,0.05);
            letter-spacing: 6px;
        }

        .btn:hover::before { transform: translateX(100%); }

        .btn-cyan {
            border-color: var(--cyan); color: var(--cyan);
            text-shadow: 0 0 10px rgba(0,255,229,0.4);
        }

        .btn-cyan:hover {
            background: rgba(0,255,229,0.06);
            box-shadow: 0 0 20px rgba(0,255,229,0.2);
        }

        /* SUMMARY */
        .summary {
            margin-bottom: 22px;
            border: 1px solid var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .summary-head {
            padding: 10px 16px;
            background: rgba(0,255,100,0.05);
            border-bottom: 1px solid var(--border);
            font-size: 0.6rem; letter-spacing: 4px;
            color: var(--green); text-transform: uppercase;
        }

        .summary-row {
            display: flex; justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid rgba(0,255,100,0.06);
            font-size: 0.78rem;
        }

        .summary-row:last-child { border-bottom: none; }

        .s-label { color: var(--muted); }
        .s-val   { color: var(--text); }

        .summary-row.total { background: rgba(0,255,229,0.04); }
        .summary-row.total .s-label { color: var(--cyan); letter-spacing: 1px; }
        .summary-row.total .s-val {
            font-family: 'Orbitron', monospace;
            font-size: 0.9rem; font-weight: 700;
            color: var(--cyan);
            text-shadow: 0 0 12px rgba(0,255,229,0.5);
        }

        /* DIVIDER */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 20px 0;
        }

        /* FOOTER */
        footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--border);
            padding: 20px 40px;
            display: flex; justify-content: space-between; align-items: center;
        }

        .foot-id {
            font-family: 'Orbitron', monospace;
            font-size: 0.65rem; letter-spacing: 3px;
            color: var(--muted);
        }

        .foot-sys {
            font-size: 0.62rem; color: var(--muted);
            letter-spacing: 1px;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--black); }
        ::-webkit-scrollbar-thumb { background: var(--green2); border-radius: 0; }

        @media (max-width: 820px) {
            .terminal { grid-template-columns: 1fr; padding: 20px 16px 60px; }
            nav, .hero, footer { padding-left: 18px; padding-right: 18px; }
        }
    </style>
</head>
<body>

<div class="orb orb1"></div>
<div class="orb orb2"></div>

<nav>
    <div class="sys-id">NEXUS//OS</div>
    <div class="sys-status">
        <div class="status-dot"></div>
        SYSTEM ONLINE
    </div>
</nav>

<div class="hero">
    <div class="hero-cmd">initialize user_portal --secure --validate</div>
    <h1>USER<br/><span>PORTAL</span></h1>
    <div class="hero-sub">реєстрація акаунту та оформлення замовлення <span class="blink"></span></div>
</div>

<div class="terminal">

    <!-- ═══ ВАРІАНТ 2: РЕЄСТРАЦІЯ ═══ -->
    <div class="win">
        <div class="win-bar">
            <div class="win-dots">
                <div class="dot dot-r"></div>
                <div class="dot dot-y"></div>
                <div class="dot dot-g"></div>
            </div>
            <div class="win-title">AUTH.MODULE // register.php</div>
        </div>
        <div class="win-body">

            <div class="sec-header">
                <div class="sec-label">module::auth</div>
                <div class="sec-title">Реєстрація</div>
            </div>

            <?php if ($regOk): ?>
                <div class="alert-ok">Акаунт «<?= htmlspecialchars($reg['login']) ?>» успішно створено</div>
            <?php endif; ?>

            <form method="POST" action="#reg">

                <div class="field">
                    <label for="login">login</label>
                    <input class="inp <?php if(isset($regErrors['login'])): ?>inp-err<?php elseif($regOk): ?>inp-ok<?php endif; ?>"
                           type="text" name="login" id="login"
                           placeholder="a-z, 0-9, _ only..."
                           value="<?= htmlspecialchars($reg['login']) ?>"/>
                    <?php if (!empty($regErrors['login'])): ?>
                        <div class="err-msg"><?= $regErrors['login'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="password">password</label>
                    <input class="inp <?php if(isset($regErrors['password'])): ?>inp-err<?php elseif($regOk): ?>inp-ok<?php endif; ?>"
                           type="password" name="password" id="password"
                           placeholder="min 8 chars..."/>
                    <?php if (!empty($regErrors['password'])): ?>
                        <div class="err-msg"><?= $regErrors['password'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="confirm">confirm_password</label>
                    <input class="inp <?php if(isset($regErrors['confirm'])): ?>inp-err<?php elseif($regOk): ?>inp-ok<?php endif; ?>"
                           type="password" name="confirm" id="confirm"
                           placeholder="repeat password..."/>
                    <?php if (!empty($regErrors['confirm'])): ?>
                        <div class="err-msg"><?= $regErrors['confirm'] ?></div>
                    <?php endif; ?>
                </div>

                <button class="btn" type="submit" name="register">
                    &gt;&gt; execute register
                </button>

            </form>
        </div>
    </div>

    <!-- ═══ ВАРІАНТ 4: ЗАМОВЛЕННЯ ═══ -->
    <div class="win" id="order">
        <div class="win-bar">
            <div class="win-dots">
                <div class="dot dot-r"></div>
                <div class="dot dot-y"></div>
                <div class="dot dot-g"></div>
            </div>
            <div class="win-title">SHOP.MODULE // order.php</div>
        </div>
        <div class="win-body">

            <div class="sec-header">
                <div class="sec-label">module::shop</div>
                <div class="sec-title">Замовлення</div>
            </div>

            <?php if ($orderOk && $orderSummary): ?>

                <div class="alert-ok">Замовлення прийнято до обробки</div>

                <div class="summary">
                    <div class="summary-head">&gt; order_summary.json</div>
                    <div class="summary-row">
                        <span class="s-label">buyer</span>
                        <span class="s-val"><?= $orderSummary['name'] ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="s-label">email</span>
                        <span class="s-val"><?= $orderSummary['email'] ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="s-label">product</span>
                        <span class="s-val"><?= $orderSummary['product'] ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="s-label">unit_price</span>
                        <span class="s-val"><?= number_format($orderSummary['price'], 0, '.', '_') ?> UAH</span>
                    </div>
                    <div class="summary-row">
                        <span class="s-label">qty</span>
                        <span class="s-val"><?= $orderSummary['qty'] ?>x</span>
                    </div>
                    <div class="summary-row total">
                        <span class="s-label">total_amount</span>
                        <span class="s-val"><?= number_format($orderSummary['total'], 0, '.', '_') ?> UAH</span>
                    </div>
                </div>

                <div class="divider"></div>

            <?php endif; ?>

            <form method="POST" action="#order">

                <div class="field">
                    <label for="o_name">buyer_name</label>
                    <input class="inp <?php if(isset($orderErrors['name'])): ?>inp-err<?php elseif($orderOk): ?>inp-ok<?php endif; ?>"
                           type="text" name="o_name" id="o_name"
                           placeholder="Ваше імʼя..."
                           value="<?= htmlspecialchars($order['name']) ?>"/>
                    <?php if (!empty($orderErrors['name'])): ?>
                        <div class="err-msg"><?= $orderErrors['name'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="o_email">contact_email</label>
                    <input class="inp <?php if(isset($orderErrors['email'])): ?>inp-err<?php elseif($orderOk): ?>inp-ok<?php endif; ?>"
                           type="text" name="o_email" id="o_email"
                           placeholder="your@email.com"
                           value="<?= htmlspecialchars($order['email']) ?>"/>
                    <?php if (!empty($orderErrors['email'])): ?>
                        <div class="err-msg"><?= $orderErrors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="product">select_product</label>
                    <select class="inp <?php if(isset($orderErrors['product'])): ?>inp-err<?php elseif($orderOk): ?>inp-ok<?php endif; ?>"
                            name="product" id="product">
                        <option value="">-- NULL --</option>
                        <?php foreach ($prices as $pname => $pprice): ?>
                            <option value="<?= htmlspecialchars($pname) ?>"
                                <?= $order['product'] === $pname ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pname) ?> [<?= number_format($pprice, 0, '.', '_') ?> UAH]
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($orderErrors['product'])): ?>
                        <div class="err-msg"><?= $orderErrors['product'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="qty">quantity [1..100]</label>
                    <input class="inp <?php if(isset($orderErrors['qty'])): ?>inp-err<?php elseif($orderOk): ?>inp-ok<?php endif; ?>"
                           type="number" name="qty" id="qty"
                           min="1" max="100" placeholder="01"
                           value="<?= htmlspecialchars($order['qty']) ?>"/>
                    <?php if (!empty($orderErrors['qty'])): ?>
                        <div class="err-msg"><?= $orderErrors['qty'] ?></div>
                    <?php endif; ?>
                </div>

                <button class="btn btn-cyan" type="submit" name="buy">
                    &gt;&gt; execute order
                </button>

            </form>
        </div>
    </div>

</div>

<footer>
    <div class="foot-id">NEXUS//OS v2.4.1</div>
    <div class="foot-sys">SYS_TIME: <?= date('Y-m-d H:i:s') ?> UTC</div>
</footer>

</body>
</html>