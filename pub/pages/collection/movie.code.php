<?php

$collectionData = $data->collections();

$recCollection = $collectionData->getRecordById($collection_id);
if ($recCollection->id() < 0) {
    header('Location: /');
    die();
}

// $recCollection->addMovies($data->movies()->getMoviesForCollection($collection_id));
