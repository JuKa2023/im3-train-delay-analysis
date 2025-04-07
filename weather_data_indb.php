<?php
require_once 'config.php';

$weather_data = include('src/weather_data_fetch.php');

$location = $weather_data['location'];
$temperature_celsius = $weather_data['current']['temperature_2m'] ?? 0; 
$wind_speed = $weather_data['current']['wind_speed_10m'] ?? 0; 
$weather_code = $weather_data['current']['weather_code'] ?? 0;
$rain = $weather_data['current']['rain'] ?? 0; 
$showers = $weather_data['current']['showers'] ?? 0; 
$snowfall = $weather_data['current']['snowfall'] ?? 0;  
$wind_gusts = $weather_data['current']['wind_gusts_10m'] ?? 0; 
$timestamp = date('Y-m-d H:i:s'); // Current timestamp

$response = [];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    $sql = "INSERT INTO weather_data (location, temperature_celsius, wind_speed, rain, weather_code, showers, snowfall, wind_gusts_10m, timestamp) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $success = $stmt->execute([
        $location,
        $temperature_celsius,
        $wind_speed,
        $rain,
        $weather_code,
        $showers,
        $snowfall,
        $wind_gusts,
        $timestamp
    ]);

    if ($success) {
        $response = [
            "status" => "success",
            "message" => "Weather data inserted successfully",
            "data" => [
                "location" => $location,
                "temperature_celsius" => $temperature_celsius,
                "wind_speed" => $wind_speed,
                "rain" => $rain,
                "weather_code" => $weather_code,
                "showers" => $showers,
                "snowfall" => $snowfall,
                "wind_gusts_10m" => $wind_gusts,
                "timestamp" => $timestamp
            ]
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Error inserting weather data",
            "error_info" => $stmt->errorInfo()
        ];
    }
} catch (PDOException $e) {
    $response = [
        "status" => "error",
        "message" => "Could not connect to the database",
        "error_info" => $e->getMessage()
    ];
}

header('Content-Type: application/json'); // To improve visualisation in browser
echo json_encode($response, JSON_PRETTY_PRINT);
?>