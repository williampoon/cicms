<?php

use GuzzleHttp\Client;

class Http extends MyController
{
    public function index()
    {
        exit('aaa');
        $client = new Client([
            'base_uri' => 'http://dev.center.com',
            // 'base_uri' => '127.0.0.1',
            // 'timeout'  => 0.5,
        ]);

        $response = $client->get('/api/user/index');
        var_dump(json_encode($response));exit;
    }

    public function raw() {
        var_dump($this->input->input_stream());
        var_dump($this->input->raw_input_stream);
        var_dump(file_get_contents('php://input'));
    }

    public function curl() {
        $service = new MyService();
        $res = $service->get('http://dev.cicms.com/test/getUser');
        var_dump('res');exit;
    }
}
