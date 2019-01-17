<?php

class UserService extends MyService
{
    public function find_by_name($name)
    {
        return [
            'name' => $name,
            'time' => date('Y-m-d H:i:s'),
        ];
    }
}
