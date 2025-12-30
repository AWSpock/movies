<?php

// require_once(__DIR__ . "/../../models/Movie_Views.php");

class Movie_ViewsRepository
{
    private $db;
    private $userid;

    private $records = [];
    private $recordsMovie = [];
    private $loaded = false;

    public $actionDataMessage;


    public function __construct(DatabaseV2 $db, $userid)
    {
        $this->db = $db;
        $this->userid = $userid;
    }

    public function getCountByMovieId($movie_id)
    {
        if (!array_key_exists($movie_id, $this->records)) {
            $sql = "
                SELECT COUNT(a.`id`) AS n
                FROM movie_views a
                WHERE a.`userid` = ? 
                    AND a.`movie_id` = ?
            ";

            $result = $this->db->query($sql, [
                $this->userid,
                $movie_id,
            ], "ii");

            if ($result) {
                $this->records[$movie_id] = $result->fetch_array(MYSQLI_ASSOC)['n'];
            } else {
                $this->records[$movie_id] = null;
            }
        }
        return $this->records[$movie_id];
    }

    public function log_user_view($movie_id = -1)
    {
        $this->actionDataMessage = "Failed to log User View";

        if ($movie_id < 0) {
            $this->actionDataMessage = "Movie ID is required to log User View";
            return 0;
        }

        $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_views (`userid`, `movie_id`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $this->userid,
            $movie_id,
        ], "ii");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "User View logged";
            $this->db->commit();
            return $result;
        }
        $this->db->rollback();
        return 0;
    }

    // public function mapCollection(Movie $rec, $collection_id = -1)
    // {
    //     $this->actionDataMessage = "Failed to map Collection to Movie";

    //     if ($rec->id() < 0 || $collection_id < 0) {
    //         $this->actionDataMessage = "Missing IDs to map Collection to Movie";
    //         return 0;
    //     }

    //     // $this->db->beginTransaction();

    //     $sql = "
    //         INSERT INTO movie_collection (`movie_id`,`collection_id`)
    //         VALUES (?,?)
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->id(),
    //         $collection_id
    //     ], "ii");

    //     if ($result) {
    //         $this->actionDataMessage = "Collection mapped to Movie";
    //         // $this->db->commit();
    //         return 1;
    //     }
    //     // $this->db->rollback();
    //     return 0;
    // }
}
