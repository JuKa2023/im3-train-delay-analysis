<?php
// API endpoint with query parameter for station name
$url = "http://transport.opendata.ch/v1/stationboard?station=Bern";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);       // Set the URL to fetch
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Return the data instead of outputting it

// Execute cURL and get the response
$data = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Decode the JSON response
$jsonData = json_decode($data, true);

// Prepare an array to hold the extracted train details
$trainDetails = [];

// Loop through each train connection
foreach ($jsonData['stationboard'] as $train) {
    // Extract details
    $departureTimestamp = $train['stop']['departureTimestamp'] ?? 'No departure timestamp';
    $departure = $train['stop']['departure'] ?? 'No departure time';
    $delay = $train['stop']['delay'] ?? 'No delay';
    $platform = $train['stop']['platform'] ?? 'Unknown platform';
    $departureStation = $jsonData['station']['name'];
    $toStation = $train['to'];

    // Store the extracted data in an array
    $trainDetails[] = [
        'Train to' => $toStation,
        'Departure Station' => $departureStation,
        'Departure Time Stamp' => $departureTimestamp,
        'Departure Time' => $departure,
        'Delay' => $delay,
        'Platform' => $platform,
    ];
}

// Display the train details using print_r()
//print_r($trainDetails);

return $trainDetails;

?>