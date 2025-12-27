<?php

require_once(__DIR__ . "/../../models/Collection.php");

class CollectionRepository
{
    private $db;

    private $records = [];
    private $recordsMovie = [];
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
                SELECT a.`id`, a.`name`, a.`created`
                FROM collection a
                WHERE a.`id` = ? 
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->records[$id] = Collection::fromDatabase($result->fetch_array(MYSQLI_ASSOC));
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
            SELECT a.`id`, a.`name`, a.`created`
            FROM collection a
            ORDER BY a.`name`
        ";

        $result = $this->db->query($sql);

        $this->loaded = true;
        $this->records = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->records[$rec['id']] = Collection::fromDatabase($rec);
        }
        return $this->records;
    }

    public function getCollectionsForMovie($id)
    {
        if (!array_key_exists($id, $this->recordsMovie)) {
            $sql = "
                SELECT a.`id`, a.`name`, a.`created`
                FROM collection a
                    INNER JOIN movie_collection b ON a.`id` = b.`collection_id`
                WHERE b.`movie_id` = ?
                ORDER BY a.`name`
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->recordsMovie[$id] = [];
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
                    array_push($this->recordsMovie[$id], Collection::fromDatabase($rec));
                }
            } else {
                $this->recordsMovie[$id] = null;
            }
        }
        return $this->recordsMovie[$id];
    }

    public function insertRecord(Collection $rec)
    {
        $this->actionDataMessage = "Failed to insert Collection";

        if (empty($rec->name())) {
            $this->actionDataMessage = "Name is required to insert Collection";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO collection (`name`)
            VALUES (?)
        ";

        $result = $this->db->query($sql, [
            $rec->name()
        ], "s");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Collection Inserted";
            // $this->db->commit();
            return $result;
        }
        // $this->db->rollback();
        return 0;
    }

    public function updateRecord(Collection $rec)
    {
        $this->actionDataMessage = "Failed to update Collection";

        if (empty($rec->name())) {
            $this->actionDataMessage = "Name is required to update Collection";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            UPDATE collection 
            SET `name` = ?
            WHERE `id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->name(),
            $rec->id()
        ], "si");

        if ($result !== false) {
            if ($result !== 1) {
                $this->actionDataMessage = "Collection Unchanged";
                return 2;
            }
            $this->actionDataMessage = "Collection Updated";
            // $this->db->commit();
            return 1;
        }

        // $this->db->rollback();
        return false;
    }

    public function deleteRecord(Collection $rec)
    {
        $this->actionDataMessage = "Failed to delete Collection";

        $this->db->beginTransaction();

        $sql = "
            DELETE a, b
            FROM collection a
                LEFT OUTER JOIN movie_collection b ON a.`id` = b.`collection_id`
            WHERE a.`id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->id()
        ], "i");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Collection Deleted";
            $this->db->commit();
            return 1;
        }
        $this->db->rollback();
        return 0;
    }


    public function unmapMovies(Collection $collection, $movies)
    {
        $this->actionDataMessage = "Failed to unmap Movies from Collection";

        if (!isset($movies) || $collection->id() < 0) {
            $this->actionDataMessage = "Missing IDs to unmap Movies from Collection";
            return 0;
        }

        // $this->db->beginTransaction();

        $arr = [];
        array_push($arr, $collection->id());

        $qs = [];
        $is = "i";
        foreach ($movies as $movie) {
            array_push($qs, "?");
            $is .= "i";
            array_push($arr, $movie);
        }

        $sql = "
            DELETE FROM movie_collection
            WHERE collection_id = ?
                AND movie_id NOT IN (" . implode(",", $qs) . ")
        ";

        $result = $this->db->query($sql, $arr, $is);

        if ($result) {
            $this->actionDataMessage = "Movies unmapped from Collection";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }

    public function mapMovie(Collection $collection, $movie_id = -1)
    {
        $this->actionDataMessage = "Failed to map Movie to Collection";

        if ($movie_id < 0 || $collection->id() < 0) {
            $this->actionDataMessage = "Missing IDs to map Movie to Collection";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_collection (`movie_id`,`collection_id`)
            SELECT ? AS movie_id, ? AS collection_id
            WHERE NOT EXISTS (
                SELECT 1
                FROM movie_collection
                WHERE `movie_id` = ?
                    AND `collection_id` = ?
            )
        ";

        $result = $this->db->query($sql, [
            $movie_id,
            $collection->id(),
            $movie_id,
            $collection->id()
        ], "iiii");

        if ($result) {
            $this->actionDataMessage = "Movie mapped to Collection";
            // $this->db->commit();
            return 1;
        }
        // $this->db->rollback();
        return 0;
    }
}
