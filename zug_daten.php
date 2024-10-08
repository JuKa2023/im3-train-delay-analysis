<?php

require_once 'db_connection.php';

// Check the connection
if (!$pdo) {
    die("Connection failed.");
}

// Define the routes to fetch data for
$routes = [
    ['from' => 'Zurich', 'to' => 'Bern'],
    ['from' => 'Geneva', 'to' => 'Bern'],
    ['from' => 'Lugano', 'to' => 'Bern'],
    ['from' => 'Visp', 'to' => 'Bern']
];

// Initialize the start date and end date
$date_from = new DateTime('2024-01-01');
$date_to = new DateTime(); // Current date

// Loop through each day until the current date is reached
while ($date_from <= $date_to) {
    // Loop through each route and fetch data from the API
    foreach ($routes as $route) {
        $apiUrl = "https://transport.opendata.ch/v1/connections?from=" . urlencode($route['from']) . "&to=" . urlencode($route['to']) . "&date=" . $date_from->format('Y-m-d') . "&limit=10";

        // Fetch data from the API for each route
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        // Process the data (for example, print the data or save it to the database)
        if (!empty($data['connections'])) {
            foreach ($data['connections'] as $connection) {
                // Here you can handle each connection as needed
                print_r($connection); // For demonstration purposes
            }
        }
    }

    // Output the current date being processed
    echo "Data fetched for date: " . $date_from->format('Y-m-d') . "<br>";

    // Increment the date by one day
    $date_from->modify('+1 day');
}





// Loop through the API data and insert into the database
/*foreach ($data['connections'] as $connection) {
    $fromStation = $connection['from']['station'];
    $toStation = $connection['to']['station'];
    $departure = $connection['from']['departure'];
    $arrival = $connection['to']['arrival'];
    $trainNumber = $connection['sections'][0]['journey']['name'];

    // Calculate delay in minutes
    $plannedDeparture = strtotime($departure);
    $actualDeparture = strtotime($arrival);
    $delayMinutes = ($actualDeparture - $plannedDeparture) / 60;

     // Insert station data into `bahnhofstabelle` for the departure station
    insertStation($pdo, $fromStation['id'], $fromStation['name'], $fromStation['location']['city']);

    // Insert train delay data into `zugverspaetung`
    insertTrainDelay($pdo, $fromStation['id'], $trainNumber, date('Y-m-d', $plannedDeparture), $departure, $arrival, $delayMinutes);

/ Function to insert station data into the `bahnhofstabelle`
function insertStation($pdo, $stationId, $stationName, $location) {
$sql = "INSERT INTO bahnhofstabelle (bahnhof_id, bahnhof_name, ort) VALUES (:stationId, :stationName, :location)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':stationId' => $stationId,
    ':stationName' => $stationName,
    ':location' => $location,
]);
}

// Function to insert train delay data into the `zugverspaetung` table
function insertTrainDelay($pdo, $stationId, $trainNumber, $date, $plannedDeparture, $actualDeparture, $delayMinutes) {
$sql = "INSERT INTO zugverspaetung (bahnhof_id, zugnummer, datum, plan_abfahrt, ist_abfahrt, verspaetung_minuten) VALUES (:stationId, :trainNumber, :date, :plannedDeparture, :actualDeparture, :delayMinutes)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':stationId' => $stationId,
    ':trainNumber' => $trainNumber,
    ':date' => $date,
    ':plannedDeparture' => $plannedDeparture,
    ':actualDeparture' => $actualDeparture,
    ':delayMinutes' => $delayMinutes,
]);
}*/