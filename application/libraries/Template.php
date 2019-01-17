<?php

/**
 * 模板解析类，实现模板继承功能，而不需要引入像Smarty那样的重模板引擎
 */
class Template
{
    // 左右限定符
    public $left_delimiter  = '{';
    public $right_delimiter = '}';

    // 模板文件目录
    public $tpl_path;

    // 文件解析后的存放目录
    public $parse_path;

    public function __construct($tpl_path = '', $parse_path = '')
    {
        $this->tpl_path   = $tpl_path ? $tpl_path : VIEWPATH . 'layout' . DS;
        $this->parse_path = $parse_path ? $parse_path : APPPATH . 'views_compile' . DS;

        if (!is_dir($this->tpl_path) && !mkdir($this->tpl_path, DIR_WRITE_MODE, true)) {
            show_error("Unable to create the layout directory: {$this->tpl_path}");
        }
        if (!is_dir($this->parse_path) && !mkdir($this->parse_path, DIR_WRITE_MODE, true)) {
            show_error("Unable to create the compile directory: {$this->parse_path}");
        }
    }

    /**
     * 解析视图
     *
     * @param  string $path 视图文件的路径
     * @return string       视图解析后存放的路径
     */
    public function parseFile($path)
    {
        if (!file_exists($path)) {
            show_error('Unable to load the requested file: ' . $path);
        }

        $content = file_get_contents($path);

        // 如果是PJAX请求，不使用布局
        if (isset($_SERVER['HTTP_X_PJAX'])) {
            // 获取content
            $pattern = "#{$this->left_delimiter}block\s*name=\s*'?\"?content'?\"?\s*{$this->right_delimiter}(.*?){$this->left_delimiter}/block{$this->right_delimiter}#is";
            preg_match($pattern, $content, $matches);
            $center = empty($matches[1]) ? '' : $matches[1];

            // 获取footer
            $pattern = "#{$this->left_delimiter}block\s*name=\s*'?\"?footer'?\"?\s*{$this->right_delimiter}(.*?){$this->left_delimiter}/block{$this->right_delimiter}#is";
            preg_match($pattern, $content, $matches);
            $footer = empty($matches[1]) ? '' : $matches[1];

            $file = str_replace('.php', '_pjax.php', str_replace(VIEWPATH, '', $path));
            $path = $this->parse_path . $file;
            $this->saveFile($path, $center . $footer);

            return $path;
        }

        $extends_pattern = "/{$this->left_delimiter}extends\s*file\s*=\s*'?\"?(.+?)'?\"?\s*{$this->right_delimiter}/is";

        while (true) {
            $find = preg_match($extends_pattern, $content, $matches);
            if (!$find) {
                // 没有找到继承标签，保存文件并退出循环
                $path = $this->parse_path . str_replace(VIEWPATH, '', $path);
                $this->saveFile($path, $content);
                break;
            }

            // 模板文件的路径
            $layout = $this->tpl_path . $matches[1];
            $layout .= strpos($matches[1], '.') !== false ?: '.php';

            // 获取模板文件中的所有block
            $tpl_content       = file_get_contents($layout);
            $tpl_block_pattern = "/{$this->left_delimiter}block\s*name\s*=\s*'?\"?(.+?)'?\"?\s*{$this->right_delimiter}[^{]*{$this->left_delimiter}\/block{$this->right_delimiter}/is";
            $find              = preg_match_all($tpl_block_pattern, $tpl_content, $tpl_blocks);
            if ($find) {
                // 用视图的block替换模板的block
                foreach ($tpl_blocks[0] as $key => $tpl_block) {
                    $block_name         = $tpl_blocks[1][$key];
                    $view_block_pattern = "/{$this->left_delimiter}block\s*name\s*=\s*'?\"?{$block_name}'?\"?[^}]*{$this->right_delimiter}.*?{$this->left_delimiter}\/block{$this->right_delimiter}/is";
                    $find               = preg_match($view_block_pattern, $content, $view_blocks);
                    if (!$find) {
                        continue;
                    }

                    $tpl_content = str_replace($tpl_block, $view_blocks[0], $tpl_content);
                }
            }

            // 清除未匹配到的block
            $unmatch_block_pattern = "/{$this->left_delimiter}\s*\/?block[^{$this->right_delimiter}]*{$this->right_delimiter}/is";
            $content               = preg_replace($unmatch_block_pattern, '', $tpl_content);
        }

        // 替换短标签输出
        $content = str_replace('<?=', '<?php echo ', $content);

        return $path;
    }

    /**
     * 保存文件
     *
     * @param  string $path    文件路径
     * @param  string $content 内容
     */
    protected function saveFile($path, $content)
    {
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        if (!is_dir($dir) && !mkdir($dir, DIR_WRITE_MODE, true)) {
            show_error("Unable to create the compile directory: {$dir}");
        }

        $fp = fopen($path, FOPEN_WRITE_CREATE_DESTRUCTIVE);
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
        } else {
            show_error("Unable to create the compile file: {$path}");
        }
    }
}
