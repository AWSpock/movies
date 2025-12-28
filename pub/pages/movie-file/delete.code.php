<?php

$movie_FileData = $data->movie_files();

$recMovie_File = $movie_FileData->getRecordById($movie_file_id);
if ($recMovie_File->id() < 0) {
    header('Location: /movie-file');
    die();
}

$recMovie = $data->movies()->getRecordById($recMovie_File->movie_id());

//

if (!empty($_POST)) {
    $res = $movie_FileData->deleteRecord($recMovie_File);
    $_SESSION['last_message_text'] = $movie_FileData->actionDataMessage;
    if ($res == 1) {
        $_SESSION['last_message_type'] = "success";
        header('Location: /movie-file');
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
}
