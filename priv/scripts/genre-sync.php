<?php

$date = new DateTime();
echo "Run Date: " . $date->format('Y-m-d H:i:s') . "\n\n";

include_once(__DIR__ . "/../../php/DataAccess/DataAccess.php");
include_once(__DIR__ . "/../../php/DataAccess/Database.php");
include_once(__DIR__ . "/../../php/DataAccess/API.php");

$data = new DataAccess();
$api = new API();

$genreData = $data->genres();
$genres = $api->genres();

foreach ($genres as $genre) {
    echo "Genre: " . json_encode($genre) . "\n";

    $g = $genreData->getRecordById($genre->id);
    if ($g->id() < 0) {
        echo "Add.. ";
        $g = new Genre(json_decode(json_encode($genre), true));
        $genreData->insertRecord($g);
        echo "Done!\n";
    } else {
        echo "Exists\n";
    }
}