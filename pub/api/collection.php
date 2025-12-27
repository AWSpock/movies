<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (isset($collection_id)) {
            $recs = [];
            foreach ($data->collections()->getRecordId($collection_id)->movies() as $rec) {
                array_push($recs, json_decode($rec->toString()));
            }
            echo json_encode($recs);
        } else {
            $recs = [];
            foreach ($data->collections()->getRecords() as $rec) {
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
