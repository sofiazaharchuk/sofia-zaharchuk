<?php
declare(strict_types=1);

#[Attribute(Attribute::TARGET_FUNCTION)]
class TraceableSearch {
    public static array $trace = [];
    public static function log(string $node): void {
        self::$trace[] = $node;
    }
    public static function reset(): void {
        self::$trace = [];
    }
}

#[Attribute(Attribute::TARGET_FUNCTION)]
class Validator {
    public function __construct(public readonly string $name) {}
    public static array $fired = [];
    public static function log(string $name): void {
        self::$fired[] = $name;
    }
    public static function reset(): void {
        self::$fired = [];
    }
}

$categoryTree = [
    ['name' => 'Техніка', 'children' => [
        ['name' => 'Комп\'ютери', 'children' => [
            ['name' => 'Ноутбуки',  'children' => []],
            ['name' => 'Моноблоки', 'children' => []],
            ['name' => 'Десктопи',  'children' => []],
        ]],
        ['name' => 'Телефони', 'children' => [
            ['name' => 'Android', 'children' => []],
            ['name' => 'iPhone',  'children' => []],
        ]],
        ['name' => 'Телевізори', 'children' => []],
    ]],
    ['name' => 'Одяг', 'children' => [
        ['name' => 'Чоловічий', 'children' => [
            ['name' => 'Куртки',  'children' => []],
            ['name' => 'Джинси',  'children' => []],
        ]],
        ['name' => 'Жіночий', 'children' => [
            ['name' => 'Сукні',   'children' => []],
            ['name' => 'Блузки',  'children' => []],
        ]],
    ]],
    ['name' => 'Книги', 'children' => [
        ['name' => 'Програмування', 'children' => [
            ['name' => 'PHP',        'children' => []],
            ['name' => 'JavaScript', 'children' => []],
            ['name' => 'Python',     'children' => []],
        ]],
        ['name' => 'Художня',  'children' => []],
        ['name' => 'Наукова',  'children' => []],
    ]],
];

#[TraceableSearch]
function logNode(string $node): void {
    TraceableSearch::log($node);
}

function findCategory(array $tree, string $name, callable $callback): ?array {
    foreach ($tree as $node) {
        $callback($node['name']);
        if (mb_strtolower($node['name']) === mb_strtolower($name)) {
            return $node;
        }
        if (!empty($node['children'])) {
            $found = findCategory($node['children'], $name, $callback);
            if ($found !== null) return $found;
        }
    }
    return null;
}

function renderTree(array $tree, string $highlight = ''): string {
    $html = '<ul>';
    foreach ($tree as $node) {
        $isFound = !empty($highlight) && mb_strtolower($node['name']) === mb_strtolower($highlight);
        $liCls   = $isFound ? ' class="found"' : '';
        $html   .= "<li$liCls><div class='tree-node'>" . htmlspecialchars($node['name']) . '</div>';
        if (!empty($node['children'])) {
            $html .= renderTree($node['children'], $highlight);
        }
        $html .= '</li>';
    }
    return $html . '</ul>';
}

#[Validator(name: 'required')]
function required(mixed $value): bool {
    Validator::log('required');
    return trim((string) $value) !== '';
}

#[Validator(name: 'isValidEmail')]
function isValidEmail(mixed $value): bool {
    Validator::log('isValidEmail');
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

#[Validator(name: 'minLength')]
function minLength(mixed $value, int $min): bool {
    Validator::log("minLength($min)");
    return mb_strlen((string) $value) >= $min;
}

#[Validator(name: 'hasUppercase')]
function hasUppercase(mixed $value): bool {
    Validator::log('hasUppercase');
    return (bool) preg_match('/[A-Z]/', (string) $value);
}

#[Validator(name: 'hasDigit')]
function hasDigit(mixed $value): bool {
    Validator::log('hasDigit');
    return (bool) preg_match('/[0-9]/', (string) $value);
}

function validateForm(array $data, array $rules): array {
    $errors = [];
    foreach ($rules as $field => $fieldRules) {
        foreach ($fieldRules as $rule) {
            $value  = $data[$field] ?? '';
            $args   = $rule['args'] ?? [];
            $passed = $rule['callback']($value, ...$args);
            if (!$passed) {
                $errors[$field][] = $rule['message'];
                break;
            }
        }
    }
    return $errors;
}

$searchQuery  = '';
$searchResult = null;
$searchDone   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchQuery = trim((string) ($_POST['category'] ?? ''));
    TraceableSearch::reset();
    if ($searchQuery !== '') {
        $searchDone   = true;
        $searchResult = findCategory($categoryTree, $searchQuery, 'logNode');
    }
}

