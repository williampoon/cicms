<?php

/**
 * 封装CI_Controller
 */
class MyController extends CI_Controller
{
    const SUCCESS_CODE = 0;     // 默认成功码
    const ERROR_CODE   = 50000; // 默认错误码

    protected $controller;
    protected $action;

    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(true);

        $this->controller = strtolower($this->router->class);
        $this->action     = strtolower($this->router->method);

        if (!$this->isAjax()) {
            $this->data['tree'] = Logic('Node')->menuTree();
        }

        // 缓存页面10分钟
        // $this->output->cache(10);

        // $data = model('NodeModel')->fetchAll();

        // var_dump($nodes);exit;

        /*$this->load->library('MySession');
        $this->session = $this->mysession->getSession(
            $this->input->ip_address(),
            $this->input->user_agent()
        );*/

        // $this->filter('accessLog');
    }

    /**
     * 过滤器方法
     *
     * @param  string $method 方法名
     * @param  array  $params 参数
     */
    protected function filter($method, $params = [])
    {
        if (!$params) {
            return call_user_func([$this, $method]);
        }
        // “包含”
        if (isset($params['only']) && in_array($this->action, $params['only'])) {
            return call_user_func([$this, $method]);
        }
        // “排除”
        if (isset($params['except']) && !in_array($this->action, $params['except'])) {
            return call_user_func([$this, $method]);
        }
    }

    /**
     * 用户认证
     */
    protected function auth()
    {
        $this->load->library('session');
        if ($this->session) {
            return true;
        }

        if ($this->isAjax()) {
            $this->error('请先登录');
        } else {
            $this->redirect('admin/login');
        }
    }

    /**
     * 重定向到目标URL
     */
    public function redirect($url)
    {
        $this->load->helper('url');
        redirect($url);
    }

    /**
     * 获取GET数据
     *
     * @param  string   $key        GET参数名
     * @param  mixed    $default    默认值
     * @return mixed
     */
    public function get($key = '', $default = null)
    {
        return $this->getInput('get', $key, $default);
    }

    /**
     * 获取POST数据
     *
     * @param  string   $key        POST参数名
     * @param  mixed    $default    默认值
     * @return mixed
     */
    public function post($key = '', $default = null)
    {
        return $this->getInput('post', $key, $default);
    }

    /**
     * 获取GET/POST数据
     *
     * @param  stringg  $method     方法
     * @param  string   $key        GET/POST参数名
     * @param  mixed    $default    默认值
     * @return mixed
     */
    public function getInput($method, $key = '', $default = null)
    {
        if (!$key) {
            $data = $this->input->$method(null, true);
        } else {
            $data = $this->input->$method($key, true);
        }

        return $data ? $data : $default;
    }

    /**
     * 展示视图
     *
     * @param  array  $data 向视图添加的动态数据
     */
    public function display(array $data = [])
    {
        return $this->load->view($this->controller . DS . $this->action, array_merge($data, $this->data));
    }

    /**
     * 是否GET请求
     *
     * @return boolean
     */
    public function isGet()
    {
        return $this->input->method() === 'get';
    }

    /**
     * 是否POST请求
     *
     * @return boolean
     */
    public function isPost()
    {
        return $this->input->method() === 'post';
    }

    /**
     * 是否AJAX请求
     *
     * @return boolean
     */
    public function isAjax()
    {
        return $this->input->is_ajax_request();
    }

    /**
     * 返回成功信息
     *
     * @param  array    $data    数据
     * @param  string   $message 消息
     * @param  integer  $code    业务状态码
     */
    protected function ajaxSuccess($data = [], $message = '', $code = self::SUCCESS_CODE)
    {
        $this->ajaxReturn([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => time(),
        ]);
    }

    /**
     * 返回错误信息
     *
     * @param  string   $message 消息
     * @param  integer  $code    业务状态码
     * @param  array    $data    数据
     */
    protected function ajaxError($message = '', $code = self::ERROR_CODE, $data = [])
    {
        $this->ajaxReturn([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => time(),
        ]);
    }

    /**
     * Ajax方式返回数据到客户端
     *
     * @param   mixed   $data           要返回的数据
     * @param   string  $type           AJAX返回数据格式
     * @param   int     $json_option    传递给json_encode的option参数
     * @return  void
     */
    protected function ajaxReturn($data, $type = 'JSON', $json_option = JSON_UNESCAPED_UNICODE)
    {
        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                $this->output
                    ->set_header('Content-Type:application/json; charset=utf-8')
                    ->set_output(json($data, $json_option | JSON_UNESCAPED_UNICODE));
                // ->_display();
                break;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                $handler = $this->get(C('VAR_JSONP_HANDLER'), C('DEFAULT_JSONP_HANDLER'));
                $this->output
                    ->set_header('Content-Type:application/json; charset=utf-8')
                    ->set_output($handler . '(' . json($data, $json_option) . ');');
                // ->_display();
                break;
            case 'XML':
                // 返回xml格式数据
                $this->load->helper('xml');
                $this->output
                    ->set_header('Content-Type:text/xml; charset=utf-8')
                    ->set_output(xml_convert($data));
                // ->_display();
                break;
        }
    }
}
