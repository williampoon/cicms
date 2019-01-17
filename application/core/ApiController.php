<?php

class ApiController extends MyController
{
    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range');
        header('Access-Control-Expose-Headers: Content-Length,Content-Range');

        if ($this->input->method() === 'options') {
            header('HTTP/1.1 204 OK');
            exit();
        }
    }
}
