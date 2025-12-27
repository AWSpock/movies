<?php

$genreData = $data->genres();

$recGenre = $genreData->getRecordById($genre_id);
if ($recGenre->id() < 0) {
    header('Location: /');
    die();
}

// $recGenre->addMovies($data->movies()->getMoviesForGenre($genre_id));
