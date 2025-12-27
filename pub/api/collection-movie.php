<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (isset($collection_id)) {
            $recs = [];
            foreach ($data->movies()->getMoviesForCollection($collection_id) as $rec) {
                array_push($recs, json_decode($rec->toString()));
            }
            echo json_encode($recs);
        } else {
            echo "Missing Collection ID";
            http_response_code(400);
        }
        break;
    case "POST":
        break;
    default:
        echo "Unknown Method";
        http_response_code(405);
        break;
}
