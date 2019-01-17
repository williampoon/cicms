<?php

class MyRedis
{
    protected $_redis = null;

    public function __construct()
    {
        $config = load_config('redis');

        $this->_redis = new Redis();
        if ($this->_redis->connect($config['host'], $config['port']) === false) {
            $msg = "Fail to connect Redis ({$config['host']}:{$config['port']})";
            log_message('error', $msg);
            show_error($msg);
        }
    }

    // 透明地调用redis的方法
    public function __call($method, $params)
    {
        try {
            return call_user_func_array([$this->_redis, $method], $params);

        } catch (\RedisException $e) {
            $log = [
                'raw_msg' => $e->getMessage(),
                'method'  => $method,
                'params'  => $params,
            ];
            log_message('error', json($log));

            show_error('Fail to connect Redis: ' . $e->getMessage());
        }
    }
}
