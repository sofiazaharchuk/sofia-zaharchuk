<?php
// logout.php — Завершення сесії
session_start();

// Зберігаємо ім'я для повідомлення (до очищення)
$username = $_SESSION['username'] ?? null;

// 1. Знищуємо всі дані сесії
$_SESSION = [];

// 2. Видаляємо сесійний cookie (якщо є)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Знищуємо сесію на сервері
session_destroy();

// 4. Видаляємо cookies пов'язані з авторизацією
// (НЕ видаляємо last_login_user і last_login_time — вони для UX)
// Тільки якщо є cookie блокування — теж очищаємо
if (isset($_COOKIE['blocked'])) {
    setcookie('blocked', '', time() - 1, '/');
    setcookie('block_reason', '', time() - 1, '/');
}

// 5. Переадресація на головну
header('Location: index.php');
exit;