$formData   = ['name' => '', 'email' => '', 'password' => ''];
$formErrors = [];
$formOk     = false;

$rules = [
    'name'     => [
        ['callback' => 'required',  'message' => 'Імʼя є обовʼязковим'],
        ['callback' => 'minLength', 'message' => 'Мінімум 2 символи', 'args' => [2]],
    ],
    'email'    => [
        ['callback' => 'required',     'message' => 'Email є обовʼязковим'],
        ['callback' => 'isValidEmail', 'message' => 'Невірний формат email'],
    ],
    'password' => [
        ['callback' => 'required',    'message' => 'Пароль є обовʼязковим'],
        ['callback' => 'minLength',   'message' => 'Мінімум 8 символів', 'args' => [8]],
        ['callback' => 'hasUppercase','message' => 'Потрібна хоча б одна велика літера'],
        ['callback' => 'hasDigit',    'message' => 'Потрібна хоча б одна цифра'],
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    Validator::reset();
    $formData = [
        'name'     => trim((string) ($_POST['name']     ?? '')),
        'email'    => trim((string) ($_POST['email']    ?? '')),
        'password' => trim((string) ($_POST['password'] ?? '')),
    ];
    $formErrors = validateForm($formData, $rules);
    $formOk     = empty($formErrors);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>NexusUI — Smart Catalogue & Auth</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --bg:       #060811;
            --surface:  #0b0e1a;
            --card:     #0d1120;
            --border:   rgba(99,102,241,0.18);
            --glow:     rgba(99,102,241,0.08);
            --indigo:   #6366f1;
            --violet:   #8b5cf6;
            --cyan:     #06b6d4;
            --emerald:  #10b981;
            --red:      #ef4444;
            --amber:    #f59e0b;
            --text:     #f1f5f9;
            --text2:    #94a3b8;
            --mono:     'JetBrains Mono', monospace;
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
                    radial-gradient(ellipse 60% 50% at 10% 10%, rgba(99,102,241,0.12), transparent 55%),
                    radial-gradient(ellipse 50% 40% at 90% 85%, rgba(6,182,212,0.07), transparent 55%),
                    radial-gradient(ellipse 40% 35% at 50% 50%, rgba(139,92,246,0.05), transparent 60%);
        }

        body::after {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                    linear-gradient(rgba(99,102,241,0.035) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* ── NAV ── */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(6,8,17,0.8);
            backdrop-filter: blur(24px) saturate(180%);
            border-bottom: 1px solid rgba(99,102,241,0.12);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px; height: 62px;
        }

        .logo {
            display: flex; align-items: center; gap: 10px;
        }

        .logo-mark {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--indigo), var(--cyan));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; color: #fff;
        }

        .logo-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700; font-size: 1.05rem;
            letter-spacing: -0.3px;
            background: linear-gradient(90deg, var(--text), var(--text2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-right {
            display: flex; align-items: center; gap: 8px;
        }

        .nav-pill {
            padding: 5px 14px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 500;
            color: var(--text2);
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.03);
            letter-spacing: 0.2px;
        }

        .nav-pill.active {
            background: rgba(99,102,241,0.12);
            border-color: rgba(99,102,241,0.3);
            color: #a5b4fc;
        }

        /* ── HERO ── */
        .hero {
            position: relative; z-index: 1;
            padding: 90px 48px 64px;
            max-width: 900px;
        }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.72rem; font-weight: 500;
            color: #818cf8; letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .hero-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--indigo);
            box-shadow: 0 0 8px var(--indigo);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.5; transform: scale(0.8); }
        }

        .hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.4rem, 5.5vw, 4.2rem);
            font-weight: 700; line-height: 1.1;
            letter-spacing: -1.5px; margin-bottom: 16px;
        }

        .hero h1 em {
            font-style: normal;
            background: linear-gradient(135deg, #818cf8, var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1rem; color: var(--text2);
            line-height: 1.7; max-width: 560px;
        }

        /* ── LAYOUT ── */
        .main {
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto;
            padding: 0 32px 100px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px; align-items: start;
        }

        /* ── GLASS CARD ── */
        .panel {
            background: rgba(255,255,255,0.022);
            backdrop-filter: blur(32px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px; overflow: hidden;
            box-shadow:
                    0 0 0 1px rgba(99,102,241,0.06),
                    0 20px 60px rgba(0,0,0,0.5),
                    inset 0 1px 0 rgba(255,255,255,0.06);
            position: relative;
        }

        .panel::before {
            content: '';
            position: absolute; top: 0; left: 20px; right: 20px; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), rgba(6,182,212,0.3), transparent);
        }

        .panel-head {
            padding: 22px 26px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex; align-items: flex-start; gap: 14px;
        }

        .panel-icon {
            width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .pi-indigo { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
        .pi-cyan   { background: rgba(6,182,212,0.1);  border: 1px solid rgba(6,182,212,0.2);  }

        .panel-head-text h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem; font-weight: 600; color: var(--text);
            letter-spacing: -0.3px;
        }

        .panel-head-text p {
            font-size: 0.72rem; color: var(--text2); margin-top: 3px;
        }

        .panel-body { padding: 22px 26px; }

        /* ── SEARCH BAR ── */
        .search-row {
            display: flex; gap: 8px; margin-bottom: 20px;
        }

        .inp {
            flex: 1;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px; padding: 10px 14px;
            color: var(--text);
            font-family: 'Inter', sans-serif; font-size: 0.85rem;
            transition: all 0.2s;
        }

        .inp:focus {
            outline: none;
            border-color: rgba(99,102,241,0.5);
            background: rgba(99,102,241,0.04);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
        }

        .inp::placeholder { color: rgba(148,163,184,0.5); }
        .inp.err { border-color: rgba(239,68,68,0.5); background: rgba(239,68,68,0.04); }
        .inp.ok  { border-color: rgba(16,185,129,0.5); background: rgba(16,185,129,0.04); }

        .btn {
            padding: 10px 22px; border: none; border-radius: 10px;
            background: linear-gradient(135deg, var(--indigo) 0%, var(--violet) 100%);
            color: #fff; font-family: 'Inter', sans-serif;
            font-size: 0.82rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(99,102,241,0.35);
        }

        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }
        .btn-full { width: 100%; justify-content: center; }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px; padding: 12px 16px;
            font-size: 0.82rem; margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }

        .alert-green {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            color: #6ee7b7;
        }

        .alert-red {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5;
        }

        /* ── TREE ── */
        .tree { font-size: 0.82rem; }
        .tree ul { list-style: none; padding-left: 20px; }
        .tree > ul { padding-left: 0; }

        .tree li {
            padding: 3px 0;
            display: flex; flex-direction: column;
        }

        .tree-node {
            display: flex; align-items: center; gap: 7px;
            padding: 4px 8px; border-radius: 6px;
            color: var(--text2); cursor: default;
            transition: all 0.15s;
        }

        .tree-node:hover { background: rgba(255,255,255,0.04); color: var(--text); }

        .tree-node::before {
            content: '';
            width: 5px; height: 5px; border-radius: 50%;
            background: rgba(99,102,241,0.4); flex-shrink: 0;
        }

        .tree li.found > .tree-node {
            background: rgba(6,182,212,0.08);
            border: 1px solid rgba(6,182,212,0.2);
            color: var(--cyan); font-weight: 600;
        }

        .tree li.found > .tree-node::before {
            background: var(--cyan);
            box-shadow: 0 0 8px var(--cyan);
        }

        /* ── TRACE ── */
        .trace {
            margin-top: 16px;
            background: rgba(0,0,0,0.25);
            border: 1px solid rgba(99,102,241,0.1);
            border-radius: 10px; padding: 14px 16px;
        }

        .trace-title {
            font-size: 0.62rem; letter-spacing: 2px;
            color: rgba(99,102,241,0.7); margin-bottom: 10px;
            font-weight: 600; text-transform: uppercase;
        }

        .trace-chips { display: flex; flex-wrap: wrap; gap: 4px; }

        .chip {
            font-family: var(--mono); font-size: 0.65rem;
            padding: 3px 9px; border-radius: 5px;
            background: rgba(99,102,241,0.07);
            border: 1px solid rgba(99,102,241,0.15);
            color: #a5b4fc;
        }

        .chip.active {
            background: rgba(6,182,212,0.1);
            border-color: rgba(6,182,212,0.25);
            color: var(--cyan);
        }

        /* ── FORM ── */
        .field { margin-bottom: 16px; }

        .field > label {
            display: block; font-size: 0.72rem; font-weight: 500;
            color: var(--text2); margin-bottom: 6px; letter-spacing: 0.3px;
        }

        .field-err {
            font-size: 0.7rem; color: #fca5a5;
            margin-top: 5px; display: flex; align-items: center; gap: 5px;
        }

        .field-err::before { content: '◆'; font-size: 0.45rem; }

        .form-ok-banner {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 12px; padding: 16px 20px;
            color: #6ee7b7; font-size: 0.85rem;
            text-align: center; margin-bottom: 18px;
        }

        .validators-log {
            margin-top: 16px;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(139,92,246,0.1);
            border-radius: 10px; padding: 14px 16px;
        }

        .v-title {
            font-size: 0.62rem; letter-spacing: 2px;
            color: rgba(139,92,246,0.7); margin-bottom: 10px;
            font-weight: 600; text-transform: uppercase;
        }

        .v-chips { display: flex; flex-wrap: wrap; gap: 4px; }

        .v-chip {
            font-family: var(--mono); font-size: 0.65rem;
            padding: 3px 9px; border-radius: 5px;
            background: rgba(139,92,246,0.07);
            border: 1px solid rgba(139,92,246,0.18);
            color: #c4b5fd;
        }

        /* ── FOOTER ── */
        footer {
            position: relative; z-index: 1;
            border-top: 1px solid rgba(255,255,255,0.04);
            padding: 28px 48px;
            display: flex; align-items: center; justify-content: space-between;
        }

        .footer-logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700; font-size: 0.9rem; color: var(--text2);
        }

        .footer-copy { font-size: 0.7rem; color: rgba(148,163,184,0.4); }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius: 2px; }

        @media (max-width: 860px) {
            .main { grid-template-columns: 1fr; padding: 0 16px 60px; }
            nav, .hero, footer { padding-left: 20px; padding-right: 20px; }
            .hero { padding-top: 56px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-mark">N</div>
        <div class="logo-name">NexusUI</div>
    </div>
    <div class="nav-right">
        <span class="nav-pill active">Каталог</span>
        <span class="nav-pill">Реєстрація</span>
    </div>
</nav>

<div class="hero">
    <div class="hero-eyebrow">
        <div class="hero-dot"></div>
        Система управління контентом
    </div>
    <h1>Розумний каталог<br/>та <em>безпечна авторизація</em></h1>
    <p>Шукайте в ієрархічному дереві категорій та створюйте акаунт з повноцінною валідацією даних.</p>
</div>

<div class="main">

    <!-- ═══ КАТАЛОГ (Варіант 3) ═══ -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-icon pi-indigo">🗂</div>
            <div class="panel-head-text">
                <h2>Каталог категорій</h2>
                <p>Рекурсивний пошук · Ієрархічне дерево</p>
            </div>
        </div>
        <div class="panel-body">

            <form method="POST">
                <div class="search-row">
                    <input class="inp" type="text" name="category"
                           placeholder="Назва категорії..."
                           value="<?= htmlspecialchars($searchQuery) ?>"/>
                    <button class="btn" type="submit" name="search">Пошук</button>
                </div>
            </form>

            <?php if ($searchDone): ?>
                <?php if ($searchResult !== null): ?>
                    <div class="alert alert-green">
                        ✓ Знайдено: <strong><?= htmlspecialchars($searchResult['name']) ?></strong>
                        <?php if (!empty($searchResult['children'])): ?>
                            &nbsp;·&nbsp; підкатегорій: <?= count($searchResult['children']) ?>
                        <?php else: ?>
                            &nbsp;·&nbsp; кінцевий вузол
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-red">
                        Категорію «<?= htmlspecialchars($searchQuery) ?>» не знайдено
                    </div>
                <?php endif; ?>

                <?php if (!empty(TraceableSearch::$trace)): ?>
                    <div class="trace">
                        <div class="trace-title">Відвідані вузли</div>
                        <div class="trace-chips">
                            <?php foreach (TraceableSearch::$trace as $node): ?>
                                <span class="chip <?= mb_strtolower($node) === mb_strtolower($searchQuery) ? 'active' : '' ?>">
                  <?= htmlspecialchars($node) ?>
                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="tree" style="margin-top:16px">
                <?= renderTree($categoryTree, $searchQuery) ?>
            </div>

        </div>
    </div>

    <!-- ═══ РЕЄСТРАЦІЯ (Варіант 5) ═══ -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-icon pi-cyan">🔐</div>
            <div class="panel-head-text">
                <h2>Створити акаунт</h2>
                <p>Валідація · Захист пароля</p>
            </div>
        </div>
        <div class="panel-body">

            <?php if ($formOk): ?>
                <div class="form-ok-banner">✓ Акаунт успішно створено!</div>
            <?php endif; ?>

            <form method="POST">

                <div class="field">
                    <label>Повне імʼя</label>
                    <input class="inp <?= isset($formErrors['name']) ? 'err' : ($formOk || !empty($formData['name']) ? 'ok' : '') ?>"
                           type="text" name="name" placeholder="Ваше імʼя..."
                           value="<?= htmlspecialchars($formData['name']) ?>" style="width:100%"/>
                    <?php if (!empty($formErrors['name'])): ?>
                        <div class="field-err"><?= htmlspecialchars($formErrors['name'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label>Email адреса</label>
                    <input class="inp <?= isset($formErrors['email']) ? 'err' : ($formOk || !empty($formData['email']) ? 'ok' : '') ?>"
                           type="text" name="email" placeholder="your@email.com"
                           value="<?= htmlspecialchars($formData['email']) ?>" style="width:100%"/>
                    <?php if (!empty($formErrors['email'])): ?>
                        <div class="field-err"><?= htmlspecialchars($formErrors['email'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label>Пароль</label>
                    <input class="inp <?= isset($formErrors['password']) ? 'err' : ($formOk && !empty($formData['password']) ? 'ok' : '') ?>"
                           type="password" name="password"
                           placeholder="Мін. 8 символів, A-Z, 0-9..."
                           style="width:100%"/>
                    <?php if (!empty($formErrors['password'])): ?>
                        <div class="field-err"><?= htmlspecialchars($formErrors['password'][0]) ?></div>
                    <?php endif; ?>
                </div>

                <button class="btn btn-full" type="submit" name="register">
                    Зареєструватись →
                </button>

            </form>

            <?php if (!empty(Validator::$fired)): ?>
                <div class="validators-log">
                    <div class="v-title">Запущені валідатори</div>
                    <div class="v-chips">
                        <?php foreach (Validator::$fired as $v): ?>
                            <span class="v-chip"><?= htmlspecialchars($v) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<footer>
    <div class="footer-logo">NexusUI</div>
    <div class="footer-copy">© <?= date('Y') ?> NexusUI Platform</div>
</footer>

</body>
</html>