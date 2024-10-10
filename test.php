I got this code php code:

<?php

require_once 'db_connection.php';



// Check the connection
if (!$pdo) {
    die("Connection failed.");
}

// Define the routes to fetch data for
$routes = [
    ['from' => 'Zurich', 'to' => 'Bern'],
];

// Loop through each route and fetch data from the API
foreach ($routes as $route) {
    $apiUrl = "https://transport.opendata.ch/v1/connections?from=" . urlencode($route['from']) . "&to=" . urlencode($route['to']) . "&date=2024-01-01&limit=10";

    // echo $apiUrl . "<br>";

    // Fetch data from the API for each route
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    echo '<pre>' . json_encode($data, JSON_PRETTY_PRINT) . '</pre>';

}