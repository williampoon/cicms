<?php

class Upload extends MyController
{
    /*public function __construct()
    {
        parent::__construct();
    }*/

    public function index()
    {
        // $ch = curl_init('http://dev.center.com');
        $this->display();
    }

    public function send()
    {
        $config = load_config('upload.client');
        $client = library('UploadClient', $config);
        // $client->addFile('/tmp/test.jpg', 'test.jpg');
        // $client->addFile(APPPATH . '../public/ace/images/gallery/image-1.jpg', 'image_1.jpg');
        $client->addFile('/tmp/test.jpg');
        $client->addFile(APPPATH . '../public/ace/images/gallery/image-1.jpg');
        $res = $client->upload();
        if ($res === false) {
            show_error($client->getErrMsg());
        }
        var_dump($res);exit;

    }

    public function test()
    {
        var_dump(!($a = 0));exit;
    }
}
