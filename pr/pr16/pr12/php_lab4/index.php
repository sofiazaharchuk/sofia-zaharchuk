<?php
// index.php — Головна точка входу (маршрутизатор)
session_start();

// Перевірка: активна сесія → secure.php
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: secure.php');
    exit;
}

// Перевірка: cookie блокування → blocked.php
if (isset($_COOKIE['blocked'])) {
    // Перевіряємо чи блокування ще активне
    if ((int)$_COOKIE['blocked'] > time()) {
        header('Location: blocked.php');
        exit;
    } else {
        // Блокування вже минуло — видаляємо cookie
        setcookie('blocked', '', time() - 1, '/');
        setcookie('block_reason', '', time() - 1, '/');
    }
}

// Інакше → сторінка входу
header('Location: login.php');
exit;
