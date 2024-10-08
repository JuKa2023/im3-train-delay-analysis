<?php
// Include the database connection file
include 'db_connection.php'; // This file should handle the database connection logic
// fetch_weather.php

// Function to fetch weather data from the Open-Meteo API
function fetchWeatherData($latitude, $longitude, $datetime) {
    $date = new DateTime($datetime);
    $url = $date > new DateTime()
        ? "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&hourly=temperature_2m,windspeed_10m,winddirection_10m,weathercode&start_date={$date->format('Y-m-d')}&end_date={$date->format('Y-m-d')}"
        : "https://archive-api.open-meteo.com/v1/archive?latitude={$latitude}&longitude={$longitude}&start_date={$date->format('Y-m-d')}&end_date={$date->format('Y-m-d')}&hourly=temperature_2m,windspeed_10m,winddirection_10m,weathercode";

    $weather_data = json_decode(file_get_contents($url), true);
    return $weather_data;
}
?>

// Function to insert weather data into the database
/*function insertWeatherDataIntoDatabase($weatherData, $conn) {
    if (isset($weatherData['daily'])) {
        foreach ($weatherData['daily']['time'] as $index => $date) {
            $location = 'Your_Location'; // Replace with your actual location name or dynamic data
            $temperature = $weatherData['daily']['temperature_2m_max'][$index];
            $precipitation = $weatherData['daily']['precipitation_sum'][$index];
            $windSpeed = $weatherData['daily']['wind_speed_max'][$index];
            $weatherCondition = determineWeatherCondition($temperature, $precipitation, $windSpeed); // Simple logic to categorize weather condition

            // Prepare the SQL query to insert the data
            $sql = "INSERT INTO Wetter (ort, datum, temperatur, niederschlag, Windgeschwindigkeit, wetterzustand)
                    VALUES ('$location', '$date', '$temperature', '$precipitation', '$windSpeed', '$weatherCondition')";

            // Execute the query
            if ($conn->query($sql) === FALSE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "No data available for the specified date range.";
    }
}*/

// Function to determine the weather condition based on data (simple example)
function determineWeatherCondition($temperature, $precipitation, $windSpeed) {
    if ($precipitation > 10 && $windSpeed > 20) {
        return 'Stormy';
    } elseif ($temperature > 30) {
        return 'Hot';
    } elseif ($temperature < 0) {
        return 'Cold';
    } else {
        return 'Moderate';
    }
}

// zu ersetzten mit den angaben der  Untersuchen Orte
/*$latitude = "40.7128";   // Example: New York City
$longitude = "-74.0060";
$startDate = "2023-01-01";    // Start of the year 2023
$endDate = "2023-12-31";      // End of the year 2023

try {
    // Assuming db_connection.php initializes a $conn variable for the database connection
    fetchWeatherData($latitude, $longitude, $startDate, $endDate, $conn);
    echo "Weather data successfully inserted into the database.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>