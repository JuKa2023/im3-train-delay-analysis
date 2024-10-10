<?php
$host = getenv('DB_HOST') ?: 'railway';
$db   = getenv('DB_NAME') ?: 'your_database_names';
$user = getenv('DB_USER') ?: 'your_username';
$pass = getenv('DB_PASSWORD') ?: 'your_password';
$charset = 'utf8mb4';

// DSN (Datenquellenname) für PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Optionen für PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}