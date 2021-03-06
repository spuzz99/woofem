<?php

namespace Woofem;

class Request
{

    /**
     * @var string
     *
     * Request URI
     */
    public $path;

    /**
     * @var string
     *
     * Request Method
     */
    public $method;

    /**
     * @var object
     *
     * Query parameters as key->value pairs
     */
    public $query_params;


    public function __construct()
    {
        $this->method = $this->getMethod();
    }

    private function getAccepts()
    {
        $out = array();
        $http_accepts = $_SERVER['HTTP_ACCEPT'];
        $exploded = explode(',', $http_accepts);
        foreach ($exploded as $accept) {
            $out[] = filter_var($accept, FILTER_SANITIZE_STRIPPED);
        }
        return $out;
    }

    private function getMethod()
    {
        return filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRIPPED);
    }

    private function getPath()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $components = explode('?', $uri);
        $path = $components[0];
        if ($path != '/') {
            $path = trim($path, '/');
        }
        return filter_var($path, FILTER_SANITIZE_STRIPPED);
    }

    private function getPost()
    {
        return Filters::filterKeyValuePairs($_POST);

    }

    private function getPut()
    {
        $vars = json_decode(file_get_contents("php://input"));
        if ($vars) {
            return Filters::filterKeyValuePairs($vars);
        }
        return FALSE;
    }

    private function getQueryParameters()
    {
        return Filters::filterKeyValuePairs($_GET);
    }

    public function getRequestObject()
    {
        $out = new \stdClass();
        $out->accepts = $this->getAccepts();
        $out->method = $this->method;
        if (!empty($_GET)) {
            $out->parameters = $this->getQueryParameters();
        }
        if (!empty($_POST)) {
            $out->post = $this->getPost();
        }
        if ($this->method == 'PUT') {
            $out->put = $this->getPut();
        }
        $out->path = $this->getPath();
        $out->time = $this->getTime();
        $out->user_agent = $this->getUserAgent();
        return $out;
    }

    private function getUserAgent()
    {
        return filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_STRIPPED);
    }

    private function getTime()
    {
        return filter_var($_SERVER['REQUEST_TIME'], FILTER_SANITIZE_NUMBER_INT);
    }
}