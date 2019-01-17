<?php

class UserLogic extends MyLogic
{
    public function find_by_id($uid)
    {
        return [
            'uid'  => $uid,
            'name' => md5($uid),
        ];
    }
}
