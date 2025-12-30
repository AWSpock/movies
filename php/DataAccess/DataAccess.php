<?php
require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Repositories/MovieRepository.php");
require_once(__DIR__ . "/Repositories/Movie_FileRepository.php");
require_once(__DIR__ . "/Repositories/GenreRepository.php");
require_once(__DIR__ . "/Repositories/CollectionRepository.php");
require_once(__DIR__ . "/Repositories/Movie_ViewsRepository.php");
require_once(__DIR__ . "/Repositories/User_RoleRepository.php");

class DataAccess
{
    private $db;
    private $movieRepository = [];
    private $movie_FileRepository = [];
    private $genreRepository = [];
    private $collectionRepository = [];
    private $movie_ViewsRepository = [];
    private $user_RoleRepository = [];

    public function __construct(mysqli $db = null)
    {
        $this->db = $db ?? new DatabaseV2();
    }

    public function movies()
    {
        if (!array_key_exists(0, $this->movieRepository)) {
            $this->movieRepository[0] = new MovieRepository($this->db);
        }
        return $this->movieRepository[0];
    }

    public function movie_files()
    {
        if (!array_key_exists(0, $this->movie_FileRepository)) {
            $this->movie_FileRepository[0] = new Movie_FileRepository($this->db);
        }
        return $this->movie_FileRepository[0];
    }

    public function genres()
    {
        if (!array_key_exists(0, $this->genreRepository)) {
            $this->genreRepository[0] = new GenreRepository($this->db);
        }
        return $this->genreRepository[0];
    }

    public function collections()
    {
        if (!array_key_exists(0, $this->collectionRepository)) {
            $this->collectionRepository[0] = new CollectionRepository($this->db);
        }
        return $this->collectionRepository[0];
    }

    public function movie_views($userid)
    {
        if (!array_key_exists($userid, $this->movie_ViewsRepository)) {
            $this->movie_ViewsRepository[$userid] = new Movie_ViewsRepository($this->db, $userid);
        }
        return $this->movie_ViewsRepository[$userid];
    }

    public function user_roles($userid)
    {
        if (!array_key_exists($userid, $this->user_RoleRepository)) {
            $this->user_RoleRepository[$userid] = new User_RoleRepository($this->db, $userid);
        }
        return $this->user_RoleRepository[$userid];
    }

    //

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }
    public function commit()
    {
        $this->db->commit();
    }
    public function rollback()
    {
        $this->db->rollback();
    }
    public function close()
    {
        $this->db->close();
    }
    public function getDb()
    {
        return $this->db;
    }
}
