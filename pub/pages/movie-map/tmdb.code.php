<?php

$movie_FileData = $data->movie_files();

$recMovie_File = $movie_FileData->getRecordById($movie_id);
if ($recMovie_File->id() < 0) {
    header('Location: /');
    die();
}

$details = null;
$movie = new Movie();

if (!empty($_POST)) {
    $tmdb_id = $_POST['selection'];

    $details = $api->get_movie($tmdb_id);

    $poster = $api->download_poster($details);

    if ($poster === false) {
        $_SESSION['last_message_text'] = $api->actionDataMessage;
        $_SESSION['last_message_type'] = "danger";
    } else {
        $movie = Movie::fromTMDB($details, $poster);

        $movieData = $data->movies();

        $data->beginTransaction();

        $res = $movieData->insertRecord($movie);
        $_SESSION['last_message_text'] = $movieData->actionDataMessage;
        if ($res > 0) {
            $_SESSION['last_message_type'] = "success";

            $movie->set_id($res);

            $res1 = $movieData->mapTMDB($movie, $tmdb_id);
            if ($res1 !== 1) {
                $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                $_SESSION['last_message_type'] = "danger";
            } else {
                $recMovie_File->setMovieID($res);
                $res2 = $movie_FileData->updateRecord($recMovie_File);
                if ($res2 !== 1) {
                    $_SESSION['last_message_text'] = $movie_FileData->actionDataMessage;
                    $_SESSION['last_message_type'] = "danger";
                } else {
                    $success = true;
                    foreach ($details->genres as $genre) {
                        $g = Genre::fromTMDB($genre);
                        $res3 = $movieData->mapGenre($movie, $g->id());
                        if ($res3 !== 1) {
                            $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                            $_SESSION['last_message_type'] = "danger";
                            $success = false;
                        }
                    }
                    if ($success) {
                        $data->commit();
                        header('Location: /movie/' . $movie->id() . "/summary");
                        die();
                    }
                }
            }
        } else {
            $_SESSION['last_message_type'] = "danger";
        }
        $data->rollback();
    }
} else {
    $search = $api->movie_search($recMovie_File->title(), $recMovie_File->year());
}
