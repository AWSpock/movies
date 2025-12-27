<?php

class Genre
{
    protected $id;
    protected $name;
    protected $created;

    protected $movies = [];

    public function __construct($rec = null)
    {
        $this->id = -1;
        if ($rec !== NULL) {
            $this->id = (array_key_exists("id", $rec) && $rec['id'] !== NULL) ? $rec['id'] : -1;
            $this->name = (array_key_exists("name", $rec) && $rec['name'] !== NULL) ? $rec['name'] : null;
            $this->created = (array_key_exists("created", $rec) && $rec['created'] !== NULL) ? $rec['created'] : null;
        }
    }

    public static function fromDatabase($db)
    {
        $rec1['id'] = $db['id'];
        $rec1['name'] = $db['name'];
        $rec1['created'] = $db['created'];

        $new = new static($rec1);

        return $new;
    }

    public static function fromTMDB($db)
    {
        $rec1['id'] = $db->id;
        $rec1['name'] = $db->name;

        $new = new static($rec1);

        return $new;
    }

    public function id()
    {
        return $this->id;
    }
    public function name()
    {
        return $this->name;
    }
    public function created()
    {
        return $this->created;
    }

    public function movies()
    {
        return $this->movies;
    }

    public function toString($pretty = false)
    {
        $obj = (object) [
            "id" => $this->id(),
            "name" => $this->name(),
            "created" => $this->created()
        ];

        if (count($this->movies) > 0)
            $obj->movies = $this->movies();

        if ($pretty === true)
            return json_encode(get_object_vars($obj), JSON_PRETTY_PRINT);

        return json_encode(get_object_vars($obj));
    }

    public function addMovies($movies)
    {
        foreach ($movies as $movie) {
            if ($movie instanceof Movie) {
                $this->movies[$movie->id()] = $movie;
            } else {
                throw new Exception("Invalid Object in Array of Movies");
            }
        }
    }

    // public function addMovie(Movie_Info $movie)
    // {
    //     array_push($this->movies, $movie);
    // }
}
