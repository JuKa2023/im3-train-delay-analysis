<?php

// Fetch the train details
$trainDetails = include('train_data_fetch.php');  // This fetches the full array from train_data_fetch.php

// Include the database connection file
require_once 'config.php';

// Initialize an array to store the response
$response = [];

try {
    // Establish PDO connection using the config file details
    $pdo = new PDO($dsn, $username, $password, $options);

    // Process each train detail and insert it into the database if not exists
    foreach ($trainDetails as $train) {
        $departureTimestamp = $train['Departure Time Stamp'];
        $platform = $train['Platform'];

        // Only attempt to insert valid timestamps
        if ($departureTimestamp !== 'No departure timestamp') {
            // Skip if the departure time is in the past
            $departureTimestampUtc = gmdate('Y-m-d H:i:s', $departureTimestamp);
            $currentTimestamp = gmdate('Y-m-d H:i:s', time());
            if ($departureTimestampUtc < $currentTimestamp) {
                $response[] = [
                    "status" => "skipped",
                    "train_to" => $train['Train to'],
                    "message" => "Departure time is in the past",
                    "departure_timestamp" => $departureTimestampUtc,
                    "current_timestamp" => $currentTimestamp
                ];
                continue;
            }

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
                    $response[] = [
                        "status" => "success",
                        "train_to" => $train['Train to'],
                        "message" => "New record inserted successfully"
                    ];
                } else {
                    $response[] = [
                        "status" => "error",
                        "train_to" => $train['Train to'],
                        "message" => "Error inserting record",
                        "error_info" => $insertStmt->errorInfo()
                    ];
                }
            } else { 
                // Record exists, update the delay
                $updateStmt = $pdo->prepare("
                    UPDATE stationtable 
                    SET delay = ?
                    WHERE departure_time_stamp = ? AND platform = ?
                ");

                // Execute the update
                if ($updateStmt->execute([$train['Delay'], $departureTimestamp, $platform])) {
                    $response[] = [
                        "status" => "updated",
                        "train_to" => $train['Train to'],
                        "message" => "Record updated successfully",
                        "new_delay" => $train['Delay']
                    ];
                } else {
                    $response[] = [
                        "status" => "error",
                        "train_to" => $train['Train to'],
                        "message" => "Error updating delay",
                        "error_info" => $updateStmt->errorInfo()
                    ];
                }
            }
        } else {
            $response[] = [
                "status" => "error",
                "train_to" => $train['Train to'],
                "message" => "Invalid departure timestamp"
            ];
        }
    }
} catch (PDOException $e) {
    $response = [
        "status" => "error",
        "message" => "Connection to the database could not be established",
        "error_info" => $e->getMessage()
    ];
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>