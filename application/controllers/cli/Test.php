<?php

class Test extends CliController
{
    public function index()
    {
        echo 'cli test index' . PHP_EOL;
    }

    public function img() {
        var_dump($this);exit;
        $this->load->library('MyImage');
    }
}
