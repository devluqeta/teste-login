<?php
declare(strict_types=1);

$DB_HOST = 'localhost';
$DB_NAME = 'login_teste';
$DB_USER = 'root';
$DB_PASS = ''; // ajuste conforme seu ambiente (XAMPP/MAMP/etc)

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        $options
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Erro ao conectar no banco.');
}
