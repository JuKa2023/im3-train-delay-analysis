<?php

$url = "https://transport.opendata.ch/v1/locations?query=Basel";

// Initialisiert eine cURL-Sitzung
$ch = curl_init($url);

// Setzt Optionen
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Führt die cURL-Sitzung aus und erhält den Inhalt
$response = curl_exec($ch);

// Schließt die cURL-Sitzung
curl_close($ch);



// Zeigt die JSON-Antwort an
print_r($response[0]);

?>