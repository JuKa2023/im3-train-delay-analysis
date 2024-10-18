<?php
require_once 'config.php';
$trainDetails = include('src/train_data_fetch.php');

$response = [];
try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // Process each train detail and insert it into the database if not exists
    foreach ($trainDetails as $train) {
        $departureTimestamp = $train['Departure Time Stamp'];
        $platform = $train['Platform'];

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

            if ($stmt->rowCount() == 0) {
                // Insert new record if it doesn't exist
                $insertStmt = $pdo->prepare("
                    INSERT INTO stationtable (destination, departure, departure_time_stamp, departure_time, delay, platform)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

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
                // Update existing record
                $updateStmt = $pdo->prepare("
                    UPDATE stationtable 
                    SET delay = ?
                    WHERE departure_time_stamp = ? AND platform = ?
                ");

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

header('Content-Type: application/json'); // To improve visualisation in browser
echo json_encode($response, JSON_PRETTY_PRINT);
?>