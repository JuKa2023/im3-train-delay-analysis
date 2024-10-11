<?php
// Include the database connection file
include 'config.php'; 
// Function to fetch current weather data from the Open-Meteo API
function fetchCurrentWeatherData($latitude, $longitude) {
    // Construct the API URL for current weather
    $url = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current=temperature_2m,rain,showers,snowfall,weather_code,wind_speed_10m,wind_gusts_10m";

    // Fetch weather data
    $response = file_get_contents($url);
    return json_decode($response, true); // Return the decoded JSON data
}

// Define Bern's coordinates
$latitude = 46.948832;
$longitude = 7.439136;

// Fetch current weather data
$weather_data = fetchCurrentWeatherData($latitude, $longitude);

print_r( $weather_data)?>