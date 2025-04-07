<?php
require_once 'util.php';
include 'config.php'; 

function fetchCurrentWeatherData($latitude, $longitude) {
    $url = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&current=temperature_2m,rain,showers,snowfall,weather_code,wind_speed_10m,wind_gusts_10m";
    return fetchData($url);
}

// Hard code Bern as the location
$location = 'Bern'; 
$latitude = 46.948832;
$longitude = 7.439136;

$weather_data = fetchCurrentWeatherData($latitude, $longitude);
$weather_data['location'] = $location;

return $weather_data;
?>