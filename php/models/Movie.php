<?php

class Movie
{
    protected $id;
    protected $created;
    protected $updated;
    protected $title;
    protected $order_title;
    protected $overview;
    protected $release_date;
    protected $poster_path;
    protected $poster_shard1;
    protected $poster_shard2;
    protected $poster_file_type;
    protected $backdrop_path;

    protected $genres = [];
    protected $movie_files = [];
    protected $collections = [];

    protected $user_view_count = null;

    public function __construct($rec = null)
    {
        $this->id = -1;
        if ($rec !== NULL) {
            $this->id = (array_key_exists("id", $rec) && $rec['id'] !== NULL) ? $rec['id'] : -1;
            $this->created = (array_key_exists("created", $rec) && $rec['created'] !== NULL) ? $rec['created'] : null;
            $this->updated = (array_key_exists("updated", $rec) && $rec['updated'] !== NULL) ? $rec['updated'] : null;

            $this->title = (array_key_exists("title", $rec) && $rec['title'] !== NULL) ? $rec['title'] : null;
            $this->order_title = (array_key_exists("order_title", $rec) && $rec['order_title'] !== NULL) ? $rec['order_title'] : null;
            $this->overview = (array_key_exists("overview", $rec) && $rec['overview'] !== NULL) ? $rec['overview'] : null;
            $this->release_date = (array_key_exists("release_date", $rec) && $rec['release_date'] !== NULL) ? $rec['release_date'] : null;
            $this->poster_path = (array_key_exists("poster_path", $rec) && $rec['poster_path'] !== NULL) ? $rec['poster_path'] : null;
            $this->poster_shard1 = (array_key_exists("poster_shard1", $rec) && $rec['poster_shard1'] !== NULL) ? $rec['poster_shard1'] : null;
            $this->poster_shard2 = (array_key_exists("poster_shard2", $rec) && $rec['poster_shard2'] !== NULL) ? $rec['poster_shard2'] : null;
            $this->poster_file_type = (array_key_exists("poster_file_type", $rec) && $rec['poster_file_type'] !== NULL) ? $rec['poster_file_type'] : null;
            $this->backdrop_path = (array_key_exists("backdrop_path", $rec) && $rec['backdrop_path'] !== NULL) ? $rec['backdrop_path'] : null;
        }
    }

    public static function fromPost($post)
    {
        $rec1['id'] = !empty($post['movie_id']) ? $post['movie_id'] : -1;
        $rec1['order_title'] = $post['movie_order_title'];
        $new = new static($rec1);
        return $new;
    }

    public static function fromDatabase($db)
    {
        $rec1['id'] = $db['id'];
        $rec1['created'] = $db['created'];
        $rec1['updated'] = $db['updated'];

        $rec1['title'] = $db['title'];
        $rec1['order_title'] = $db['order_title'];
        $rec1['overview'] = $db['overview'];
        $rec1['release_date'] = $db['release_date'];
        $rec1['poster_path'] = $db['poster_path'];
        $rec1['poster_shard1'] = $db['poster_shard1'];
        $rec1['poster_shard2'] = $db['poster_shard2'];
        $rec1['poster_file_type'] = $db['poster_file_type'];
        $rec1['backdrop_path'] = $db['backdrop_path'];

        $new = new static($rec1);
        return $new;
    }

    public static function fromTMDB($db, $poster)
    {
        $rec1['id'] = -1;

        $rec1['title'] = $db->title;
        $rec1['order_title'] = $db->title;
        $rec1['overview'] = $db->overview;
        $rec1['release_date'] = $db->release_date;
        $rec1['poster_path'] = $poster->name;
        $rec1['poster_shard1'] = substr($poster->name, 0, 2);
        $rec1['poster_shard2'] = substr($poster->name, 2, 2);
        $rec1['poster_file_type'] = $poster->file_type;
        // $rec1['backdrop_path'] = $db->backdrop_path;

        $new = new static($rec1);
        return $new;
    }

    public function set_id($val)
    {
        $this->id = $val;
    }
    public function set_order_title($val)
    {
        $this->order_title = $val;
    }

    public function id()
    {
        return $this->id;
    }
    public function created()
    {
        return $this->created;
    }
    public function updated()
    {
        return $this->updated;
    }

    public function title()
    {
        return $this->title;
    }
    public function order_title()
    {
        return $this->order_title;
    }
    public function overview()
    {
        return $this->overview;
    }
    public function release_date()
    {
        return $this->release_date;
    }
    public function poster_path()
    {
        return $this->poster_path;
    }
    public function poster_shard1()
    {
        return $this->poster_shard1;
    }
    public function poster_shard2()
    {
        return $this->poster_shard2;
    }
    public function poster_file_type()
    {
        return $this->poster_file_type;
    }
    public function backdrop_path()
    {
        return $this->backdrop_path;
    }

    public function genres()
    {
        return $this->genres;
    }
    public function movie_files()
    {
        return $this->movie_files;
    }
    public function collections()
    {
        return $this->collections;
    }
    public function user_view_count()
    {
        return $this->user_view_count !== null ? intval($this->user_view_count) : null;
    }

    public function toString($pretty = false)
    {
        $obj = (object) [
            "id" => $this->id(),
            "created" => $this->created(),
            "updated" => $this->updated(),
            "title" => $this->title(),
            "order_title" => $this->order_title(),
            "overview" => $this->overview(),
            "release_date" => $this->release_date(),
            "poster_path" => $this->poster_path(),
            "poster_shard1" => $this->poster_shard1(),
            "poster_shard2" => $this->poster_shard2(),
            "poster_file_type" => $this->poster_file_type(),
            "backdrop_path" => $this->backdrop_path(),
        ];

        if (count($this->genres) > 0)
            $obj->genres = $this->genres();

        if (count($this->movie_files) > 0)
            $obj->movie_files = $this->movie_files();

        if (count($this->collections) > 0)
            $obj->collections = $this->collections();

        if ($this->user_view_count !== null)
            $obj->user_view_count = $this->user_view_count();

        if ($pretty === true)
            return json_encode(get_object_vars($obj), JSON_PRETTY_PRINT);

        return json_encode(get_object_vars($obj));
    }

    public function addGenres($genres = [])
    {
        foreach ($genres as $genre) {
            if ($genre instanceof Genre) {
                array_push($this->genres, $genre);
            } else {
                throw new Exception("Invalid Object in Array of Genres");
            }
        }
    }

    public function addGenre(Genre $genre)
    {
        array_push($this->genres, $genre);
    }

    public function addMovieFiles($movie_files = [])
    {
        foreach ($movie_files as $movie_file) {
            if ($movie_file instanceof Movie_File) {
                array_push($this->movie_files, $movie_file);
            } else {
                throw new Exception("Invalid Object in Array of Movie_Files");
            }
        }
    }

    public function addMovieFile(Movie_File $movie_file)
    {
        array_push($this->movie_files, $movie_file);
    }

    public function addCollections($collections = [])
    {
        foreach ($collections as $collection) {
            if ($collection instanceof Collection) {
                array_push($this->collections, $collection);
            } else {
                throw new Exception("Invalid Object in Array of Collection");
            }
        }
    }

    public function addCollection(Collection $collection)
    {
        array_push($this->collections, $collection);
    }

    public function addUserViewCount($count)
    {
        $this->user_view_count = $count;
    }
}
