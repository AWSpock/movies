<?php

$movie_FileData = $data->movie_files($userAuth->user()->id());

$recMovie_File = $movie_FileData->getRecordById($movie_file_id);
if ($recMovie_File->id() < 0) {
    header('Location: /');
    die();
}

$movies = [];

//

if (!empty($_POST)) {
    $recMovie_File = Movie_File::fromPost($_POST);
    $data->beginTransaction();
    $res = $movie_FileData->updateRecord($recMovie_File);
    $_SESSION['last_message_text'] = $movie_FileData->actionDataMessage;
    if ($res == 1 || $res == 2) {
        $data->commit();
        $_SESSION['last_message_type'] = "success";
        header('Location: /movie-file');
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $movies = $data->movies()->getRecords();
}
