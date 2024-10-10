<?php

require_once 'db_connection.php'; // Ensure this is your PDO connection file

$trainDetails = require 'train_data_fetch.php'; // Assuming this returns an array of train details

//print_r($trainDetails); // For debugging purposes

// Assuming $trainDetails is the array with train data as processed from the previous code
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
            echo "New record inserted successfully.\n";
        } else {
            echo "Error inserting record: " . implode(":", $insertStmt->errorInfo()) . "\n";
        }
    } else {
        echo "Record with departure timestamp $departureTimestamp and platform $platform already exists.\n";
    }
}

// No need to close the connection manually, as PDO will handle it when the script ends
?>