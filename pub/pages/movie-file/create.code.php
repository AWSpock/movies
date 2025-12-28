<?php

exit;

/*$recMovie_File = new Movie_File();

$movies = [];

// 

if (!empty($_POST)) {
    $data = new DataAccess();
    $movie_FileData = $data->movie_files();

    $recMovie_File = Movie_File::fromPost($_POST);
    $data->beginTransaction();
    $movie_file_id = $movie_FileData->insertRecord($recMovie_File);
    $_SESSION['last_message_text'] = $movie_FileData->actionDataMessage;
    if ($movie_file_id > 0) {
        $data->commit();
        $_SESSION['last_message_type'] = "success";
        header('Location: /movie-file/' . $movie_file_id);
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $movies = $data->movies()->getRecords();
}
*/