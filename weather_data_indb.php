<?php

// Include the database connection file
require_once 'config.php';

// Read the weather data from the JSON file
$weather_data = file_get_contents('weather_data.json');

// Decode the JSON data into a PHP array
$current_weather = json_decode($weather_data, true);

// Check if the weather data was properly decoded
if ($current_weather) {
    // Map the data to variables for database insertion
    $temperature_celsius = $current_weather['temperature'];
    $wind_speed = $current_weather['windspeed'];
    $weather_code = $current_weather['weathercode']; // Map weather codes to conditions if necessary
    $rain = isset($current_weather['precipitation']) ? $current_weather['precipitation'] : 0;

    // Insert data into the database
    try {
        // Establish a new PDO instance with the config from config.php
        $pdo = new PDO($dsn, $username, $password, $options);

        // SQL query with placeholders for inserting data
        $sql = "INSERT INTO weather_data (location, temperature_celsius, wind_speed, rain, weather_code) 
                VALUES (?, ?, ?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Execute the prepared statement with the mapped weather data
        $stmt->execute([
            'Bern', // Assuming 'Bern' as the location
            $temperature_celsius,
            $wind_speed,
            $rain,
            $weather_code
        ]);

        echo "Weather data successfully inserted into the database.";
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }
} else {
    echo "Failed to decode weather data.";
}
?>