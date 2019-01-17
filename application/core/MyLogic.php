<?php

/**
 * 业务逻辑层基类
 */
class MyLogic
{
    protected $errCode; // 错误码
    protected $errMsg;  // 错误消息

    /**
     * 魔术方法 __get
     *
     * 允许logics像controllers那样来访问CI已加载的类
     *
     * @param   string  $key
     */
    public function __get($key)
    {
        return get_instance()->$key;
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
}
