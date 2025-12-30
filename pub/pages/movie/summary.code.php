<?php

$movieData = $data->movies();

$recMovie = $movieData->getRecordById($movie_id);
if ($recMovie->id() < 0) {
    header('Location: /');
    die();
}

if (!empty($_POST['movie_view_count'])) {
    $movie_ViewData = $data->movie_views($userAuth->user()->id());
    $res = $movie_ViewData->log_user_view($recMovie->id());
    $_SESSION['last_message_text'] = $movie_ViewData->actionDataMessage;
    if ($res > 0) {
        $_SESSION['last_message_type'] = "success";
        header('Location: /movie/' . $recMovie->id() . '/summary');
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
}

$recMovie->addGenres($data->genres()->getGenresForMovie($recMovie->id()));
$recMovie->addMovieFiles($data->movie_files()->getMovieFilesForMovie($recMovie->id()));
$recMovie->addCollections($data->collections()->getCollectionsForMovie($recMovie->id()));
$recMovie->addUserViewCount($data->movie_views($userAuth->user()->id())->getCountByMovieId($recMovie->id()));
