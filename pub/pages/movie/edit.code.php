<?php

if (!$data->user_roles($userAuth->user()->id())->hasRole("manager")) {
    header('Location: /unauthorized?message=' . urlencode("Not a manager."));
    die();
}

$movieData = $data->movies();

$recMovie = $movieData->getRecordById($movie_id);
if ($recMovie->id() < 0) {
    header('Location: /');
    die();
}

$collections = [];
$genres = [];

//

if (!empty($_POST)) {
    $recMovie1 = Movie::fromPost($_POST);
    $recMovie->set_order_title($recMovie1->order_title());
    $data->beginTransaction();
    $res = $movieData->updateRecord($recMovie);
    $_SESSION['last_message_text'] = $movieData->actionDataMessage;
    if ($res == 1 || $res == 2) {
        $success = true;
        $addedCollection = false;
        $addedGenre = false;

        // collections
        $collections = [];
        if (isset($_POST['movie_collection']))
            $collections = $_POST['movie_collection'];
        $recMovie->addCollections($data->collections()->getCollectionsForMovie($movie_id));
        foreach ($collections as $collection) {
            $add = true;
            foreach ($recMovie->collections() as $c) {
                if ($c->id() == $collection) {
                    $add = false;
                    break;
                }
            }
            if ($add) {
                $addedCollection = true;
                if ($movieData->mapCollection($recMovie, $collection) !== 1) {
                    $success = false;
                    $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                    break;
                }
            }
        }
        if ($addedCollection) {
            $_SESSION['last_message_text'] .= " + Added Collections";
        }
        if ($success) {
            foreach ($recMovie->collections() as $c) {
                $extra = true;
                foreach ($collections as $collection) {
                    if ($c->id() == $collection) {
                        $extra = false;
                        break;
                    }
                }
                if ($extra) {
                    if ($movieData->unmapCollections($recMovie, $collections) !== 1) {
                        $success = false;
                        $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                    } else {
                        $_SESSION['last_message_text'] .= " + Removed Collections";
                    }
                    break;
                }
            }
        }

        // genres
        $genres = [];
        if (isset($_POST['movie_genre']))
            $genres = $_POST['movie_genre'];
        $recMovie->addGenres($data->genres()->getGenresForMovie($movie_id));
        foreach ($genres as $genre) {
            $add = true;
            foreach ($recMovie->genres() as $g) {
                if ($g->id() == $genre) {
                    $add = false;
                    break;
                }
            }
            if ($add) {
                $addedGenre = true;
                if ($movieData->mapGenre($recMovie, $genre) !== 1) {
                    $success = false;
                    $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                    break;
                }
            }
        }
        if ($addedGenre) {
            $_SESSION['last_message_text'] .= " + Added Genres";
        }
        if ($success) {
            foreach ($recMovie->genres() as $g) {
                $extra = true;
                foreach ($genres as $genre) {
                    if ($g->id() == $genre) {
                        $extra = false;
                        break;
                    }
                }
                if ($extra) {
                    if ($movieData->unmapGenres($recMovie, $genres) !== 1) {
                        $success = false;
                        $_SESSION['last_message_text'] = $movieData->actionDataMessage;
                    } else {
                        $_SESSION['last_message_text'] .= " + Removed Genres";
                    }
                    break;
                }
            }
        }

        if ($success) {
            $data->commit();
            $_SESSION['last_message_type'] = "success";
            header('Location: /movie/' . $recMovie->id() . '/summary');
            die();
        }
    } else {
        $_SESSION['last_message_type'] = "danger";
    }
    $data->rollback();
} else {
    $collections = $data->collections()->getRecords();
    $genres = $data->genres()->getRecords();
    $recMovie->addCollections($data->collections()->getCollectionsForMovie($movie_id));
    $recMovie->addGenres($data->genres()->getGenresForMovie($movie_id));
}

function isCollectionChecked($collections = [], $id)
{
    foreach ($collections as $collection) {
        if ($collection instanceof Collection) {
            if ($collection->id() == $id)
                return true;
        }
    }
    return false;
}

function isGenreChecked($genres = [], $id)
{
    foreach ($genres as $genre) {
        if ($genre instanceof Genre) {
            if ($genre->id() == $id)
                return true;
        }
    }
    return false;
}
