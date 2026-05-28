<?php
// api.php — REST API для блогу (Варіант 5)
// Запуск: php -S localhost:8080

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Preflight OPTIONS запит (для CORS з браузерного клієнта)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── Конфігурація ───────────────────────────────────────────────
define('DATA_FILE', __DIR__ . '/posts.json');

// ─── Утиліти ────────────────────────────────────────────────────

/** Зчитує всі пости з JSON-файлу */
function readPosts(): array {
    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, '[]');
    }
    $raw = file_get_contents(DATA_FILE);
    return json_decode($raw, true) ?? [];
}

/** Записує масив постів у JSON-файл */
function writePosts(array $posts): void {
    file_put_contents(DATA_FILE, json_encode(array_values($posts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/** Генерує новий унікальний ID */
function generateId(array $posts): int {
    if (empty($posts)) return 1;
    return max(array_column($posts, 'id')) + 1;
}

/** Відправляє JSON-відповідь з HTTP-кодом */
function respond(int $code, mixed $data): void {
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/** Повертає тіло запиту як асоціативний масив */
function getBody(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

/** Валідує обов'язкові поля */
function validateFields(array $data, array $required): ?string {
    foreach ($required as $field) {
        if (empty(trim((string)($data[$field] ?? '')))) {
            return "Поле '{$field}' є обов'язковим";
        }
    }
    return null;
}

// ─── Маршрутизація ──────────────────────────────────────────────

$method = $_SERVER['REQUEST_METHOD'];

// Отримуємо шлях: /posts або /posts/3
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Розбиваємо: ['', 'posts'] або ['', 'posts', '3']
$segments = explode('/', $uri);

// Перевіряємо що маршрут починається з /posts
if (($segments[1] ?? '') !== 'posts') {
    respond(404, ['error' => 'Маршрут не знайдено. Використовуйте /posts']);
}

$id = isset($segments[2]) ? (int)$segments[2] : null;

// ─── Обробники ──────────────────────────────────────────────────

switch ($method) {

    // ── GET /posts — усі пости ──────────────────────────────────
    // ── GET /posts/{id} — один пост ────────────────────────────
    case 'GET':
        $posts = readPosts();

        if ($id === null) {
            // Підтримка пошуку: GET /posts?search=слово
            $search = trim($_GET['search'] ?? '');
            if ($search !== '') {
                $lower = mb_strtolower($search);
                $posts = array_filter($posts, fn($p) =>
                    str_contains(mb_strtolower($p['title']),   $lower) ||
                    str_contains(mb_strtolower($p['author']),  $lower) ||
                    str_contains(mb_strtolower($p['content']), $lower)
                );
            }

            respond(200, [
                'success' => true,
                'count'   => count($posts),
                'posts'   => array_values($posts),
            ]);
        }

        // Пошук за ID
        $post = current(array_filter($posts, fn($p) => $p['id'] === $id));
        if (!$post) {
            respond(404, ['error' => "Пост з ID {$id} не знайдено"]);
        }
        respond(200, ['success' => true, 'post' => $post]);

    // ── POST /posts — створити пост ─────────────────────────────
    case 'POST':
        if ($id !== null) {
            respond(405, ['error' => 'POST не підтримує ID. Використовуйте POST /posts']);
        }

        $body = getBody();
        $err  = validateFields($body, ['title', 'author', 'content']);
        if ($err) respond(400, ['error' => $err]);

        $posts   = readPosts();
        $newPost = [
            'id'         => generateId($posts),
            'title'      => trim($body['title']),
            'author'     => trim($body['author']),
            'content'    => trim($body['content']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $posts[] = $newPost;
        writePosts($posts);

        respond(201, ['success' => true, 'message' => 'Пост створено', 'post' => $newPost]);

    // ── PUT /posts/{id} — оновити пост ─────────────────────────
    case 'PUT':
        if ($id === null) {
            respond(400, ['error' => 'Вкажіть ID поста: PUT /posts/{id}']);
        }

        $posts = readPosts();
        $index = null;
        foreach ($posts as $i => $p) {
            if ($p['id'] === $id) { $index = $i; break; }
        }

        if ($index === null) {
            respond(404, ['error' => "Пост з ID {$id} не знайдено"]);
        }

        $body = getBody();
        if (empty($body)) {
            respond(400, ['error' => 'Тіло запиту порожнє']);
        }

        // Часткове оновлення — оновлюємо тільки передані поля
        $allowed = ['title', 'author', 'content'];
        foreach ($allowed as $field) {
            if (isset($body[$field]) && trim($body[$field]) !== '') {
                $posts[$index][$field] = trim($body[$field]);
            }
        }
        $posts[$index]['updated_at'] = date('Y-m-d H:i:s');

        writePosts($posts);
        respond(200, ['success' => true, 'message' => 'Пост оновлено', 'post' => $posts[$index]]);

    // ── DELETE /posts/{id} — видалити пост ─────────────────────
    case 'DELETE':
        if ($id === null) {
            respond(400, ['error' => 'Вкажіть ID поста: DELETE /posts/{id}']);
        }

        $posts    = readPosts();
        $filtered = array_filter($posts, fn($p) => $p['id'] !== $id);

        if (count($filtered) === count($posts)) {
            respond(404, ['error' => "Пост з ID {$id} не знайдено"]);
        }

        writePosts($filtered);
        respond(200, ['success' => true, 'message' => "Пост ID {$id} видалено"]);

    default:
        respond(405, ['error' => "Метод {$method} не підтримується"]);
}
