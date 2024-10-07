<?php

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the routes to fetch data for
$routes = [
    ['from' => 'Zurich', 'to' => 'Bern'],
    ['from' => 'Geneva', 'to' => 'Lausanne'],
    ['from' => 'Basel', 'to' => 'Lucerne'],
    ['from' => 'St. Gallen', 'to' => 'Winterthur']
];

// Function to insert station data into the `bahnhofstabelle`
/*function insertStation($conn, $stationId, $stationName, $location) {
    $sql = "INSERT INTO bahnhofstabelle (bahnhof_id, bahnhof_name, ort) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $stationId, $stationName, $location);
    $stmt->execute();
    $stmt->close();
}

// Function to insert train delay data into the `zugverspaetung` table
function insertTrainDelay($conn, $stationId, $trainNumber, $date, $plannedDeparture, $actualDeparture, $delayMinutes) {
    $sql = "INSERT INTO zugverspaetung (bahnhof_id, zugnummer, datum, plan_abfahrt, ist_abfahrt, verspaetung_minuten) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $stationId, $trainNumber, $date, $plannedDeparture, $actualDeparture, $delayMinutes);
    $stmt->execute();
    $stmt->close();
}*/

// Loop through each route and fetch data from the API
foreach ($routes as $route) {
    $apiUrl = "https://transport.opendata.ch/v1/connections?from=" . urlencode($route['from']) . "&to=" . urlencode($route['to']) . "&date=2023-01-01&limit=10";

    // Fetch data from the API for each route
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    // Loop through the API data and insert into the database
    foreach ($data['connections'] as $connection) {
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
        insertStation($conn, $fromStation['id'], $fromStation['name'], $fromStation['location']['city']);

        // Insert train delay data into `zugverspaetung`
        insertTrainDelay($conn, $fromStation['id'], $trainNumber, date('Y-m-d', $plannedDeparture), $departure, $arrival, $delayMinutes);
    }
}

// Close the database connection
$conn->close();

echo "Data for all routes has been successfully inserted into the tables.";
?>