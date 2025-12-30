<?php

if (!$data->user_roles($userAuth->user()->id())->hasRole("manager")) {
    header('Location: /unauthorized?message=' . urlencode("Not a manager."));
    die();
}

$recCollection = new Collection();

$movies = [];

// 

if (!empty($_POST)) {
    $data = new DataAccess();
    $collectionData = $data->collections();

    $recCollection = Collection::fromPost($_POST);
    $data->beginTransaction();
    $collection_id = $collectionData->insertRecord($recCollection);
    $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
    if ($collection_id > 0) {
        $success = true;
        $recCollection->set_id($collection_id);
        if (isset($_POST['collection_movie'])) {
            foreach ($_POST['collection_movie'] as $movie) {
                if ($collectionData->mapMovie($recCollection, $movie) !== 1) {
                    $success = false;
                    $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
                    break;
                }
            }
        }

        if ($success) {
            $data->commit();
            $_SESSION['last_message_type'] = "success";
            header('Location: /collection/' . $collection_id);
            die();
        }
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $movies = $data->movies()->getRecords();
}

function isMovieChecked($movies = [], $id)
{
    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            if ($movie->id() == $id)
                return true;
        }
    }
    return false;
}
