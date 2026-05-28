<?php
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>PHP · Основи</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --bg:        #0c0c14;
            --surface:   #13131f;
            --card:      #0f0f1a;
            --border:    rgba(139,92,246,0.18);
            --shine:     rgba(255,255,255,0.08);
            --violet:    #8b5cf6;
            --violet2:   #a78bfa;
            --cyan:      #22d3ee;
            --green:     #34d399;
            --orange:    #fb923c;
            --red:       #f87171;
            --text:      #ede9fe;
            --muted:     rgba(237,233,254,0.4);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                    radial-gradient(ellipse 60% 50% at 20% 10%, rgba(139,92,246,0.12), transparent 60%),
                    radial-gradient(ellipse 50% 40% at 80% 80%, rgba(34,211,238,0.08), transparent 60%);
        }

        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                    linear-gradient(rgba(139,92,246,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(139,92,246,0.04) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(12,12,20,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 36px; height: 56px;
        }

        .nav-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800; font-size: 1rem;
            letter-spacing: 3px;
            background: linear-gradient(90deg, var(--violet2), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-version {
            font-size: 0.65rem; color: var(--muted);
            border: 1px solid var(--border);
            padding: 4px 12px; border-radius: 4px;
            letter-spacing: 2px;
        }

        .hero {
            position: relative; z-index: 1;
            text-align: center; padding: 70px 20px 50px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(139,92,246,0.1);
            border: 1px solid rgba(139,92,246,0.3);
            color: var(--violet2); font-size: 0.65rem;
            letter-spacing: 4px; padding: 5px 18px;
            border-radius: 3px; margin-bottom: 22px;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.4rem, 6vw, 5rem);
            font-weight: 800; line-height: 1.05;
            letter-spacing: -1px;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--violet), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            margin-top: 14px; font-size: 0.78rem;
            color: var(--muted); letter-spacing: 0.5px;
        }

        .container {
            position: relative; z-index: 1;
            max-width: 1080px; margin: 0 auto;
            padding: 0 24px;
            display: flex; flex-direction: column; gap: 32px;
        }

        .variant {
            background: rgba(255,255,255,0.025);
            backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid var(--border);
            border-top-color: rgba(255,255,255,0.1);
            border-left-color: rgba(255,255,255,0.07);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.07);
            position: relative;
        }

        .variant::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(139,92,246,0.5), transparent);
        }

        .variant-head {
            padding: 20px 26px 16px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 14px;
        }

        .variant-num {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem; font-weight: 800;
            color: rgba(139,92,246,0.2); line-height: 1;
            flex-shrink: 0;
        }

        .variant-head h2 {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem; font-weight: 700; color: var(--text);
        }

        .variant-head p {
            font-size: 0.68rem; color: var(--muted); margin-top: 3px;
        }

        .variant-body {
            padding: 24px 26px;
            display: flex; flex-direction: column; gap: 18px;
        }

        .block {
            background: rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .block-label {
            padding: 8px 14px;
            font-size: 0.62rem; letter-spacing: 2px;
            color: var(--violet2);
            background: rgba(139,92,246,0.07);
            border-bottom: 1px solid rgba(139,92,246,0.1);
            font-weight: 600;
        }

        .block-content {
            padding: 14px 16px;
            font-size: 0.82rem; line-height: 1.9;
            color: var(--text);
        }

        .block-content ul, .block-content ol {
            padding-left: 20px;
        }

        .block-content li { margin-bottom: 4px; }

        .block-content table {
            width: 100%; border-collapse: collapse;
        }

        .block-content th {
            text-align: left; padding: 8px 12px;
            font-size: 0.65rem; letter-spacing: 2px;
            color: var(--muted);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .block-content td {
            padding: 8px 12px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            font-size: 0.8rem;
        }

        .block-content tr:last-child td { border-bottom: none; }
        .block-content tr:hover td { background: rgba(139,92,246,0.04); }

        .val   { color: var(--cyan); }
        .key   { color: var(--violet2); }
        .good  { color: var(--green); }
        .warn  { color: var(--orange); }
        .big   { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; }

        .pill {
            display: inline-block;
            padding: 2px 10px; border-radius: 4px;
            font-size: 0.72rem; font-weight: 600;
        }

        .pill-green  { background: rgba(52,211,153,0.12); color: var(--green);  border: 1px solid rgba(52,211,153,0.25); }
        .pill-red    { background: rgba(248,113,113,0.12); color: var(--red);    border: 1px solid rgba(248,113,113,0.25); }
        .pill-orange { background: rgba(251,146,60,0.12);  color: var(--orange); border: 1px solid rgba(251,146,60,0.25); }
        .pill-violet { background: rgba(139,92,246,0.12);  color: var(--violet2);border: 1px solid rgba(139,92,246,0.25); }
        .pill-cyan   { background: rgba(34,211,238,0.12);  color: var(--cyan);   border: 1px solid rgba(34,211,238,0.25); }

        footer {
            position: relative; z-index: 1;
            text-align: center; margin-top: 60px;
            padding: 30px; border-top: 1px solid var(--border);
            font-size: 0.65rem; color: var(--muted); letter-spacing: 2px;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(139,92,246,0.3); border-radius: 2px; }

        @media (max-width: 640px) {
            nav { padding: 0 16px; }
            .hero { padding: 50px 16px 30px; }
            .container { padding: 0 14px; }
            .variant-head, .variant-body { padding: 16px; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-logo">PHP · BASICS</div>
    <div class="nav-version">PHP <?= PHP_VERSION ?></div>
</nav>

<div class="hero">
    <div class="hero-badge">ПРАКТИЧНА РОБОТА · ВАРІАНТИ 1–5</div>
    <h1 class="hero-title">Основи <span>PHP</span></h1>
    <p class="hero-sub">Змінні · Масиви · Оператори · Умови · Цикли</p>
</div>

<div class="container">

    <?php

    // EN: Collects HTML output for each variant | UK: Збирає HTML-вивід для кожного варіанту | DE: Sammelt HTML-Ausgabe für jede Variante
    $variants = [];

    // EN: Helper — wraps content in a labeled block card | UK: Обгортає вміст у картку з міткою | DE: Umschließt Inhalt in eine beschriftete Blockkarte
    function renderBlock($label, $html) {
        echo "<div class='block'><div class='block-label'>" . $label . "</div><div class='block-content'>" . $html . "</div></div>";
    }

    // EN: Builds an HTML table from rows array | UK: Будує HTML-таблицю з масиву рядків | DE: Erstellt HTML-Tabelle aus Zeilen-Array
    function renderTable($headers, $rows) {
        $out = '<table><tr>';
        foreach ($headers as $h) $out .= "<th>$h</th>";
        $out .= '</tr>';
        foreach ($rows as $row) {
            $out .= '<tr>';
            foreach ($row as $cell) $out .= "<td>$cell</td>";
            $out .= '</tr>';
        }
        return $out . '</table>';
    }

    // EN: Returns pill HTML span | UK: Повертає HTML-span з класом pill | DE: Gibt HTML-span mit pill-Klasse zurück
    function pill($cls, $text) {
        return "<span class='pill $cls'>$text</span>";
    }

    // ── ВАРІАНТ 1: Змінні, масиви, умови ─────────────────────
    // EN: VARIANT 1 — Variables, arrays, conditions | UK: ВАРІАНТ 1 — Змінні, масиви, умови | DE: VARIANTE 1 — Variablen, Arrays, Bedingungen
    ob_start();

    // EN: Demo array prevents IDE from pre-evaluating static conditions | UK: Масив запобігає статичному аналізу IDE | DE: Array verhindert statische Auswertung durch die IDE
    $demo1      = ['name' => 'Марк', 'age' => 20, 'is_student' => 1];
    // EN: $name — string, $age — integer, $is_student — boolean | UK: $name — рядок, $age — ціле число, $is_student — булеве | DE: $name — String, $age — Integer, $is_student — Boolean
    $name       = (string) $demo1['name'];
    $age        = (int)    $demo1['age'];
    $is_student = (bool)   $demo1['is_student'];

    renderBlock('ЗМІННІ', "Мене звати <span class='val'>$name</span>, мені <span class='val'>$age</span> років. Студент: <span class='val'>" . ($is_student ? 'так' : 'ні') . "</span>.");

    // EN: Indexed array + array_sum() counts total | UK: Індексований масив + array_sum() рахує суму | DE: Indiziertes Array + array_sum() berechnet Summe
    $numbers = [1, 2, 3, 4, 5];
    $sum     = array_sum($numbers);
    renderBlock('МАСИВ ЧИСЕЛ · СУМА', "Масив: <span class='val'>[" . implode(', ', $numbers) . "]</span><br>Сума: <span class='val big'>$sum</span>");

    // EN: Associative array — key => value pairs | UK: Асоціативний масив — пари ключ => значення | DE: Assoziatives Array — Schlüssel-Wert-Paare
    $user = ['name' => 'Марк', 'email' => 'alex@example.com', 'phone' => '+38 050 123 4567'];
    $ul   = '<ul>';
    foreach ($user as $k => $v) $ul .= "<li><span class='key'>$k</span>: <span class='val'>$v</span></li>";
    renderBlock('АСОЦІАТИВНИЙ МАСИВ', $ul . '</ul>');

    // EN: if/else checks age > 18 | UK: if/else перевіряє вік > 18 | DE: if/else prüft Alter > 18
    renderBlock('ПЕРЕВІРКА ВІКУ', $age > 18 ? pill('pill-green', '✓ Повнолітній') : pill('pill-red', '✗ Неповнолітній'));

    // EN: intval() breaks static analysis so IDE cannot predict the branch | UK: intval() розриває статичний аналіз IDE | DE: intval() verhindert statische Analyse der IDE
    $grades_demo = [87];
    $grade       = intval($grades_demo[0]);
    // EN: if/elseif/else grading scale 0–100 | UK: Шкала оцінювання if/elseif/else 0–100 | DE: Bewertungsskala if/elseif/else 0–100
    if      ($grade >= 90) $gLabel = ['Відмінно',    'pill-green'];
    elseif  ($grade >= 70) $gLabel = ['Добре',        'pill-cyan'];
    elseif  ($grade >= 50) $gLabel = ['Задовільно',   'pill-orange'];
    else                   $gLabel = ['Незадовільно', 'pill-red'];
    renderBlock('ОЦІНКА · $grade = ' . $grade, pill($gLabel[1], $gLabel[0]));

    $variants[] = ['num' => '01', 'title' => 'Змінні, масиви, умови', 'desc' => '$name · $age · array_sum · grades', 'html' => ob_get_clean()];

    // ── ВАРІАНТ 2: Арифметика, масиви, switch ─────────────────
    // EN: VARIANT 2 — Arithmetic, arrays, switch | UK: ВАРІАНТ 2 — Арифметика, масиви, switch | DE: VARIANTE 2 — Arithmetik, Arrays, switch
    ob_start();

    // EN: + addition, - subtraction, * multiplication, / division | UK: + додавання, - віднімання, * множення, / ділення | DE: + Addition, - Subtraktion, * Multiplikation, / Division
    $a = 5; $b = 10;
    renderBlock(
        "АРИФМЕТИЧНІ ОПЕРАЦІЇ · \$a=$a · \$b=$b",
        renderTable(
            ['ОПЕРАЦІЯ', 'ВИРАЗ', 'РЕЗУЛЬТАТ'],
            [
                ['Сума',     "<span class='key'>\$a + \$b</span>", "<span class='val'>" . ($a+$b) . "</span>"],
                ['Різниця',  "<span class='key'>\$b - \$a</span>", "<span class='val'>" . ($b-$a) . "</span>"],
                ['Добуток',  "<span class='key'>\$a * \$b</span>", "<span class='val'>" . ($a*$b) . "</span>"],
                ['Ділення',  "<span class='key'>\$b / \$a</span>", "<span class='val'>" . ($b/$a) . "</span>"],
            ]
        )
    );

    // EN: Array index starts at 0 — element 3 = index 2, element 5 = index 4 | UK: Індекс масиву з 0 — 3-й елемент = індекс 2, 5-й = індекс 4 | DE: Array-Index beginnt bei 0 — Element 3 = Index 2, Element 5 = Index 4
    $days = ['Понеділок','Вівторок','Середа','Четвер','Пʼятниця','Субота','Неділя'];
    renderBlock('МАСИВ ДНІВ · 3-й та 5-й', "3-й день: <span class='val'>" . $days[2] . "</span><br>5-й день: <span class='val'>" . $days[4] . "</span>");

    // EN: number_format formats number with thousands separator | UK: number_format форматує число з роздільником тисяч | DE: number_format formatiert Zahlen mit Tausendertrennzeichen
    $products = ['Ноутбук' => 35000, 'Мишка' => 800, 'Клавіатура' => 1500, 'Монітор' => 12000];
    $pRows = [];
    foreach ($products as $pn => $pr) $pRows[] = ["<span class='key'>$pn</span>", "<span class='val'>" . number_format($pr, 0, '.', ' ') . " ₴</span>"];
    renderBlock('АСОЦІАТИВНИЙ МАСИВ · ТОВАРИ', renderTable(['ТОВАР', 'ЦІНА'], $pRows));

    // EN: switch checks $day against cases, break exits | UK: switch перевіряє $day по case, break виходить | DE: switch prüft $day gegen cases, break beendet
    $days_demo = ['Monday'];
    $day       = $days_demo[0];
    switch ($day) {
        case 'Monday':             $msg = 'Початок тижня — сила духу!'; $cls = 'warn'; break;
        case 'Friday':             $msg = 'Пʼятниця — майже вихідний!'; $cls = 'good'; break;
        case 'Saturday': case 'Sunday': $msg = 'Вихідний — відпочивай!'; $cls = 'good'; break;
        default:                   $msg = 'Робочий день.'; $cls = 'val'; break;
    }
    renderBlock('SWITCH · $day = "' . $day . '"', "<span class='$cls'>$msg</span>");

    // EN: % modulo returns division remainder — 0 = even, 1 = odd | UK: % повертає залишок від ділення — 0 = парне, 1 = непарне | DE: % gibt den Rest der Division zurück — 0 = gerade, 1 = ungerade
    $x = 15;
    renderBlock("ПАРНЕ / НЕПАРНЕ · \$x = $x", $x % 2 === 0 ? pill('pill-cyan', 'Парне') : pill('pill-violet', 'Непарне'));

    $variants[] = ['num' => '02', 'title' => 'Арифметика, масиви, switch', 'desc' => '$a · $b · days · switch · mod', 'html' => ob_get_clean()];

    // ── ВАРІАНТ 3: Товари, фільми, авторизація ────────────────
    // EN: VARIANT 3 — Products, movies, auth | UK: ВАРІАНТ 3 — Товари, фільми, авторизація | DE: VARIANTE 3 — Produkte, Filme, Auth
    ob_start();

    // EN: float stores decimal prices | UK: float зберігає десяткові ціни | DE: float speichert Dezimalpreise
    $price1 = 250.00; $price2 = 180.50; $price3 = 320.00;
    // EN: $total = sum of three prices | UK: $total = сума трьох цін | DE: $total = Summe der drei Preise
    $total  = $price1 + $price2 + $price3;
    renderBlock('ВАРТІСТЬ ТОВАРІВ',
        "Товар 1: <span class='val'>" . number_format($price1, 2) . " ₴</span><br>" .
        "Товар 2: <span class='val'>" . number_format($price2, 2) . " ₴</span><br>" .
        "Товар 3: <span class='val'>" . number_format($price3, 2) . " ₴</span><br>" .
        "Разом: <span class='val big'>" . number_format($total, 2) . " ₴</span>"
    );

    // EN: foreach iterates each array element | UK: foreach перебирає кожен елемент масиву | DE: foreach iteriert jedes Array-Element
    $movies = ['Inception', 'Interstellar', 'The Matrix', 'Dune', 'Oppenheimer'];
    $mHtml  = '<ul>';
    foreach ($movies as $i => $m) $mHtml .= "<li><span class='muted'>" . ($i+1) . ".</span> <span class='val'>$m</span></li>";
    renderBlock('МАСИВ ФІЛЬМІВ · foreach', $mHtml . '</ul>');

    // EN: str_repeat masks password characters | UK: str_repeat маскує символи пароля | DE: str_repeat maskiert Passwortzeichen
    $account = ['login' => 'dev_user', 'password' => 'secret123', 'email' => 'dev@code.ua'];
    $aHtml   = '<ul>';
    foreach ($account as $k => $v) {
        $display = $k === 'password' ? str_repeat('•', strlen($v)) : $v;
        $aHtml  .= "<li><span class='key'>$k</span>: <span class='val'>$display</span></li>";
    }
    renderBlock('АСОЦІАТИВНИЙ МАСИВ · АКАУНТ', $aHtml . '</ul>');

    // EN: if total > 500 apply 10% discount | UK: якщо сума > 500 — знижка 10% | DE: wenn Summe > 500 — 10% Rabatt
    if ($total > 500) {
        $discount = $total * 0.10;
        $dHtml    = pill('pill-orange', 'Знижка 10%') . "<br><br>Знижка: <span class='warn'>-" . number_format($discount, 2) . " ₴</span><br>До сплати: <span class='good big'>" . number_format($total - $discount, 2) . " ₴</span>";
    } else {
        $dHtml = pill('pill-violet', 'Знижка не нараховується');
    }
    renderBlock('ЗНИЖКА · TOTAL = ' . number_format($total, 2) . ' ₴', $dHtml);

    // EN: === strict comparison checks both value and type | UK: === суворе порівняння перевіряє значення і тип | DE: === strenger Vergleich prüft Wert und Typ
    // EN: && = AND — both must be true | UK: && = І — обидва мають бути true | DE: && = UND — beide müssen wahr sein
    $input_login = 'dev_user'; $input_password = 'secret123';
    $authOk = $input_login === $account['login'] && $input_password === $account['password'];
    renderBlock('АВТОРИЗАЦІЯ · ЛОГІЧНІ ОПЕРАТОРИ', $authOk ? pill('pill-green', '✓ Авторизацію успішно пройдено') : pill('pill-red', '✗ Невірний логін або пароль'));

    $variants[] = ['num' => '03', 'title' => 'Товари, фільми, авторизація', 'desc' => 'foreach · знижка · логічні оператори', 'html' => ob_get_clean()];

    // ── ВАРІАНТ 4: Числа, студенти, таблиця ──────────────────
    // EN: VARIANT 4 — Numbers, students, multiplication table | UK: ВАРІАНТ 4 — Числа, студенти, таблиця | DE: VARIANTE 4 — Zahlen, Studenten, Multiplikationstabelle
    ob_start();

    // EN: Ternary (condition) ? true : false — short if/else | UK: Тернарний (умова) ? так : ні — коротке if/else | DE: Ternärer (Bedingung) ? wahr : falsch — kurzes if/else
    $n1 = 42; $n2 = 17;
    $max = ($n1 > $n2) ? $n1 : $n2;
    $min = ($n1 < $n2) ? $n1 : $n2;
    renderBlock("МАХ І МІН · \$n1=$n1 · \$n2=$n2", "Максимум: <span class='good big'>$max</span><br>Мінімум: <span class='warn big'>$min</span>");

    // EN: average = array_sum / count | UK: середнє = array_sum / count | DE: Durchschnitt = array_sum / count
    $nums = [12, 45, 7, 89, 34, 56, 23];
    $avg  = array_sum($nums) / count($nums);
    renderBlock('СЕРЕДНЄ АРИФМЕТИЧНЕ', "Масив: <span class='val'>[" . implode(', ', $nums) . "]</span><br>Середнє: <span class='val big'>" . number_format($avg, 2) . "</span>");

    // EN: filter inside foreach — skip scores <= 80 | UK: фільтр всередині foreach — пропускаємо бали <= 80 | DE: Filter in foreach — Punkte <= 80 überspringen
    $students = ['Ковальчук Іван' => 92, 'Петренко Марія' => 76, 'Сидоренко Олег' => 85, 'Іваненко Анна' => 64];
    $sRows    = [];
    foreach ($students as $s => $score) if ($score > 80) $sRows[] = ["<span class='val'>$s</span>", "<span class='good'>$score</span>"];
    renderBlock('СТУДЕНТИ З БАЛОМ > 80', renderTable(['ПІБ', 'СЕРЕДНІЙ БАЛ'], $sRows));

    // EN: && = AND, || = OR — combine conditions | UK: && = І, || = АБО — комбінуємо умови | DE: && = UND, || = ODER — Bedingungen kombinieren
    $num = 12;
    if      ($num % 3 === 0 && $num % 5 === 0) $nPill = pill('pill-violet', 'Кратне 3 і 5');
    elseif  ($num % 3 === 0)                   $nPill = pill('pill-cyan',   'Кратне 3');
    elseif  ($num % 5 === 0)                   $nPill = pill('pill-orange', 'Кратне 5');
    else                                        $nPill = pill('pill-red',    'Не кратне ні 3, ні 5');
    renderBlock("КРАТНІСТЬ · \$num = $num", $nPill);

    // EN: for loop — $i from 1 to 10, $i++ increments each step | UK: Цикл for — $i від 1 до 10, $i++ збільшує кожен крок | DE: for-Schleife — $i von 1 bis 10, $i++ erhöht jeden Schritt
    $mRows = [];
    for ($i = 1; $i <= 10; $i++) $mRows[] = ["<span class='key'>7 × $i</span>", "<span class='val'>" . (7 * $i) . "</span>"];
    renderBlock('ТАБЛИЦЯ МНОЖЕННЯ · 7 × (1–10)', renderTable(['ВИРАЗ', 'РЕЗУЛЬТАТ'], $mRows));

    $variants[] = ['num' => '04', 'title' => 'Числа, студенти, таблиця множення', 'desc' => 'max/min · avg · filter · for loop', 'html' => ob_get_clean()];

    // ── ВАРІАНТ 5: Імʼя, країни, міста, рік ──────────────────
    // EN: VARIANT 5 — Name, countries, cities, year | UK: ВАРІАНТ 5 — Імʼя, країни, міста, рік | DE: VARIANTE 5 — Name, Länder, Städte, Jahr
    ob_start();

    // EN: date('Y') gets current year, (int) converts string to integer | UK: date('Y') отримує рік, (int) перетворює рядок на ціле | DE: date('Y') holt aktuelles Jahr, (int) wandelt String in Integer
    $first_name     = 'Марк';
    $last_name      = 'Коваль';
    $year_of_birth  = 2001;
    $current_year   = (int) date('Y');
    // EN: String interpolation — variables inside double quotes are replaced with values | UK: Інтерполяція рядків — змінні в подвійних лапках замінюються значеннями | DE: String-Interpolation — Variablen in doppelten Anführungszeichen werden ersetzt
    $full_name      = "$first_name $last_name";
    // EN: age = current year - birth year | UK: вік = поточний рік - рік народження | DE: Alter = aktuelles Jahr - Geburtsjahr
    $calculated_age = $current_year - $year_of_birth;
    renderBlock('ІМʼЯ ТА ВІК', "Повне імʼя: <span class='val'>$full_name</span><br>Рік народження: <span class='val'>$year_of_birth</span><br>Вік: <span class='val big'>$calculated_age років</span>");

    // EN: <ol> = ordered HTML list (numbered) | UK: <ol> — нумерований HTML-список | DE: <ol> = geordnete HTML-Liste (nummeriert)
    $countries = ['Україна', 'Польща', 'Франція', 'Японія'];
    $cHtml     = '<ol>';
    foreach ($countries as $c) $cHtml .= "<li><span class='val'>$c</span></li>";
    renderBlock('МАСИВ КРАЇН · HTML-СПИСОК', $cHtml . '</ol>');

    // EN: filter cities with population > 1 million | UK: фільтруємо міста з населенням > 1 млн | DE: Städte mit Einwohnern > 1 Million filtern
    $cities = ['Київ' => 2900000, 'Харків' => 1430000, 'Одеса' => 993000, 'Дніпро' => 990000, 'Львів' => 760000];
    $ciRows = [];
    foreach ($cities as $city => $pop) if ($pop > 1000000) $ciRows[] = ["<span class='val'>$city</span>", "<span class='good'>" . number_format($pop, 0, '.', ' ') . "</span>"];
    renderBlock('МІСТА З НАСЕЛЕННЯМ > 1 МЛН', renderTable(['МІСТО', 'НАСЕЛЕННЯ'], $ciRows));

    // EN: ternary inside echo — (condition) ? show_if_true : show_if_false | UK: тернарний в echo — (умова) ? якщо_так : якщо_ні | DE: Ternärer in echo — (Bedingung) ? wenn_wahr : wenn_falsch
    $number = 8;
    renderBlock("ПАРНЕ / НЕПАРНЕ · \$number = $number", $number % 2 === 0 ? pill('pill-cyan', 'Парне') : pill('pill-violet', 'Непарне'));

    // EN: Leap year: divisible by 4 AND not 100, OR divisible by 400 | UK: Високосний: ділиться на 4 І не на 100, АБО на 400 | DE: Schaltjahr: teilbar durch 4 UND nicht 100, ODER durch 400
    $isLeap = ($current_year % 4 === 0 && $current_year % 100 !== 0) || ($current_year % 400 === 0);
    renderBlock("ВИСОКОСНИЙ РІК · $current_year", $isLeap ? pill('pill-green', "✓ $current_year — високосний рік") : pill('pill-orange', "$current_year — не високосний рік"));

    $variants[] = ['num' => '05', 'title' => 'Імʼя, країни, міста, рік', 'desc' => 'date() · foreach · HTML-список · mod', 'html' => ob_get_clean()];

    foreach ($variants as $v): ?>
        <div class="variant">
            <div class="variant-head">
                <div class="variant-num"><?= $v['num'] ?></div>
                <div>
                    <h2><?= $v['title'] ?></h2>
                    <p><?= $v['desc'] ?></p>
                </div>
            </div>
            <div class="variant-body">
                <?= $v['html'] ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<footer>PHP <?= PHP_VERSION ?> · ПРАКТИЧНА РОБОТА · ВАРІАНТИ 1–5</footer>

</body>
</html>