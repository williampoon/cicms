<?php

/**
 * 文件上传客户端
 */
class UploadClient
{
    /**
     * 目标主机、端口、超时时间
     */
    protected $host    = '';
    protected $port    = 80;
    protected $timeout = 5;

    /**
     * 上传参数，密钥、所属项目
     */
    protected $secret_key = '';
    protected $project    = 'oa';

    /**
     * 上传文件允许的类型、大小（默认10M）
     */
    protected $allow_types = [];
    protected $allow_size  = 10485760;

    /**
     * 上传文件集合
     */
    protected $files = [];

    /**
     * 错误码、错误信息
     */
    const ERR_PHP_VERSION_NOT_ALLOW = 40000;
    const ERR_FILES_EMPTY           = 40001;
    const ERR_FILE_NOT_FOUND        = 40002;
    const ERR_UNREADABLE            = 40003;
    const ERR_FAIL_TO_GET_MIME_TYPE = 40004;
    const ERR_MIME_TYPE_NOT_ALLOW   = 40005;
    const ERR_OVERSIZE              = 40006;
    const ERR_NEED_SECRET_KEY       = 40007;
    protected $err_code             = -1;
    protected $err_msg              = '文件服务器错误';
    protected $err_map              = [
        /* 内置错误 */
        // 上传的文件超过php.ini中upload_max_filesize选项限制的值
        UPLOAD_ERR_INI_SIZE             => '文件大小超过服务端限制',
        // 上传文件的大小超过HTML表单中MAX_FILE_SIZE选项指定的值
        UPLOAD_ERR_FORM_SIZE            => '文件大小超过客户端限制',
        UPLOAD_ERR_PARTIAL              => '文件只有部分被上传',
        UPLOAD_ERR_NO_FILE              => '没有文件被上传',
        UPLOAD_ERR_NO_TMP_DIR           => '找不到临时文件夹',
        UPLOAD_ERR_CANT_WRITE           => '文件写入失败',
        /* 自定义错误 */
        self::ERR_PHP_VERSION_NOT_ALLOW => 'PHP版本要求5.5.0以上',
        self::ERR_FILES_EMPTY           => '没有文件需要被上传',
        self::ERR_FILE_NOT_FOUND        => '找不到上传文件',
        self::ERR_UNREADABLE            => '无法读取上传文件',
        self::ERR_FAIL_TO_GET_MIME_TYPE => '无法获取上传文件的MIME类型',
        self::ERR_MIME_TYPE_NOT_ALLOW   => '文件类型非法',
        self::ERR_OVERSIZE              => '文件大小超过限制',
        self::ERR_NEED_SECRET_KEY       => '请配置上传密钥',
    ];

    public function __construct(array $config = [])
    {
        if ($config) {
            $this->setConfig($config);
        }
    }

    /**
     * 设置配置
     *
     * @param array $config 配置数组
     */
    public function setConfig(array $config)
    {
        if (!empty($config['host']) && is_string($config['host'])) {
            $this->host = $config['host'];
        }

        if (!empty($config['port'])) {
            $this->port = $config['port'];
        }

        if (!empty($config['timeout'])) {
            $this->timeout = $config['timeout'];
        }

        if (!empty($config['secret_key']) && is_string($config['secret_key'])) {
            $this->secret_key = $config['secret_key'];
        }

        if (!empty($config['project']) && is_string($config['project'])) {
            $this->project = $config['project'];
        }

        if (isset($config['allow_types']) && is_array($config['allow_types'])) {
            $this->allow_types = array_unique(array_merge($this->allow_types, $config['allow_types']));
        }

        if (isset($config['allow_size']) && intval($config['allow_size']) > 0) {
            $this->allow_size = intval($config['allow_size']);
        }
    }

    /**
     * 添加本地文件
     *
     * @param string $file_path 文件完整路径
     * @param string $file_name 文件名
     */
    public function addFile($file_path, $file_name = '')
    {
        $this->files[] = [
            'name' => strval($file_name),
            'path' => strval($file_path),
        ];
    }

