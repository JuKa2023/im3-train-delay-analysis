<?php
// Include the database connection file
require_once 'config.php';

try {
    // Establish PDO connection using the config file details
    $pdo = new PDO($dsn, $username, $password, $options);

    // Get the resolution parameter from GET request
    $resolution = isset($_GET['resolution']) ? $_GET['resolution'] : 'daily';

    // Set the time interval and group by clause based on resolution
    if ($resolution === 'hourly') {
        $timeInterval = '24 HOUR';
        $groupBy = 'DATE(departure_time), HOUR(departure_time)';
        $dateFormat = 'DATE_FORMAT(departure_time, "%Y-%m-%d %H:00:00")';
        $weatherDateFormat = 'DATE_FORMAT(timestamp, "%Y-%m-%d %H:00:00")';
    } else {
        $timeInterval = '30 DAY';
        $groupBy = 'DATE(departure_time)';
        $dateFormat = 'DATE(departure_time)';
        $weatherDateFormat = 'DATE(timestamp)';
    }

    // Query to fetch train data
    $trainStmt = $pdo->prepare("
        SELECT 
            {$dateFormat} as date,
            COUNT(id) as total_trains,
            SUM(delay > 0) as total_delays,
            AVG(delay) as avg_delay
        FROM stationtable
        WHERE departure_time >= NOW() - INTERVAL {$timeInterval}
        GROUP BY {$groupBy}
        ORDER BY date ASC
    ");
    $trainStmt->execute();
    $trainData = $trainStmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch weather data and calculate disruption score
    $weatherStmt = $pdo->prepare("
        SELECT 
            {$weatherDateFormat} as date,
            AVG(temperature_celsius) as avg_temperature,
            AVG(wind_speed) as avg_wind_speed,
            AVG(rain) as avg_rain,
            AVG(showers) as avg_showers,
            AVG(snowfall) as avg_snowfall,
            AVG(wind_gusts_10m) as avg_wind_gusts,
            AVG(weather_code) as avg_weather_code,
            (AVG(wind_speed) * 0.4 + AVG(wind_gusts_10m) * 0.5 + AVG(rain) * 0.2 + AVG(showers) * 0.2 + AVG(snowfall) * 0.3) AS disruption_score
        FROM weather_data
        WHERE timestamp >= NOW() - INTERVAL {$timeInterval}
        GROUP BY {$weatherDateFormat}
        ORDER BY date ASC
    ");
    $weatherStmt->execute();
    $weatherData = $weatherStmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine train and weather data by date
    $combinedData = [];
    foreach ($trainData as $train) {
        $date = $train['date'];
        $weather = array_filter($weatherData, function($w) use ($date) {
            return $w['date'] === $date;
        });
        $weather = reset($weather); // Get the first (and only) match if available

        $combinedData[] = [
            'date' => $date,
            'total_trains' => $train['total_trains'],
            'total_delays' => $train['total_delays'],
            'avg_delay' => $train['avg_delay'],
            'avg_temperature' => $weather['avg_temperature'] ?? null,
            'avg_wind_speed' => $weather['avg_wind_speed'] ?? null,
            'avg_rain' => $weather['avg_rain'] ?? null,
            'avg_showers' => $weather['avg_showers'] ?? null,
            'avg_snowfall' => $weather['avg_snowfall'] ?? null,
            'avg_wind_gusts' => $weather['avg_wind_gusts'] ?? null,
            'disruption_score' => $weather['disruption_score'] ?? null
        ];
    }

    // Output the combined response as JSON
    header('Content-Type: application/json');
    echo json_encode($combinedData, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
?>