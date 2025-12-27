<?php

$movieData = $data->movies();

$recMovie = $movieData->getRecordById($movie_id);
if ($recMovie->id() < 0) {
    header('Location: /');
    die();
}

$recMovie->addGenres($data->genres()->getGenresForMovie($movie_id));
$recMovie->addMovieFiles($data->movie_files()->getMovieFilesForMovie($movie_id));
$recMovie->addCollections($data->collections()->getCollectionsForMovie($movie_id));
