<?php

require_once(__DIR__ . "/../../models/Movie_File.php");

class Movie_FileRepository
{
    private $db;

    private $records = [];
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
                SELECT a.`id`, a.`created`, a.`updated`, a.`movie_id`, a.`title`, a.`year`, a.`notes`, a.`file_type`, a.`file_name`, a.`from_disk`, a.`quality_notes`, a.`file_size`, a.`bluray`, a.`quality`
                FROM movie_file a
                WHERE a.`id` = ? 
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $rec = Movie_File::fromDatabase($result->fetch_array(MYSQLI_ASSOC));
                $this->records[$id] = $rec;
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
            SELECT a.`id`, a.`created`, a.`updated`, a.`movie_id`, a.`title`, a.`year`, a.`notes`, a.`file_type`, a.`file_name`, a.`from_disk`, a.`quality_notes`, a.`file_size`, a.`bluray`, a.`quality`
                FROM movie_file a
            WHERE a.`file_exists` = 1
            ORDER BY `title`
        ";

        $result = $this->db->query($sql);

        $this->loaded = true;
        $this->records = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->records[$rec['id']] = Movie_File::fromDatabase($rec);
        }
        return $this->records;
    }

    public function getRecordsToMap()
    {
        if ($this->loadedMap) {
            $recs = [];
            foreach ($this->recordsMap as $key => $rec) {
                if ($rec->id() > 0)
                    $recs[$key] = $rec;
            }
            return $recs;
        }

        $sql = "
            SELECT a.`id`, a.`created`, a.`updated`, a.`movie_id`, a.`title`, a.`year`, a.`notes`, a.`file_type`, a.`file_name`, a.`from_disk`, a.`quality_notes`, a.`file_size`, a.`bluray`, a.`quality`
                FROM movie_file a
            WHERE a.`file_exists` = 1
                AND (
                    a.`movie_id` IS NULL
                    OR a.`movie_id` = ''
                )
            ORDER BY `title`
        ";

        $result = $this->db->query($sql);

        $this->loadedMap = true;
        $this->recordsMap = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->recordsMap[$rec['id']] = Movie_File::fromDatabase($rec);
        }
        return $this->recordsMap;
    }

    public function getMovieFilesForMovie($id)
    {
        if (!array_key_exists($id, $this->records)) {
            $sql = "
                SELECT a.`id`, a.`created`, a.`updated`, a.`movie_id`, a.`title`, a.`year`, a.`notes`, a.`file_type`, a.`file_name`, a.`from_disk`, a.`quality_notes`, a.`file_size`, a.`bluray`, a.`quality`
                    FROM movie_file a
                WHERE a.`file_exists` = 1
                    AND a.`movie_id` = ?
                ORDER BY a.`file_name`
            ";

            $result = $this->db->query($sql, [
                $id,
            ], "i");

            if ($result) {
                $this->records[$id] = [];
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
                    array_push($this->records[$id], Movie_File::fromDatabase($rec));
                }
            } else {
                $this->records[$id] = null;
            }
        }
        return $this->records[$id];
    }

    public function insertRecord(Movie_File $rec)
    {
        $this->actionDataMessage = "Failed to insert Movie_File";

        if (empty($rec->file_name())) {
            $this->actionDataMessage = "File Name is required to insert Movie_File";
            return 0;
        }

        $this->db->beginTransaction();

        $sql = "
            INSERT INTO movie_file (`movie_id`,`title`,`year`,`notes`,`file_type`,`file_name`,`from_disk`,`quality_notes`,`bluray`,`quality`)
            VALUES (?,?,?,?,?,?,?,?,?)
        ";

        $result = $this->db->query($sql, [
            $rec->movie_id(),
            $rec->title(),
            $rec->year(),
            $rec->notes(),
            $rec->file_type(),
            $rec->file_name(),
            $rec->from_disk(),
            $rec->quality_notes(),
            $rec->bluray(),
            $rec->quality(),
            $rec->id()
        ], "isisssisis");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Movie_File Inserted";
            $this->db->commit();
            return $result;
        }
        $this->db->rollback();
        return 0;
    }

    public function updateRecord(Movie_File $rec)
    {
        $this->actionDataMessage = "Failed to update Movie_File";

        if (empty($rec->file_name())) {
            $this->actionDataMessage = "File Name is required to update Movie_File";
            return 0;
        }

        // $this->db->beginTransaction();

        $sql = "
            UPDATE movie_file 
            SET `movie_id` = ?,
                `title` = ?,
                `year` = ?,
                `notes` = ?,
                `file_type` = ?,
                `file_name` = ?,
                `from_disk` = ?,
                `quality_notes` = ?,
                `bluray` = ?,
                `quality` = ?
            WHERE `id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->movie_id(),
            $rec->title(),
            $rec->year(),
            $rec->notes(),
            $rec->file_type(),
            $rec->file_name(),
            $rec->from_disk(),
            $rec->quality_notes(),
            $rec->bluray(),
            $rec->quality(),
            $rec->id()
        ], "isisssisisi");

        if ($result !== false) {
            if ($result !== 1) {
                $this->actionDataMessage = "Movie_File Unchanged";
                return 2;
            }
            $this->actionDataMessage = "Movie_File Updated";
            // $this->db->commit();
            return 1;
        }

        // $this->db->rollback();
        return false;
    }

    public function deleteRecord(Movie_File $rec)
    {
        $this->actionDataMessage = "Failed to delete Movie_File";

        $this->db->beginTransaction();

        $sql = "
            DELETE a
            FROM movie_file a
            WHERE a.`id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->id()
        ], "i");

        if (is_int($result) && $result > 0) {
            $this->actionDataMessage = "Movie_File Deleted";
            $this->db->commit();
            return 1;
        }
        $this->db->rollback();
        return 0;
    }
}
