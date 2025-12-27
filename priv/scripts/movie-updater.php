<?php

$date = new DateTime();
echo "Run Date: " . $date->format('Y-m-d H:i:s') . "\n\n";

echo "Disabled";
exit;

include_once(__DIR__ . "/../../php/DataAccess/Database.php");
include_once(__DIR__ . "/../../php/DataAccess/API.php");

$db = new DatabaseV2();
$db2 = new DatabaseV2();
$db3 = new DatabaseV2();
$api = new API();

$sql = "
    SELECT a.`id`, a.`movie_id`, a.`tmdb_id`
    FROM movie_info a
    WHERE a.`title` IS NULL
";

$db->set_sql($sql);
$db->prepare();
$result = $db->execute();

if ($result) {
    $records = $result->fetch_all(MYSQLI_ASSOC);

    $sql = "
        UPDATE movie_info
        SET title = ?,
            original_title = ?,
            overview = ?,
            poster_path = ?,
            backdrop_path = ?,
            release_date = ?
        WHERE id = ?
    ";
    $db->set_sql($sql);
    $db->prepare();

    $sql2 = "
        INSERT INTO genre (id,name)
        SELECT ? AS id, ? AS name
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1
            FROM genre
            WHERE id = ?
                AND name = ?
        )
    ";
    $db2->set_sql($sql2);
    $db2->prepare();

    $sql3 = "
        INSERT INTO movie_genre (movie_id,genre_id)
        SELECT ? AS movie_id, ? AS genre_id
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1
            FROM movie_genre
            WHERE movie_id = ?
                AND genre_id = ?
        )
    ";
    $db3->set_sql($sql3);
    $db3->prepare();

    foreach ($records as $record) {
        echo "Movie ID: " . $record['movie_id'] . " [" . $record['tmdb_id'] . "]\n";

        $details = $api->get_movie($record['tmdb_id']);
        echo json_encode($details, JSON_PRETTY_PRINT) . "\n";

        $db->beginTransaction();
        $db2->beginTransaction();
        $db3->beginTransaction();

        $res = $db->execute([
            $details->title,
            $details->original_title,
            $details->overview,
            $details->poster_path,
            $details->backdrop_path,
            $details->release_date,
            $record['id']
        ], "ssssssi");

        $success = true;

        if ($res) {
            echo "Updated!\n";

            foreach ($details->genres as $genre) {
                echo "Check Genre: " . $genre->name . " [" . $genre->id . "] -- ";
                $res1 = $db2->execute([
                    $genre->id,
                    $genre->name,
                    $genre->id,
                    $genre->name
                ], "isis");

                if ($res1) {
                    echo "Success\n";

                    echo "Map Genre to Movie: ";
                    $res2 = $db3->execute([
                        $record['movie_id'],
                        $genre->id,
                        $record['movie_id'],
                        $genre->id
                    ], "iiii");

                    if ($res2) {
                        echo "Success\n";
                    } else {
                        echo "Failed\n";
                        $success = false;
                    }
                } else {
                    echo "Failed\n";
                    $success = false;
                }
            }
        } else {
            echo "Failed to update..\n";
            $success = false;
        }

        if ($success) {
            $db3->commit();
            $db2->commit();
            $db->commit();
        } else {
            $db3->rollback();
            $db2->rollback();
            $db->rollback();
        }
    }
} else {
    echo "Failed to get data..\n";
}

// foreach ($shard1s as $shard1) {
//     echo "Shard1: " . basename($shard1) . "\n";

//     $shard2s = glob($shard1 . "/*", GLOB_ONLYDIR);

//     if ($shard2s) {
//         foreach ($shard2s as $shard2) {
//             echo "Shard2: " . basename($shard2) . "\n";

//             $files = array_filter(glob($shard2 . "/*"), 'is_file');

//             if ($files) {
//                 foreach ($files as $file) {
//                     echo "File: " . basename($file) . "\n";

//                     $result = $db->execute([
//                         basename($shard1),
//                         basename($shard2),
//                         "%/" . basename($file)
//                     ], "sss");

//                     $id = -1;

//                     if ($result) {
//                         $id = $result->fetch_array(MYSQLI_ASSOC)['id'];
//                     }

//                     if ($id > -1) {
//                         echo "Exists: " . $id;
//                     } else {
//                         echo "Nope.. ";
//                         if (unlink($file)) {
//                             echo "Removed!";
//                         } else {
//                             echo "Failed to remove..";
//                         }
//                     }
//                     echo "\n";
//                 }
//             } else {
//                 echo "No Files Found.. ";
//                 if (rmdir($shard2)) {
//                     echo "Removed!";
//                 } else {
//                     echo "Failed to remove..";
//                 }
//                 echo "\n";
//             }
//         }
//     } else {
//         echo "No Shard2 Found.. ";
//         if (rmdir($shard1)) {
//             echo "Removed!";
//         } else {
//             echo "Failed to remove..";
//         }
//         echo "\n";
//     }
//     echo "\n";
// }
