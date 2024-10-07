<?php
// Include the database connection file
include 'db_connection.php'; // This file should handle the database connection logic

// Function to fetch weather data from Open-Meteo API for the entire year of 2023
function fetchWeatherData($latitude, $longitude, $startDate, $endDate, $conn) {
    // Open-Meteo API URL with query parameters for the full year
    $apiUrl = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&start_date={$startDate}&end_date={$endDate}&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,snowfall_sum,wind_speed_max,pressure_msl&timezone=auto";

    // Fetch weather data using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Check for errors in the response
    if ($response === false) {
        throw new Exception("Failed to retrieve weather data.");
    }

    // Decode JSON response to PHP array
    $weatherData = json_decode($response, true);

    // Insert the weather data into the database
    insertWeatherDataIntoDatabase($weatherData, $conn);
}

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
$longitude = "-74.0060";*/
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