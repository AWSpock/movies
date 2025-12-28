<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (isset($movie_file_id)) {
            echo $data->movie_files()->getRecordById($movie_file_id)->toString();
        } else {
            $recs = [];
            foreach ($data->movie_files()->getRecords() as $rec) {
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
