<?php

class CliController extends MyController
{
    public function __construct()
    {
        if (!is_cli()) {
            show_404();
        }
    }
}
