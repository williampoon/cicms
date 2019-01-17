<?php

class PostSystem
{
    protected $CI;

    public function __construct()
    {
        $this->CI = get_instance();
    }

    public function index()
    {
        // var_dump(byte2unit(memory_get_peak_usage()));exit;
    }
}
