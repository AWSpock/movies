<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // if (isset($movie_id)) {
        $recs = [];
        foreach ($data->movies()->getMoviesRecentlyViewedByUser($userAuth->user()->id()) as $rec) {
            array_push($recs, json_decode($rec->toString()));
        }
        echo json_encode($recs);
        // } else {
        //     echo "Missing Movie ID";
        //     http_response_code(400);
        // }
        break;
    case "POST":
        break;
    default:
        echo "Unknown Method";
        http_response_code(405);
        break;
}
