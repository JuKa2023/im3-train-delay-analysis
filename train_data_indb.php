<?php

// Include your transform script if needed
$jsonData = include('230_transform.php');

// Decode the JSON data into an array
$trainDetails = json_decode($jsonData, true);

// Include the database connection file
require_once 'config.php';

try {
    // Establish PDO connection using the config file details
    $pdo = new PDO($dsn, $username, $password, $options);

    // Process each train detail and insert it into the database if not exists
    foreach ($trainDetails as $train) {
        $departureTimestamp = $train['Departure Time Stamp'];
        $platform = $train['Platform'];

        // Check if the entry already exists in the database
        $stmt = $pdo->prepare("SELECT id FROM stationtable WHERE departure_time_stamp = ? AND platform = ?");
        $stmt->execute([$departureTimestamp, $platform]);

        // If no record is found, insert the new data
        if ($stmt->rowCount() == 0) {
            // Prepare the insert statement
            $insertStmt = $pdo->prepare("
                INSERT INTO stationtable (destination, departure, departure_time_stamp, departure_time, delay, platform)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            // Execute the insertion
            if ($insertStmt->execute([
                $train['Train to'],
                $train['Departure Station'],
                $departureTimestamp,
                $train['Departure Time'],
                $train['Delay'],
                $platform
            ])) {
                echo "New record inserted successfully for train to " . $train['Train to'] . ".\n";
            } else {
                echo "Error inserting record for train to " . $train['Train to'] . ": " . implode(":", $insertStmt->errorInfo()) . "\n";
            }
        } else {
            echo "Record with departure timestamp $departureTimestamp and platform $platform already exists.\n";
        }
    }
} catch (PDOException $e) {
    die("Verbindung zur Datenbank konnte nicht hergestellt werden: " . $e->getMessage());
}

?>