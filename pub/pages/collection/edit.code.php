<?php

$collectionData = $data->collections($userAuth->user()->id());

$recCollection = $collectionData->getRecordById($collection_id);
if ($recCollection->id() < 0) {
    header('Location: /');
    die();
}

$movies = [];

//

if (!empty($_POST)) {
    $recCollection = Collection::fromPost($_POST);
    $data->beginTransaction();
    $res = $collectionData->updateRecord($recCollection);
    $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
    if ($res == 1 || $res == 2) {
        $success = true;
        $added = false;

        $recCollection->addMovies($data->movies()->getMoviesForCollection($collection_id));
        foreach ($_POST['collection_movie'] as $movie) {
            $add = true;
            foreach ($recCollection->movies() as $m) {
                if ($m->id() == $movie) {
                    $add = false;
                    break;
                }
            }
            if ($add) {
                $added = true;
                if ($collectionData->mapMovie($movie, $recCollection) !== 1) {
                    $success = false;
                    $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
                    break;
                }
            }
        }
        if ($added) {
            $_SESSION['last_message_text'] .= " + Added Movies";
        }
        if ($success) {
            foreach ($recCollection->movies() as $m) {
                $extra = true;
                foreach ($_POST['collection_movie'] as $movie) {
                    if ($m->id() == $movie) {
                        $extra = false;
                        break;
                    }
                }
                if ($extra) {
                    if ($collectionData->unmapMovies($_POST['collection_movie'], $recCollection) !== 1) {
                        $success = false;
                        $_SESSION['last_message_text'] = $collectionData->actionDataMessage;
                    } else {
                        $_SESSION['last_message_text'] .= " + Removed Movies";
                    }
                    break;
                }
            }
        }

        if ($success) {
            $data->commit();
            $_SESSION['last_message_type'] = "success";
            header('Location: /collection/' . $recCollection->id());
            die();
        }
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $movies = $data->movies()->getRecords();
    $recCollection->addMovies($data->movies()->getMoviesForCollection($collection_id));
}

function isChecked($movies = [], $id)
{
    foreach ($movies as $movie) {
        if ($movie instanceof Movie) {
            if ($movie->id() == $id)
                return true;
        }
    }
    return false;
}
