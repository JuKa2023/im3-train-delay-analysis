<?php
// Include the database connection file
include 'config.php'; 

// Function to fetch current weather data from the Open-Meteo API using cURL
function fetchCurrentWeatherData($latitude, $longitude) {
    // Construct the API URL for current weather
    $url = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current=temperature_2m,rain,showers,snowfall,weather_code,wind_speed_10m,wind_gusts_10m";

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);         // Set the URL to fetch
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed (use with caution)

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        return json_encode(['error' => 'cURL Error: ' . curl_error($ch)]); // Return error as JSON
    }

    // Close the cURL session
    curl_close($ch);

    // Decode the JSON response
    $decoded_data = json_decode($response, true); // Decode the response

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        return json_encode(['error' => 'JSON Error: ' . json_last_error_msg()]); // Return JSON error
    }

    return $decoded_data; // Return the decoded JSON data
}

// Define Bern's coordinates
$latitude = 46.948832;
$longitude = 7.439136;

// Fetch current weather data
$weather_data = fetchCurrentWeatherData($latitude, $longitude);

// Set the header to return JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($weather_data); // Encode and echo the data
?>