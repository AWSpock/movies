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

    public function deleteRecord(Movie $rec)
    {
        $this->actionDataMessage = "Failed to delete Movie";

        $this->db->beginTransaction();

        $sql = "
            DELETE a, b, c, d, e
            FROM movie a
                LEFT OUTER JOIN movie_genre b ON a.`id` = b.`movie_id`
                LEFT OUTER JOIN movie_collection c ON a.`id` = c.`movie_id`
                LEFT OUTER JOIN movie_tmdb d ON a.`id` = d.`movie_id`
                LEFT OUTER JOIN movie_file e ON a.`id` = e.`movie_id`
            WHERE a.`id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->id()
        ], "i");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Movie Deleted";
            $this->db->commit();
            return 1;
        }
        $this->db->rollback();
        return 0;
    }

    public function unmapGenres(Movie $movie, $genres)
    {
        $this->actionDataMessage = "Failed to unmap Movies from Genre";

        if (!isset($genres) || $movie->id() < 0) {
            $this->actionDataMessage = "Missing IDs to unmap Genres from Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $arr = [];
        array_push($arr, $movie->id());

        $qs = [];
        $is = "i";
        foreach ($genres as $genre) {
            array_push($qs, "?");
            $is .= "i";
            array_push($arr, $genre);
        }

        $sql = "
            DELETE FROM movie_genre
            WHERE movie_id = ?
                AND genre_id NOT IN (" . implode(",", $qs) . ")
        ";

        $result = $this->db->query($sql, $arr, $is);

        if ($result) {
            $this->actionDataMessage = "Genres unmapped from Movie";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapGenre(Movie $rec, $genre_id = -1)
    {
        $this->actionDataMessage = "Failed to map Genre to Movie";

        if ($rec->id() < 0 || $genre_id < 0) {
            $this->actionDataMessage = "Missing IDs to map Genre to Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_genre (`movie_id`,`genre_id`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->id(),
            $genre_id
        ], "ii");

        if ($result) {
            $this->actionDataMessage = "Genre mapped to Movie";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function unmapCollections(Movie $movie, $collections)
    {
        $this->actionDataMessage = "Failed to unmap Collections from Movie";

        if (!isset($collections) || $movie->id() < 0) {
            $this->actionDataMessage = "Missing IDs to unmap Collections from Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $arr = [];
        array_push($arr, $movie->id());

        $qs = [];
        $is = "i";
        foreach ($collections as $collection) {
            array_push($qs, "?");
            $is .= "i";
            array_push($arr, $collection);
        }

        $sql = "
            DELETE FROM movie_collection
            WHERE movie_id = ?
                AND collection_id NOT IN (" . implode(",", $qs) . ")
        ";

        $result = $this->db->query($sql, $arr, $is);

        if ($result) {
            $this->actionDataMessage = "Collections unmapped from Movie";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapCollection(Movie $rec, $collection_id = -1)
    {
        $this->actionDataMessage = "Failed to map Collection to Movie";

        if ($rec->id() < 0 || $collection_id < 0) {
            $this->actionDataMessage = "Missing IDs to map Collection to Movie";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_collection (`movie_id`,`collection_id`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->id(),
            $collection_id
        ], "ii");

        if ($result) {
            $this->actionDataMessage = "Collection mapped to Movie";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapTMDB(Movie $rec, $tmdb_id = -1)
    {
        $this->actionDataMessage = "Failed to map Movie to TMDB";

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
}
