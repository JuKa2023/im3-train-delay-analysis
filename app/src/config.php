<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $envFile = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Database connection parameters from environment variables
$host = getenv('DB_HOST') ?: 'mysql';
$dbname = getenv('DB_NAME') ?: 'im3';
$username = getenv('DB_USER') ?: 'im3_user';
$password = getenv('DB_PASSWORD') ?: 'im3_password';

// DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

// PDO options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Debug information (remove in production)
error_log("Attempting to connect to database with:");
error_log("Host: $host");
error_log("Database: $dbname");
error_log("Username: $username");
error_log("DSN: $dsn");

?>