<?php
require_once 'util.php';

$url = "http://transport.opendata.ch/v1/stationboard?station=Bern";
$jsonData = fetchData($url);

$trainDetails = [];
// Loop through each train connection and extract the details
foreach ($jsonData['stationboard'] as $train) {
    $departureTimestamp = $train['stop']['departureTimestamp'] ?? 'No departure timestamp';
    $departure = $train['stop']['departure'] ?? 'No departure time';
    $delay = $train['stop']['delay'] ?? 'No delay';
    $platform = $train['stop']['platform'] ?? 'Unknown platform';
    $departureStation = $jsonData['station']['name'];
    $toStation = $train['to'];

    $trainDetails[] = [
        'Train to' => $toStation,
        'Departure Station' => $departureStation,
        'Departure Time Stamp' => $departureTimestamp,
        'Departure Time' => $departure,
        'Delay' => $delay,
        'Platform' => $platform,
    ];
}

return $trainDetails;
?>
