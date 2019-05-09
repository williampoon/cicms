<?php

class MyLog extends CI_Log
{
    protected $_file_ext = 'log';

    protected $_levels = [
        'EMERG'  => 1,  // 严重错误: 导致系统崩溃无法使用
        'ALERT'  => 2,  // 警戒性错误: 必须被立即修改的错误
        'CRIT'   => 3,  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
        'ERROR'  => 4,  // 一般错误: 一般性错误
        'WARN'   => 5,  // 警告性错误: 需要发出警告的错误
        'NOTIC'  => 6,  // 通知: 程序可以运行但是还不够完美的错误
        'DEBUG'  => 7,  // 调试: 调试信息
        'INFO'   => 8,  // 信息: 程序输出信息
        'SQL'    => 9,  // SQL：SQL语句 注意只在调试模式开启时有效
        'ACCESS' => 10, // 访问日志
    ];
    
    /*const DEBUG     = 100;
    const INFO      = 200;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500;
    const ALERT     = 550;
    const EMERGENCY = 600;

    protected $_levels = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];*/

    public function __construct()
    {
        parent::__construct();
    }

    public function write_log($level, $msg, $log_file = '')
    {
        if ($this->_enabled === false) {
            return false;
        }

        $level = strtoupper($level);

        if ((!isset($this->_levels[$level]) || ($this->_levels[$level] > $this->_threshold))
            && !isset($this->_threshold_array[$this->_levels[$level]])) {
            return false;
        }

        $log_file = is_string($log_file) && $log_file ? $log_file : $level;
        $log_path = $this->_log_path . DS . date('Ymd') . DS;
        if (!is_dir($log_path)) {
            mkdir($log_path, DIR_WRITE_MODE, true);
        }
        $filepath = $log_path . strtolower($log_file) . '.' . $this->_file_ext;
        $message  = '';

        if (!file_exists($filepath)) {
            $newfile = true;
            // Only add protection to php files
            if ($this->_file_ext === 'php') {
                $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
            }
        }

        if (!$fp = @fopen($filepath, 'ab')) {
            return false;
        }

        flock($fp, LOCK_EX);

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->_date_fmt, 'u') !== false) {
            $microtime_full  = microtime(true);
            $microtime_short = sprintf('%06d', ($microtime_full - floor($microtime_full)) * 1000000);
            $date            = new DateTime(date('Y-m-d H:i:s.' . $microtime_short, $microtime_full));
            $date            = $date->format($this->_date_fmt);
        } else {
            $date = date($this->_date_fmt);
        }

        $message .= $this->_format_line($level, $date, $msg);

        for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result) {
            if (($result = fwrite($fp, self::substr($message, $written))) === false) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === true) {
            chmod($filepath, $this->_file_permissions);
        }

        return is_int($result);
    }

    protected function _format_line($level, $date, $message)
    {
        return json_encode([
            'level'   => $level,
            'time'    => $date,
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
