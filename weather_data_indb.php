<?php
// Include the database connection file
require_once 'config.php';

require_once 'weather_data_fetch.php'; // Include the weather data fetch file

// Fetch the current weather data by including the fetch file
$weather_data = fetchCurrentWeatherData(46.948832, 7.439136); // Call the function directly

// Debugging: Check the content of the response
var_dump($weather_data); // See what is returned here

// Check if the weather data was properly decoded
if (!is_array($weather_data) || isset($weather_data['error'])) {
    echo "Error fetching weather data: " . ($weather_data['error'] ?? 'Unknown error');
    exit; // Exit if there is an error
}

// Map the data to variables for database insertion
$temperature_celsius = $weather_data['current']['temperature_2m'];
$wind_speed = $weather_data['current']['wind_speed_10m'];
$weather_code = $weather_data['current']['weather_code']; // Adjust according to your API's response
$rain = $weather_data['current']['rain'] ?? 0; // Use null coalescing operator for optional data

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
?>