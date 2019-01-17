<?php

// use GuzzleHttp\Client;

// use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Exception\ClientException;

class Test extends MyController
{
    public function index()
    {
        $data = $this->input->post();
        // var_dump($data);exit;
        $this->load->view('welcome_message', $data);
    }

    public function getUser()
    {
        return $this->ajaxSuccess(['name' => 'Peter', 'age' => 28]);
    }

    public function check()
    {
        $id  = $this->get('id');
        $lib = library('IDCard');
        var_dump($id, $lib->validate($id));
    }

    public function redis() {
        // $redis1 = library('MyRedis');
        $redis2 = library('myRedis');

        // var_dump($_SESSION);
        // $session->set_userdata([
        //     'name' => 'Peter',
        //     'age' => 27,
        // ]);
    }

    public function img() {
        $this->load->library('MyImage');
    }

    public function echo() {
        echo 'aaa';
    }
}
