<?php

$movie_FileData = $data->movie_files();

$recMovie_File = $movie_FileData->getRecordById($movie_id);
if ($recMovie_File->id() < 0) {
    header('Location: /');
    die();
}

if (!empty($_POST)) {
    $id = $_POST['selection'];

    $recMovie_File->setMovieID($id);

    $data->beginTransaction();

    $res = $movie_FileData->updateRecord($recMovie_File);
    $_SESSION['last_message_text'] = $movie_FileData->actionDataMessage;
    if ($res > 0) {
        $_SESSION['last_message_type'] = "success";

        $data->commit();
        header('Location: /movie-map');
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $search = $data->movies()->getRecords();
}
