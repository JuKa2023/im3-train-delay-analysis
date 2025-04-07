<?php

// Function to fetch data from a given URL using cURL
// Returns the decoded JSON response
function fetchData($url) {
    $ch = curl_init();
    if ($ch === false) {
        throw new Exception("Failed to initialize cURL");
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL request failed: " . $error);
    }

    curl_close($ch);

    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Failed to decode JSON: " . json_last_error_msg());
    }

    return $decodedResponse;
}