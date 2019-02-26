<?php

require_once APPCORE_PATH . 'CliController.php';

class Socket extends CliController
{
    public function server() {
        $address = '127.0.0.1';
        $port = 8080;

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($socket, $address, $port);
        socket_listen($socket);

        while(1) {
            $client = socket_accept($socket);
            if ($client) {
                $msg = socket_read($client, 4096);
                var_dump($msg);
                socket_close($client);
            }
            usleep(100);
        }
    }

    public function client() {
        $address = '127.0.0.1';
        $port = 8080;
        $msg = 'hello, php socket';

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $address, $port);
        socket_write ($socket, $msg);
        socket_close($socket);
    }
}