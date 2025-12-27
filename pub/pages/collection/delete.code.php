<?php

$collectionData = $data->collections();

$recCollection = $collectionData->getRecordById($collection_id);
if ($recCollection->id() < 0) {
    header('Location: /collection');
    die();
}

$recCollection->addMovies($data->movies()->getMoviesForCollection($collection_id));

//

if (!empty($_POST)) {
    $res = $collectionData->deleteRecord($recCollection);
    $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
    if ($res == 1) {
        $_SESSION['last_message_type'] = "success";
        header('Location: /collection');
        die();
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
}
