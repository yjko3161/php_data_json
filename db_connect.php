<?php
// db_connect.php
// 보안 상수 정의 (config.php 직접 접근 방지)
define('ALLOWED_ACCESS', true);

$configPath = __DIR__ . '/config.php';

if (!file_exists($configPath)) {
    die("Configuration error: config.php file missing.");
}

$config = require $configPath;

$host = $config['DB_HOST'];
$dbname = $config['DB_NAME'];
$username = $config['DB_USER'];
$password = $config['DB_PASS'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}