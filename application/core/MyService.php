<?php

/**
 * 服务层基类
 */
class MyService
{
    protected $serviceName = 'service';

    protected $timeout = 6;

    protected $errCode = 0;
    protected $errMsg  = '';
    protected $data    = [];

    public function get($url, $data = [], $options = [])
    {
        $mark = strpos($url, '?') === false ? '?' : '&';
        $url  = rtrim($url, '/') . $mark . http_build_query($data);

        $options[CURLOPT_CUSTOMREQUEST] = 'GET';

        return $this->request($url, $options);
    }

    public function post($url, $data = [], $options = [])
    {
        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        $options[CURLOPT_POSTFIELDS]    = $data;

        return $this->request($url, $options);
    }

    protected function request($url, $options = [])
    {
        // 初始化cURL会话
        $ch = curl_init($url);

        // 设置cURL选项
        $default_options = [
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER         => 1,
            CURLINFO_HEADER_OUT    => 1,
        ];
        curl_setopt_array($ch, $default_options);
        curl_setopt_array($ch, $options);

        // 不校验https证书
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) === 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        // 执行cURL会话
        $response = curl_exec($ch);

        // 判断错误
        if (curl_errno($ch)) {
            $this->errMsg = curl_errno($ch) . ': ' . curl_error($ch);
            return false;
        }

        // 请求上下文，响应头和响应体
        $ctx                          = curl_getinfo($ch);
        $http_code                    = $ctx['http_code'];
        $ctx['request_body']          = array_get($options, CURLOPT_POSTFIELDS, '');
        list($res_headers, $res_body) = explode("\r\n\r\n", $response);
        $ctx['response_header']       = $res_headers;
        $ctx['response_body']         = $http_code === 404 ? '' : $res_body;

        // http状态码不是200
        if ($http_code !== 200) {
            $this->log('error', $ctx);
            return false;
        }

        $this->log('info', $ctx);

        // 关闭cURL会话
        curl_close($ch);

        return $response;
    }

    /**
     * 设置错误信息
     *
     * @param integer   $errCode    错误码
     * @param string    $errmsg     错误消息
     * @param string    $data       数据
     */
    protected function setErrInfo($errCode, $errMsg = '', $data = [])
    {
        $this->setErrCode($errCode);
        $this->setErrMsg($errMsg);
        $this->setData($data);
    }

    /**
     * 设置错误码
     *
     * @param integer $errCode 错误码
     */
    protected function setErrCode($errCode = 0)
    {
        $this->errCode = $errCode;
    }

    /**
     * 设置错误消息
     *
     * @param string $errMsg 错误消息
     */
    protected function setErrMsg($errMsg = '')
    {
        $this->errMsg = $errMsg;
    }

    protected function setData($data = [])
    {
        $this->data = $data;
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

    public function getData()
    {
        return $this->data;
    }

    protected function log($level = 'error', $message = '')
    {
        log_message($level, $message, $this->serviceName);
    }
}
