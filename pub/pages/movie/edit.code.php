<?php

$movieData = $data->movies();

$recMovie = $movieData->getRecordById($movie_id);
if ($recMovie->id() < 0) {
    header('Location: /');
    die();
}

//

if (!empty($_POST)) {
    $recMovie1 = Movie::fromPost($_POST);
    $recMovie->set_order_title($recMovie1->order_title());
    $data->beginTransaction();
    $res = $movieData->updateRecord($recMovie);
    $_SESSION['last_message_text'] = $movieData->actionDataMessage;
    if ($res == 1 || $res == 2) {
        $data->commit();
        $_SESSION['last_message_type'] = "success";
        header('Location: /movie/' . $recMovie->id() . '/summary');
        die();
    } else {
        $data->rollback();
        $_SESSION['last_message_type'] = "danger";
    }
}
