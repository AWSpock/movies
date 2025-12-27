<?php

require_once(__DIR__ . "/../../models/Movie.php");

class MovieRepository
{
    private $db;

    private $records = [];
    private $recordsGenre = [];
    private $recordsCollection = [];
    private $loaded = false;

    public $actionDataMessage;

    public function __construct(DatabaseV2 $db)
    {
        $this->db = $db;
    }

    public function getRecordById($id)
    {
        if (!array_key_exists($id, $this->records)) {
            $sql = "
                SELECT a.`id`, a.`created`, a.`updated`, a.`title`, a.`order_title`, a.`overview`, a.`release_date`, a.`poster_path`, a.`poster_shard1`, a.`poster_shard2`, a.`poster_file_type`, a.`backdrop_path`
                FROM movie a
                WHERE a.`id` = ? 
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->records[$id] = Movie::fromDatabase($result->fetch_array(MYSQLI_ASSOC));
            } else {
                $this->records[$id] = null;
            }
        }
        return $this->records[$id];
    }

    public function getRecords()
    {
        if ($this->loaded) {
            $recs = [];
            foreach ($this->records as $key => $rec) {
                if ($rec->id() > 0)
                    $recs[$key] = $rec;
            }
            return $recs;
        }

        $sql = "
            SELECT a.`id`, a.`created`, a.`updated`, a.`title`, a.`order_title`, a.`overview`, a.`release_date`, a.`poster_path`, a.`poster_shard1`, a.`poster_shard2`, a.`poster_file_type`, a.`backdrop_path`
            FROM movie a
            WHERE `id` IN (
                SELECT movie_id
                FROM movie_file
                WHERE movie_id IS NOT NULL
            )
            ORDER BY a.`order_title`, a.`release_date`
        ";

        $result = $this->db->query($sql);

        $this->loaded = true;
        $this->records = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->records[$rec['id']] = Movie::fromDatabase($rec);
        }
        return $this->records;
    }

    public function getMoviesForGenre($id)
    {
        if (!array_key_exists($id, $this->recordsGenre)) {
            $sql = "
                SELECT a.`id`, a.`created`, a.`updated`, a.`title`, a.`order_title`, a.`overview`, a.`release_date`, a.`poster_path`, a.`poster_shard1`, a.`poster_shard2`, a.`poster_file_type`, a.`backdrop_path`
                FROM movie a
                WHERE a.`id` IN (
                    SELECT `movie_id`
                    FROM movie_genre
                    WHERE `genre_id` = ?
                )
                ORDER BY a.`order_title`, a.`release_date`
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->recordsGenre[$id] = [];
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
                    $this->recordsGenre[$id][$rec['id']] = Movie::fromDatabase($rec);
                }
            } else {
                $this->recordsGenre[$id] = null;
            }
        }
        return $this->recordsGenre[$id];
    }

    public function getMoviesForCollection($id)
    {
        if (!array_key_exists($id, $this->recordsCollection)) {
            $sql = "
                SELECT a.`id`, a.`created`, a.`updated`, a.`title`, a.`order_title`, a.`overview`, a.`release_date`, a.`poster_path`, a.`poster_shard1`, a.`poster_shard2`, a.`poster_file_type`, a.`backdrop_path`
                FROM movie a
                WHERE a.`id` IN (
                    SELECT `movie_id`
                    FROM movie_collection
                    WHERE `collection_id` = ?
                )
                ORDER BY a.`order_title`, a.`release_date`
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->recordsCollection[$id] = [];
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
                    $this->recordsCollection[$id][$rec['id']] = Movie::fromDatabase($rec);
                }
            } else {
                $this->recordsCollection[$id] = null;
            }
        }
        return $this->recordsCollection[$id];
    }

    public function insertRecord(Movie $rec)
    {
        $this->actionDataMessage = "Failed to insert Movie";

        if (empty($rec->title())) {
            $this->actionDataMessage = "Title is required to insert Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie (`title`,`order_title`,`overview`,`release_date`,`poster_path`,`poster_shard1`,`poster_shard2`,`poster_file_type`,`backdrop_path`)
            VALUES (?,?,?,?,?,?,?,?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->title(),
            $rec->title(),
            $rec->overview(),
            $rec->release_date(),
            $rec->poster_path(),
            $rec->poster_shard1(),
            $rec->poster_shard2(),
            $rec->poster_file_type(),
            $rec->backdrop_path(),
        ], "sssssssss");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Movie Inserted";
            // $this->db->commit();
            return $result;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapGenre(Movie $rec, Genre $genre)
    {
        $this->actionDataMessage = "Failed to map Movie to Genre";

        if ($rec->id() < 0 || $genre->id() < 0) {
            $this->actionDataMessage = "Missing IDs to map Movie to Genre";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_genre (`movie_id`,`genre_id`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->id(),
            $genre->id()
        ], "ii");

        if ($result) {
            $this->actionDataMessage = "Movie mapped to Genre";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapTMDB(Movie $rec, $tmdb_id = -1)
    {
        $this->actionDataMessage = "Failed to map Movie to Genre";

        if ($rec->id() < 0 || $tmdb_id < 0) {
            $this->actionDataMessage = "Missing IDs to map Movie to TMDB";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_tmdb (`movie_id`,`tmdb_id`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->id(),
            $tmdb_id
        ], "ii");

        if ($result) {
            $this->actionDataMessage = "Movie mapped to TMDB";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function updateRecord(Movie $rec)
    {
        $this->actionDataMessage = "Failed to update Movie";

        if (empty($rec->title())) {
            $this->actionDataMessage = "Title is required to update Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            UPDATE movie 
            SET `title` = ?,
                `order_title` = ?,
                `overview` = ?,
                `release_date` = ?,
                `poster_path` = ?,
                `poster_shard1` = ?,
                `poster_shard2` = ?
            WHERE `id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->title(),
            $rec->order_title(),
            $rec->overview(),
            $rec->release_date(),
            $rec->poster_path(),
            $rec->poster_shard1(),
            $rec->poster_shard2(),
            $rec->id()
        ], "sssssssi");

        if ($result !== false) {
            if ($result !== 1) {
                $this->actionDataMessage = "Movie Unchanged";
                return 2;
            }
            $this->actionDataMessage = "Movie Updated";
            // $this->db->commit();
            return 1;
        }

        // $this->db->rollback();
        return false;
    }

    // public function deleteRecord(Address $rec)
    // {
    //     $this->actionDataMessage = "Failed to delete Address";

    //     $this->db->beginTransaction();

    //     $sql = "
    //         DELETE a, b, c, d, e
    //         FROM address a
    //             LEFT OUTER JOIN bill_type b ON a.`id` = b.`address_id`
    //             LEFT OUTER JOIN address_favorite c ON a.`id` = c.`address_id`
    //             LEFT OUTER JOIN address_share d ON a.`id` = d.`address_id`
    //             LEFT OUTER JOIN bill e ON a.`id` = e.`address_id`
    //         WHERE a.`id` = ? 
    //         AND a.`userid` = ?
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->id(),
    //         $this->userid
    //     ], "ii");

    //     if (is_int($result) && $result > 0) {
    //         $this->actionDataMessage = "Address Deleted";
    //         $this->db->commit();
    //         return 1;
    //     }
    //     $this->db->rollback();
    //     return 0;
    // }

    //

    // public function setFavorite($id)
    // {
    //     $this->actionDataMessage = "Failed to Add Favorite Address";

    //     $this->db->beginTransaction();

    //     $sql = "
    //         INSERT INTO address_favorite (`address_id`, `userid`)
    //         VALUES (?,?)
    //     ";

    //     $result = $this->db->query($sql, [
    //         $id,
    //         $this->userid
    //     ], "ii");

    //     if ($result === true) {
    //         $this->actionDataMessage = "Added Favorite Address";
    //         $this->db->commit();
    //         return true;
    //     }

    //     $this->db->rollback();
    //     return false;
    // }

    // public function removeFavorite($id)
    // {
    //     $this->actionDataMessage = "Failed to Remove Favorite Address";

    //     $this->db->beginTransaction();

    //     $sql = "
    //         DELETE FROM address_favorite
    //         WHERE `address_id` = ? 
    //         AND `userid` = ?
    //     ";

    //     $result = $this->db->query($sql, [
    //         $id,
    //         $this->userid
    //     ], "ii");

    //     if (is_int($result) && $result > 0) {
    //         $this->actionDataMessage = "Removed Favorite Address";
    //         $this->db->commit();
    //         return true;
    //     }

    //     $this->db->rollback();
    //     return false;
    // }
}
