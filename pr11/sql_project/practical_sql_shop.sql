-- ============================================================
-- ВАРІАНТ 3: Магазин
-- База даних: shop
-- ============================================================

DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET character_set_connection=utf8mb4;

-- ── 1. Головна таблиця: products ─────────────────────────────
CREATE TABLE products (
                          id           INT PRIMARY KEY AUTO_INCREMENT,
                          product_name VARCHAR(100) NOT NULL,
                          category     VARCHAR(50),
                          price        DECIMAL(10,2),
                          quantity     INT
);

-- ── 2. Залежна таблиця: orders (ON DELETE CASCADE) ───────────
CREATE TABLE orders (
                        id            INT PRIMARY KEY AUTO_INCREMENT,
                        product_id    INT,
                        customer_name VARCHAR(50),
                        order_date    DATE,
                        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ── 3. Залежна таблиця: reviews (ON DELETE SET NULL) ─────────
CREATE TABLE reviews (
                         id          INT PRIMARY KEY AUTO_INCREMENT,
                         product_id  INT,
                         review_text TEXT,
                         rating      INT,
                         FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ── 4. Залежна таблиця: inventory_logs (ON DELETE SET NULL) ──
CREATE TABLE inventory_logs (
                                id            INT PRIMARY KEY AUTO_INCREMENT,
                                product_id    INT,
                                change_date   DATE,
                                change_amount INT,
                                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ============================================================
-- INSERT
-- ============================================================

-- 5 продуктів різних категорій
INSERT INTO products (product_name, category, price, quantity) VALUES
                                                                   ('MacBook Pro 14"',        'Електроніка',  89999.00, 10),
                                                                   ('Механічна клавіатура',   'Електроніка',   3499.00, 25),
                                                                   ('Зошит A4 96 аркушів',    'Канцелярія',      89.00, 200),
                                                                   ('Набір ручок Pilot',      'Канцелярія',     149.00, 300),
                                                                   ('Офісне крісло Comfort',  'Меблі',         8999.00,  8);

-- Замовлення для кожного продукту
INSERT INTO orders (product_id, customer_name, order_date) VALUES
                                                               (1, 'Олексій Коваль',    '2024-01-15'),
                                                               (2, 'Марія Петренко',    '2024-01-20'),
                                                               (3, 'Іван Сидоренко',    '2024-02-01'),
                                                               (4, 'Анна Бойко',        '2024-02-10'),
                                                               (5, 'Дмитро Мельник',    '2024-02-14');

-- Відгуки для кількох продуктів (1-2 шт.)
INSERT INTO reviews (product_id, review_text, rating) VALUES
                                                          (1, 'Чудовий ноутбук! Дуже задоволений покупкою.',    9),
                                                          (1, 'Трохи дорогий, але якість на висоті.',            8),
                                                          (2, 'Відмінна клавіатура для програмістів.',           10),
                                                          (4, 'Ручки пишуть чудово, рекомендую.',                8);

-- Зміни залишку (inventory_logs)
INSERT INTO inventory_logs (product_id, change_date, change_amount) VALUES
                                                                        (1, '2024-01-10', -2),
                                                                        (2, '2024-01-18',  5),
                                                                        (3, '2024-01-25', 50),
                                                                        (4, '2024-02-05', 100),
                                                                        (5, '2024-02-12', -1);

-- ============================================================
-- UPDATE
-- ============================================================

-- Оновити ціну одного продукту (MacBook Pro)
UPDATE products
SET price = 84999.00
WHERE id = 1;

-- Оновити дату замовлення
UPDATE orders
SET order_date = '2024-03-01'
WHERE id = 3;

-- ============================================================
-- DELETE + ПЕРЕВІРКИ
-- ============================================================

-- Видалити товар з id = 2 (Механічна клавіатура)
-- orders для цього продукту зникнуть (CASCADE)
-- reviews і inventory_logs — залишаться (SET NULL)
DELETE FROM products WHERE id = 2;

-- Перевірка: orders має НЕ містити записів з product_id = 2
SELECT 'Перевірка CASCADE в orders:' AS info;
SELECT * FROM orders WHERE product_id = 2;

-- Перевірка: reviews містить запис, але product_id = NULL
SELECT 'Перевірка SET NULL в reviews:' AS info;
SELECT * FROM reviews WHERE product_id IS NULL;

-- Перевірка: inventory_logs містить запис, але product_id = NULL
SELECT 'Перевірка SET NULL в inventory_logs:' AS info;
SELECT * FROM inventory_logs WHERE product_id IS NULL;

-- ============================================================
-- SELECT ЗАПИТИ
-- ============================================================

-- 1. Всі продукти
SELECT 'Запит 1: всі продукти' AS query;
SELECT * FROM products;

-- 2. Товари категорії 'Електроніка'
SELECT 'Запит 2: категорія Електроніка' AS query;
SELECT * FROM products
WHERE category = 'Електроніка';

-- 3. Всі товари відсортовані за ціною (зростання)
SELECT 'Запит 3: сортування за ціною ASC' AS query;
SELECT * FROM products
ORDER BY price ASC;

-- 4. Товари категорії 'Канцелярія', відсортовані за кількістю
SELECT 'Запит 4: Канцелярія, сортування за кількістю' AS query;
SELECT * FROM products
WHERE category = 'Канцелярія'
ORDER BY quantity ASC;