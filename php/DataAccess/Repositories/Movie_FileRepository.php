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
                SELECT a.`id`, a.`created`, a.`updated`, a.`file_name`, a.`title`, a.`year`, a.`notes`, a.`from_disk`, a.`file_size`, a.`bluray`, a.`quality`, a.`movie_id`
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
            SELECT a.`id`, a.`created`, a.`updated`, a.`file_name`, a.`title`, a.`year`, a.`notes`, a.`from_disk`, a.`file_size`, a.`bluray`, a.`quality`, a.`movie_id`
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
            SELECT a.`id`, a.`created`, a.`updated`, a.`file_name`, a.`title`, a.`year`, a.`notes`, a.`from_disk`, a.`file_size`, a.`bluray`, a.`quality`, a.`movie_id`
                FROM movie_file a
            WHERE a.`file_exists` = 1
                AND a.`movie_id` IS NULL
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
                SELECT a.`id`, a.`created`, a.`updated`, a.`file_name`, a.`title`, a.`year`, a.`notes`, a.`from_disk`, a.`file_size`, a.`bluray`, a.`quality`, a.`movie_id`
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

    // public function insertRecord(Address $rec)
    // {
    //     $this->actionDataMessage = "Failed to insert Address";

    //     if (empty($rec->street())) {
    //         $this->actionDataMessage = "Street is required to insert Address";
    //         return 0;
    //     }

    //     $this->db->beginTransaction();

    //     $sql = "
    //         INSERT INTO address (`street`,`userid`)
    //         VALUES (?,?)
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->street(),
    //         $this->userid
    //     ], "si");

    //     if (is_int($result) && $result > 0) {
    //         $this->actionDataMessage = "Street Inserted";
    //         $this->db->commit();
    //         return $result;
    //     }
    //     $this->db->rollback();
    //     return 0;
    // }

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
                `file_name` = ?,
                `from_disk` = ?,
                `file_size` = ?,
                `bluray` = ?,
                `quality` = ?
            WHERE `id` = ? 
        ";

        $result = $this->db->query($sql, [
            $rec->movie_id(),
            $rec->title(),
            $rec->year(),
            $rec->notes(),
            $rec->file_name(),
            $rec->from_disk(),
            $rec->file_size(),
            $rec->bluray(),
            $rec->quality(),
            $rec->id()
        ], "isissiiisi");

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
