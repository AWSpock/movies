<?php

require_once(__DIR__ . "/../../models/Genre.php");

class GenreRepository
{
    private $db;

    private $records = [];
    private $recordsMovie = [];
    private $recordsMap = [];
    private $loaded = false;
    private $loadedMap = false;

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
                FROM genre a
                WHERE a.`id` = ? 
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->records[$id] = Genre::fromDatabase($result->fetch_array(MYSQLI_ASSOC));
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
            FROM genre a
            ORDER BY a.`name`
        ";

        $result = $this->db->query($sql);

        $this->loaded = true;
        $this->records = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->records[$rec['id']] = Genre::fromDatabase($rec);
        }
        return $this->records;
    }

    public function getGenresForMovie($id)
    {
        if (!array_key_exists($id, $this->records)) {
            $sql = "
                SELECT a.`id`, a.`name`, a.`created`
                FROM genre a
                    INNER JOIN movie_genre b ON a.`id` = b.`genre_id`
                WHERE b.`movie_id` = ?
                ORDER BY a.`name`
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->records[$id] = [];
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
                    array_push($this->records[$id], Genre::fromDatabase($rec));
                }
            } else {
                $this->records[$id] = null;
            }
        }
        return $this->records[$id];
    }

    public function insertRecord(Genre $rec)
    {
        $this->actionDataMessage = "Failed to insert Genre";

        if (empty($rec->name())) {
            $this->actionDataMessage = "Name is required to insert Genre";
            return 0;
        }

        $this->db->beginTransaction();

        $sql = "
            INSERT INTO genre (`id`,`name`)
            VALUES (?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->id(),
            $rec->name()
        ], "is");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Genre Inserted";
            $this->db->commit();
            return $result;
        }
        $this->db->rollback();
        return 0;
    }
}