    /**
     * /**
     * 上传
     *
     * @param  array  $params 额外的POST参数
     * @return bool|response
     */
    public function upload(array $params = [])
    {
        // php5.5.0开始支持CURLFile方式
        if (!class_exists('\CURLFile')) {
            $this->err_code = self::ERR_PHP_VERSION_NOT_ALLOW;
            return false;
        }

        $this->getUploadFiles();

        if (!$this->checkError()) {
            return false;
        }

        return $this->doUpload($params);
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getErrMsg()
    {
        return isset($this->err_map[$this->err_code])
            ? $this->err_map[$this->err_code]
            : $this->err_msg;
    }

    /**
     * 获取客户端上传的文件
     */
    protected function getUploadFiles()
    {
        foreach ($_FILES as $file) {
            if (is_array($file['name'])) {
                /**
                 * 上传方式：
                 * <input type="file" name="file[]" />
                 * <input type="file" name="file[]" />
                 */
                for ($i = 0; $i < count($file['name']); ++$i) {
                    if (empty($file['tmp_name'][$i])) {
                        continue;
                    }

                    $this->files[] = [
                        'name'  => $file['name'][$i],
                        'path'  => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                    ];
                }
            } else {
                /**
                 * 上传方式：
                 * <input type="file" name="file1" />
                 * <input type="file" name="file2" />
                 */
                if (empty($file['tmp_name'])) {
                    continue;
                }
                $this->files[] = [
                    'name'  => $file['name'],
                    'path'  => $file['tmp_name'],
                    'error' => $file['error'],
                ];
            }
        }
    }

    /**
     * 检查错误
     *
     * @return bool
     */
    protected function checkError()
    {
        if (!$this->files) {
            $this->err_code = self::ERR_FILES_EMPTY;
            return false;
        }

        foreach ($this->files as $i => $file) {
            // 检查内置错误
            if (!empty($file['error'])) {
                $this->err_code = $file['error'];
                return false;
            }

            // 文件是否存在
            $file_path = realpath($file['path']);
            if (!file_exists($file_path)) {
                $this->err_code = self::ERR_FILE_NOT_FOUND;
                return false;
            }

            // 文件是否可读
            if (!is_readable($file_path)) {
                $this->err_code = self::ERR_UNREADABLE;
                return false;
            }

            // 获取文件类型
            $mime = $this->getMimeType($file_path);
            if (!$mime) {
                $this->err_code = self::ERR_FAIL_TO_GET_MIME_TYPE;
                return false;
            }
            $this->files[$i]['mime'] = $mime;

            // 检查文件类型
            if (!in_array($mime, $this->allow_types)) {
                $this->log(json(['file' => $file_path, 'mime' => $mime, 'allow_types' => $this->allow_types]));
                $this->err_code = self::ERR_MIME_TYPE_NOT_ALLOW;
                return false;
            }

            // 检查文件大小
            if (filesize($file_path) > $this->allow_size) {
                $this->err_code = self::ERR_OVERSIZE;
                return false;
            }
        }

        return true;
    }

    /**
     * 获取文件的MIME类型
     *
     * @param  string $path 文件路径
     * @return string
     */
    protected function getMimeType($path)
    {
        $mime = '';

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if (!$finfo) {
                return '';
            }
            $mime = finfo_file($finfo, $path);
            if (!$mime) {
                return '';
            }

        } elseif (function_exists('mime_content_type')) {
            $mime = mime_content_type($path);

        } elseif (function_exists('shell_exec')) {
            $cmd = 'file -bi ' . escapeshellarg($path);
            $res = shell_exec($cmd);
            if (is_null($res)) {
                return '';
            }
            $arr_res = explode(';', $res);
            $mime    = current($arr_res);

        }

        return $mime;
    }

    /**
     * 执行上传
     *
     * @param  array  $params 额外的POST参数
     * @return false|response
     */
    protected function doUpload(array $params = [])
    {
        if (empty($this->secret_key)) {
            $this->err_code = self::ERR_NEED_SECRET_KEY;
            return false;
        }

        // 构造字段
        $fields = [];
        foreach ($this->files as $file) {
            $path = realpath($file['path']);
            $mime = $file['mime'];

            if (!empty($file['name'])) {
                $fields[$file['name']] = new \CURLFile($path, $mime);
            } else {
                $fields[] = new \CURLFile($path, $mime);
            }
        }
        $fields['secret_key'] = $this->secret_key;
        $fields['project']    = $this->project;
        if ($params) {
            $fields = array_merge($fields, $params);
        }

        // 发送请求
        $ch = curl_init($this->host);
        curl_setopt($ch, CURLOPT_PORT, $this->port);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        if (strlen($this->host) > 5 && strtolower(substr($this->host, 0, 5)) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->err_msg = curl_errno($ch) . ': ' . curl_error($ch);
            return false;
        } else {
            $info      = curl_getinfo($ch);
            $http_code = $info['http_code'];
            if ($http_code != 200) {
                $info['response'] = $response;
                $this->log(json($info));
                $response      = strip_tags($response);
                $this->err_msg = "Upload failed, http code is {$http_code}, response from server is {$response}";
                return false;
            }
        }
        curl_close($ch);

        return $response;
    }

    /**
     * 记录日志，单独封装方便替换
     *
     * @param  string $message 日志信息
     * @param  string $level   日志级别（error, debug, info）
     */
    protected function log($message = '', $level = 'error')
    {
        log_message($level, $message);
    }
}
