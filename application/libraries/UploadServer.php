<?php

class UploadServer
{
    protected $CI;
    /**
     * 必要参数，密钥、允许上传的项目（决定存放路径）
     */
    protected $secret_key = '';
    protected $projects   = 'oa';

    /**
     * 上传文件允许的类型、大小（默认10M）
     */
    protected $allow_type = [];
    protected $allow_size = 10485760;

    /**
     * 上传文件集合
     */
    protected $files = [];

    /**
     * 错误码、错误信息
     */
    const ERR_SERVER_NEED_SECRET_KEY = 10000;
    const ERR_CLIENT_NEED_SECRET_KEY = 10000;
    const ERR_WRONG_SECRET_KEY       = 10000;
    const ERR_NEED_PROJECT           = 10000;
    const ERR_PROJECT_NOT_ALLOW      = 10000;

    const ERR_PHP_VERSION_NOT_ALLOW = 10000;
    const ERR_FILES_EMPTY           = 10001;
    const ERR_FILE_NOT_FOUND        = 10002;
    const ERR_UNREADABLE            = 10003;
    const ERR_FAIL_TO_GET_MIME_TYPE = 10004;
    const ERR_MIME_TYPE_NOT_ALLOW   = 10005;
    const ERR_OVERSIZE              = 10006;
    const ERR_NEED_SECRET_KEY       = 10007;
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
        $this->CI = &get_instance();

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
        if (!empty($config['secret_key']) && is_string($config['secret_key'])) {
            $this->params['secret_key'] = $config['secret_key'];
        }

        if (!empty($config['projects']) && is_array($config['projects'])) {
            $this->params['projects'] = $config['projects'];
        }

        if (isset($config['allow_type']) && is_array($config['allow_type'])) {
            $this->allow_type = array_unique(array_merge($this->allow_type, $config['allow_type']));
        }

        if (isset($config['allow_size']) && intval($config['allow_size']) > 0) {
            $this->allow_size = intval($config['allow_size']);
        }
    }

    /**
     * 保存上传文件
     *
     * @return bool
     */
    public function save()
    {
        $this->getUploadFiles();

        if (!$this->checkError()) {
            return false;
        }

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
        if (!$this->secret_key) {
            $this->err_code = self::ERR_SERVER_NEED_SECRET_KEY;
            return false;
        }

        // 校验密钥
        $secret_key = $this->CI->input->post('secret_key', true);
        if (!$secret_key) {
            $this->err_code = self::ERR_CLIENT_NEED_SECRET_KEY;
            return false;
        }
        if ($this->secret_key != $secret_key) {
            $this->err_code = self::ERR_WRONG_SECRET_KEY;
            return false;
        }

        // 校验项目
        $project = $this->CI->input->post('project', true);
        if (!$project) {
            $this->err_code = self::ERR_NEED_PROJECT;
            return false;
        }
        if (!in_array($project, $this->projects)) {
            $this->err_code = self::ERR_PROJECT_NOT_ALLOW;
            return false;
        }

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
            if (!in_array($mime, $this->allow_type)) {
                $this->log(json(['file' => $file_path, 'mime' => $mime, 'allow_type' => $this->allow_type]));
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
}
