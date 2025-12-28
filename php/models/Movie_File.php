<?php

class Movie_File
{
    protected $id;
    protected $created;
    protected $updated;
    protected $movie_id;
    protected $title;
    protected $year;
    protected $notes;
    protected $file_type;
    protected $file_name;
    protected $from_disk;
    protected $quality_notes;
    protected $file_size;
    protected $bluray;
    protected $quality;

    public function __construct($rec = null)
    {
        $this->id = -1;
        if ($rec !== NULL) {
            $this->id = (array_key_exists("id", $rec) && $rec['id'] !== NULL) ? $rec['id'] : -1;
            $this->created = (array_key_exists("created", $rec) && $rec['created'] !== NULL) ? $rec['created'] : null;
            $this->updated = (array_key_exists("updated", $rec) && $rec['updated'] !== NULL) ? $rec['updated'] : null;
            $this->movie_id = (array_key_exists("movie_id", $rec) && $rec['movie_id'] !== NULL) ? $rec['movie_id'] : null;
            $this->title = (array_key_exists("title", $rec) && $rec['title'] !== NULL) ? $rec['title'] : null;
            $this->year = (array_key_exists("year", $rec) && $rec['year'] !== NULL) ? $rec['year'] : null;
            $this->notes = (array_key_exists("notes", $rec) && $rec['notes'] !== NULL) ? $rec['notes'] : null;
            $this->file_type = (array_key_exists("file_type", $rec) && $rec['file_type'] !== NULL) ? $rec['file_type'] : null;
            $this->file_name = (array_key_exists("file_name", $rec) && $rec['file_name'] !== NULL) ? $rec['file_name'] : null;
            $this->from_disk = (array_key_exists("from_disk", $rec) && $rec['from_disk'] !== NULL) ? $rec['from_disk'] : null;
            $this->quality_notes = (array_key_exists("quality_notes", $rec) && $rec['quality_notes'] !== NULL) ? $rec['quality_notes'] : null;
            $this->file_size = (array_key_exists("file_size", $rec) && $rec['file_size'] !== NULL) ? $rec['file_size'] : null;
            $this->bluray = (array_key_exists("bluray", $rec) && $rec['bluray'] !== NULL) ? $rec['bluray'] : null;
            $this->quality = (array_key_exists("quality", $rec) && $rec['quality'] !== NULL) ? $rec['quality'] : null;
        }
    }

    public static function fromPost($post)
    {
        $exploded = explode("-", $post['movie_file_file_name']);

        $rec1['id'] = !empty($post['movie_file_id']) ? $post['movie_file_id'] : -1;
        $rec1['movie_id'] = $post['movie_file_movie_id'];
        $rec1['file_name'] = $post['movie_file_file_name'];
        $rec1['title'] = str_replace("_", " ", $exploded[0]);
        $rec1['year'] = $exploded[1];
        $rec1['notes'] = (count($exploded) > 2) ? str_replace("_", " ", $exploded[2]) : null;
        $rec1['file_type'] = pathinfo($rec1['file_name'], PATHINFO_EXTENSION);
        $rec1['from_disk'] = $post['movie_file_from_disk'];
        $rec1['quality_notes'] = $post['movie_file_quality_notes'];
        $rec1['bluray'] = $post['movie_file_bluray'];
        $rec1['quality'] = $post['movie_file_quality'];
        $new = new static($rec1);
        return $new;
    }

    public static function fromDatabase($db)
    {
        $rec1['id'] = $db['id'];
        $rec1['created'] = $db['created'];
        $rec1['updated'] = $db['updated'];
        $rec1['movie_id'] = $db['movie_id'];
        $rec1['title'] = $db['title'];
        $rec1['year'] = $db['year'];
        $rec1['notes'] = $db['notes'];
        $rec1['file_type'] = $db['file_type'];
        $rec1['file_name'] = $db['file_name'];
        $rec1['from_disk'] = $db['from_disk'];
        $rec1['quality_notes'] = $db['quality_notes'];
        $rec1['file_size'] = $db['file_size'];
        $rec1['bluray'] = $db['bluray'];
        $rec1['quality'] = $db['quality'];
        $new = new static($rec1);
        return $new;
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
    public function movie_id()
    {
        return $this->movie_id;
    }
    public function title()
    {
        return $this->title;
    }
    public function year()
    {
        return ($this->year !== null) ? intval($this->year) : null;
    }
    public function notes()
    {
        return $this->notes;
    }
    public function file_type()
    {
        return $this->file_type;
    }
    public function file_name()
    {
        return $this->file_name;
    }
    public function from_disk()
    {
        return ($this->from_disk !== null) ? boolval($this->from_disk) : null;
    }
    public function quality_notes()
    {
        return $this->quality_notes;
    }
    public function file_size()
    {
        return ($this->file_size !== null) ? intval($this->file_size) : null;
    }
    public function bluray()
    {
        return ($this->bluray !== null) ? boolval($this->bluray) : null;
    }
    public function quality()
    {
        return $this->quality;
    }

    public function setMovieID($id)
    {
        $this->movie_id = $id;
    }

    public function toString($pretty = false)
    {
        $obj = (object) [
            "id" => $this->id(),
            "created" => $this->created(),
            "updated" => $this->updated(),
            "movie_id" => $this->movie_id(),
            "title" => $this->title(),
            "year" => $this->year(),
            "notes" => $this->notes(),
            "file_type" => $this->file_type(),
            "file_name" => $this->file_name(),
            "from_disk" => $this->from_disk(),
            "quality_notes" => $this->quality_notes(),
            "file_size" => $this->file_size(),
            "bluray" => $this->bluray(),
            "quality" => $this->quality()
        ];

        if ($pretty === true)
            return json_encode(get_object_vars($obj), JSON_PRETTY_PRINT);

        return json_encode(get_object_vars($obj));
    }
}
