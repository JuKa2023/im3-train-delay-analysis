<?php
// API endpoint
$url = "http://transport.opendata.ch/v1/stationboard?station=Bern";

// Fetch the data
$data = file_get_contents($url);

// Output the raw data
print_r($data);
?>