<?php

/**
 * 服务层基类
 */
class MyService
{
    protected $mh;
    protected $timeout = 6;
    protected $responses;

    protected $errCode = 0;
    protected $errMsg  = '';

    /**
     * 魔术方法 __get
     *
     * 允许services像controllers那样来访问CI已加载的类
     *
     * @param   string  $key
     */
    public function __get($key)
    {
        return get_instance()->$key;
    }

    public function __construct()
    {
        // 创建批处理cURL句柄
        $this->mh = curl_multi_init();
    }

    public function get($url, $options = [])
    {
        $ch      = curl_init($url);
        $options = array_merge([
            CURLOPT_RETURNTRANSFER => 1,
        ], $options);
        curl_setopt_array($ch, $options);

        return $this->addUrl($ch);
    }

    // public function get() {}

    public function post($url)
    {
        // 初始化cURL会话
        $ch = curl_init($url);

        // 设置cURL选项
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        // 执行cURL会话
        $response = curl_exec($ch);

        // 判断错误
        if (curl_errno($ch)) {
            $this->errMsg = curl_errno($ch) . ': ' . curl_error($ch);
            return false;
        } else {
            $info      = curl_getinfo($ch);
            $http_code = $info['http_code'];
            if ($http_code != 200) {
                $info['response'] = $response;
                $this->log(json($info));
                $response     = strip_tags($response);
                $this->errMsg = "Upload failed, http code is {$http_code}, response is {$response}";
                return false;
            }
        }

        // 关闭cURL会话
        curl_close($ch);

        return $response;
    }

    // public function post($url, $options = []) {}

    public function addUrl($ch)
    {
        return curl_multi_add_handle($this->mh, $ch);
    }

    /**
     * 设置错误信息
     *
     * @param integer   $errCode    错误码
     * @param string    $errmsg     错误消息
     */
    public function setErrInfo($errCode, $errMsg)
    {
        $this->setErrCode($errCode);
        $this->setErrMsg($errMsg);
    }

    /**
     * 设置错误码
     *
     * @param integer $errCode 错误码
     */
    public function setErrCode($errCode = 0)
    {
        $this->errCode = $errCode;
    }

    /**
     * 设置错误消息
     *
     * @param string $errMsg 错误消息
     */
    public function setErrMsg($errMsg = '')
    {
        $this->errMsg = $errMsg;
    }

    /**
     * 获取错误码
     *
     * @return integer
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * 获取错误消息
     *
     * @return string
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }

    public function __destruct()
    {
        curl_multi_close($this->mh);
    }
}
