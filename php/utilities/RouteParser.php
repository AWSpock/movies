<?php

class RouteParser
{
    protected $Route;

    protected $basePath;
    protected $siteDir;
    protected $request;
    protected $pagePath;
    protected $codePath;
    protected $resourcePath;

    public function __construct($type = "view")
    {
        $this->siteDir = $_SERVER['DOCUMENT_ROOT'];
        $this->request = $_SERVER['REDIRECT_URL'];

        switch (strtolower($type)) {
            case "view":
                $this->basePath = "/pages";
                $this->ValidateRoute();
                break;
            case "api":
                $this->basePath = "/api";
                $this->ValidateAPIRoute();
                break;
            default:
                throw new Exception("Invalid RouteParser Type");
                break;
        }
    }

    protected function ValidateRoute()
    {
        /* index */
        if (preg_match("~^/$~", $this->request)) {
            $this->resourcePath = "/index";
            return;
        }
        if (preg_match("~^/movie-map$~", $this->request)) {
            $this->resourcePath = "/movie-map/index";
            return;
        }
        if (preg_match("~^/genre$~", $this->request)) {
            $this->resourcePath = "/genre/index";
            return;
        }
        if (preg_match("~^/collection$~", $this->request)) {
            $this->resourcePath = "/collection/index";
            return;
        }
        if (preg_match("~^/movie-file$~", $this->request)) {
            $this->resourcePath = "/movie-file/index";
            return;
        }

        /* details */
        if (preg_match("~^/movie/(\d+)/summary$~", $this->request)) {
            $this->resourcePath = "/movie/index";
            return;
        }
        if (preg_match("~^/genre/(\d+)$~", $this->request)) {
            $this->resourcePath = "/genre/movie";
            return;
        }
        if (preg_match("~^/collection/(\d+)$~", $this->request)) {
            $this->resourcePath = "/collection/movie";
            return;
        }
        if (preg_match("~^/movie-file/(\d+)$~", $this->request)) {
            $this->resourcePath = "/movie-file/file";
            return;
        }

        if (preg_match("~^/movie-map/(\d+)/tmdb$~", $this->request)) {
            $this->resourcePath = "/movie-map/tmdb";
            return;
        }
        if (preg_match("~^/movie-map/(\d+)/movie$~", $this->request)) {
            $this->resourcePath = "/movie-map/movie";
            return;
        }

        /* create */
        if (preg_match("~^/collection/create$~", $this->request)) {
            $this->resourcePath = "/collection/create";
            return;
        }

        /* edit */
        if (preg_match("~^/movie/(\d+)/edit$~", $this->request)) {
            $this->resourcePath = "/movie/edit";
            return;
        }
        if (preg_match("~^/collection/(\d+)/edit$~", $this->request)) {
            $this->resourcePath = "/collection/edit";
            return;
        }
        if (preg_match("~^/movie-file/(\d+)/edit$~", $this->request)) {
            $this->resourcePath = "/movie-file/edit";
            return;
        }

        /* delete */
        if (preg_match("~^/movie/(\d+)/delete$~", $this->request)) {
            $this->resourcePath = "/movie/delete";
            return;
        }
        if (preg_match("~^/collection/(\d+)/delete$~", $this->request)) {
            $this->resourcePath = "/collection/delete";
            return;
        }
        if (preg_match("~^/movie-file/(\d+)/delete$~", $this->request)) {
            $this->resourcePath = "/movie-file/delete";
            return;
        }


        /* basic */
        if (preg_match("~^/error$~", $this->request)) {
            $this->resourcePath = "/error";
            return;
        }
        if (preg_match("~^/unauthorized$~", $this->request)) {
            $this->resourcePath = "/unauthorized";
            return;
        }
    }

    protected function ValidateAPIRoute()
    {
        if (preg_match("~^/api/movie$~", $this->request)) {
            $this->resourcePath = "/movie";
            return;
        }
        if (preg_match("~^/api/movie/(\d+)$~", $this->request)) {
            $this->resourcePath = "/movie";
            return;
        }
        if (preg_match("~^/api/movie/(\d+)/poster$~", $this->request)) {
            $this->resourcePath = "/movie-poster";
            return;
        }
        if (preg_match("~^/api/movie/(\d+)/genre$~", $this->request)) {
            $this->resourcePath = "/movie-genre";
            return;
        }
        if (preg_match("~^/api/movie/(\d+)/collection$~", $this->request)) {
            $this->resourcePath = "/movie-collection";
            return;
        }

        if (preg_match("~^/api/genre$~", $this->request)) {
            $this->resourcePath = "/genre";
            return;
        }
        if (preg_match("~^/api/genre/(\d+)$~", $this->request)) {
            $this->resourcePath = "/genre";
            return;
        }
        if (preg_match("~^/api/genre/(\d+)/movies$~", $this->request)) {
            $this->resourcePath = "/genre-movie";
            return;
        }

        if (preg_match("~^/api/collection$~", $this->request)) {
            $this->resourcePath = "/collection";
            return;
        }
        if (preg_match("~^/api/collection/(\d+)$~", $this->request)) {
            $this->resourcePath = "/collection";
            return;
        }
        if (preg_match("~^/api/collection/(\d+)/movies$~", $this->request)) {
            $this->resourcePath = "/collection-movie";
            return;
        }

        if (preg_match("~^/api/movie-map$~", $this->request)) {
            $this->resourcePath = "/movie-map";
            return;
        }
        if (preg_match("~^/api/movie-map/(\d+)$~", $this->request)) {
            $this->resourcePath = "/movie-map";
            return;
        }

        if (preg_match("~^/api/movie-file$~", $this->request)) {
            $this->resourcePath = "/movie-file";
            return;
        }
        if (preg_match("~^/api/movie-file/(\d+)$~", $this->request)) {
            $this->resourcePath = "/movie-file";
            return;
        }
    }

    function Request()
    {
        return $this->request;
    }
    function PagePath()
    {
        if ($this->resourcePath == "")
            return "";
        return $this->siteDir . $this->basePath . $this->resourcePath . ".php";
    }
    function CodePath()
    {
        if ($this->resourcePath == "")
            return "";
        return $this->siteDir . $this->basePath . $this->resourcePath . ".code.php";;
    }
    function CSS()
    {
        if ($this->resourcePath == "")
            return "";
        return $this->basePath . $this->resourcePath . ".php.css";
    }
    function JS()
    {
        if ($this->resourcePath == "")
            return "";
        return $this->basePath . $this->resourcePath . ".php.js";
    }
    function ResourcePath()
    {
        return $this->resourcePath;
    }
    function Page404()
    {
        return $this->siteDir . $this->basePath . "/404.php";
    }
}
