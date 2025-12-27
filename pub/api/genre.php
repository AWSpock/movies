<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (isset($genre_id)) {
            // echo $data->genres()->getRecordId($genre_id)->toString();
            $recs = [];
            foreach ($data->genres()->getRecordId($genre_id)->movies() as $rec) {
                array_push($recs, json_decode($rec->toString()));
            }
            echo json_encode($recs);
        } else {
            $recs = [];
            foreach ($data->genres()->getRecords() as $rec) {
                array_push($recs, json_decode($rec->toString()));
            }
            echo json_encode($recs);
        }
        break;
    case "POST":
        break;
    default:
        echo "Unknown Method";
        http_response_code(405);
        break;
}
