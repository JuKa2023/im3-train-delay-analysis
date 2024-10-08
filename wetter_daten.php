<?php
// Include the database connection file
include 'db_connection.php'; // This file should handle the database connection logic

// Array of fixed locations with their latitude and longitude
$locations = [
    ['name' => 'Zurich', 'latitude' => 47.3769, 'longitude' => 8.5417],
    ['name' => 'Geneva', 'latitude' => 46.2044, 'longitude' => 6.1432],
    ['name' => 'Lugano', 'latitude' => 46.0037, 'longitude' => 8.9511],
    ['name' => 'Visp', 'latitude' => 46.2932, 'longitude' => 7.8817],
];

// Function to fetch weather data from Open-Meteo API for the entire year of 2023
function fetchWeatherData($latitude, $longitude, $startDate, $endDate, $locationName, $pdo) {
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
    insertWeatherDataIntoDatabase($weatherData, $locationName, $pdo);
}

// Function to insert weather data into the database
function insertWeatherDataIntoDatabase($weatherData, $locationName, $pdo) {
    if (isset($weatherData['daily'])) {
        foreach ($weatherData['daily']['time'] as $index => $date) {
            $temperature = $weatherData['daily']['temperature_2m_max'][$index];
            $precipitation = $weatherData['daily']['precipitation_sum'][$index];
            $windSpeed = $weatherData['daily']['wind_speed_max'][$index];
            $weatherCondition = determineWeatherCondition($temperature, $precipitation, $windSpeed); // Simple logic to categorize weather condition

            // Prepare the SQL query to insert the data using PDO
            $sql = "INSERT INTO Wetter (ort, datum, temperatur, niederschlag, Windgeschwindigkeit, wetterzustand) 
                    VALUES (:location, :date, :temperature, :precipitation, :windSpeed, :weatherCondition)";

            // Execute the query using prepared statements
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':location' => $locationName,
                ':date' => $date,
                ':temperature' => $temperature,
                ':precipitation' => $precipitation,
                ':windSpeed' => $windSpeed,
                ':weatherCondition' => $weatherCondition
            ]);
        }
    } else {
        echo "No data available for the specified date range.";
    }
}

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

$startDate = "2023-01-01";    // Start of the year 2023
$endDate = "2023-12-31";      // End of the year 2023

try {
    // Loop through each fixed location and fetch its weather data
    foreach ($locations as $location) {
        fetchWeatherData($location['latitude'], $location['longitude'], $startDate, $endDate, $location['name'], $pdo);
        echo "Weather data successfully inserted into the database for location: " . $location['name'] . "<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>