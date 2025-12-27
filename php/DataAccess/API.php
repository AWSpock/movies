<?php

class API
{
    private $api_base;
    private $api_key;
    private $file_dir;

    public $actionDataMessage;

    public function __construct($env = null)
    {
        if (!isset($env))
            $env = parse_ini_file(__DIR__ . '/.env');

        if (array_key_exists("API_BASE", $env))
            $this->api_base = $env["API_BASE"];
        if (array_key_exists("API_KEY", $env))
            $this->api_key = $env["API_KEY"];
        if (array_key_exists("FILE_DIR", $env))
            $this->file_dir = $env["FILE_DIR"];
    }

    public function api_key()
    {
        return $this->api_key;
    }

    public function movie_search($title, $year = null)
    {
        $curl = curl_init();

        $results = [];

        $page = 1;

        while (true) {
            $url = $this->api_base;
            $url .= "/search/movie";
            $url .= "?query=" . urlencode($title);
            //$url .= "&include_adult=true";
            if ($year !== null)
                $url .= "&year=" . $year;
            $url .= "&page=" . $page;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->api_key
                ],
            ));

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('cURL error: ' . curl_error($curl));
                break;
            }

            $res = json_decode($response);
            foreach ($res->results as $rec) {
                array_push($results, $rec);
            }

            if ($page < $res->total_pages) {
                $page++;
            } else {
                break;
            }
        }

        curl_close($curl);

        return $results;
    }

    public function get_movie($movie_id)
    {
        $curl = curl_init();
        $url = $this->api_base;
        $url .= "/movie/" . $movie_id;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key
            ],
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception('cURL error: ' . curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response);
    }

    public function genres()
    {
        $curl = curl_init();

        $results = [];

        $url = $this->api_base;
        $url .= "/genre/movie/list";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key
            ],
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception('cURL error: ' . curl_error($curl));
        }

        $res = json_decode($response);
        foreach ($res->genres as $rec) {
            array_push($results, $rec);
        }

        curl_close($curl);

        return $results;
    }

    public function download_poster($details)
    {
        $poster = new stdClass();

        $url = "https://image.tmdb.org/t/p/w200" . $details->poster_path;

        // error_log("Download Poster: " . $url, 0);

        $name = bin2hex(random_bytes(16));
        $shard1 = substr($name, 0, 2);
        $shard2 = substr($name, 2, 2);

        $destination_dir = $this->file_dir . $shard1 . "/" . $shard2 . "/";
        $destination = $destination_dir . $name;

        if (!is_dir($destination_dir)) {
            if (!mkdir($destination_dir, 0770, true)) {
                $this->actionDataMessage = "Failed to create directories";
                return false;
            }
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // Exclude the header from the output
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 100); // Set a timeout
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // We want the transfer to be handled by CURLOPT_FILE
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // Set a user agent to avoid 403 errors

        $imageData = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->actionDataMessage = "cURL error: " . curl_error($ch);
            return false;
        } else {
            $this->actionDataMessage = "File downloaded successfully";
        }

        $poster->file_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $poster->name = $name;

        if ($imageData !== false) {
            file_put_contents($destination, $imageData);
            $this->actionDataMessage .= "; Saved to: " . htmlspecialchars($destination);
        } else {
            $this->actionDataMessage = "Failed to Download Image";
            return false;
        }

        curl_close($ch);

        return $poster;
    }
}
