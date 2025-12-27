<?php

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (isset($movie_id)) {
            $movie = $data->movies()->getRecordById($movie_id);

            $file_dir = $data->getDb()->file_dir();
            $full_path = $file_dir . $movie->poster_shard1() . "/" . $movie->poster_shard2() . "/" . $movie->poster_path();

            if (file_exists($full_path)) {
                $lastModified = filemtime($full_path);
                header('Content-Type: ' . $movie->poster_file_type());
                header('Last-Modified: ' . $lastModified);
                header('Content-Length: ' . filesize($full_path));
                header('Content-Disposition: inline; filename="' . $movie->title() . '"');
                header('Content-Transfer-Encoding: binary');

                header('Cache-Control: public, max-age=31536000'); // Cache for a long time (e.g., 1 year)
                header('Expires: ' . gmdate("D, d M Y H:i:s T", time() + 31536000)); // Also set Expires header

                $gmdate = gmdate("D, d M Y H:i:s T", $lastModified);

                if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastModified) {
                    header("HTTP/1.1 304 Not Modified");
                    exit; // Stop script execution, browser uses its cache
                }

                echo file_get_contents($full_path);
            } else {
                echo "File Does Not Exist";
                http_response_code(404);
            }
        } else {
            echo "Missing Movie ID";
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
