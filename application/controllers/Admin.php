<?php

class Admin extends MyController
{
    public function index()
    {
        $info = [
            'system'   => exec('cat /etc/redhat-release'),
            'server'   => $_SERVER['SERVER_SOFTWARE'],
            'database' => exec('mysql --version'),
            'php'      => exec('php-fpm -v|head -1'),
        ];

        $p_node         = current($this->data['tree']);
        $top_nodes      = isset($p_node['children']) ? $p_node['children'] : [];
        $first_top_node = current($top_nodes);
        $left_nodes     = isset($first_top_node['children']) ? $first_top_node['children'] : [];

        return $this->display([
            'info'       => $info,
            'top_nodes'  => $top_nodes,
            'left_nodes' => $left_nodes,
        ]);
    }

    public function login()
    {
        if ($this->isGet()) {
            return $this->display([
                'js_file' => 'login',
            ]);
        }

        $account  = $this->post('account');
        $password = $this->post('password');
        if (!$account || !$password) {
            return $this->ajaxError('请填写用户名和密码');
        }

        // 校验用户
        $model = model('UserModel', 'user');
        if (!is_email($account)) {
            $model->where('account', $account);
        } else {
            $model->where('email', $account);
        }
        $user = $model->fetchOne();
        if (!$user || $user['passwd'] != md5($password)) {
            return $this->ajaxError('用户名或密码错误');
        }

        // 保存session
        $_SESSION['uid'] = $user['id'];

        return $this->ajaxSuccess();
    }

    public function logout()
    {
        unset($_SESSION['uid']);

        return $this->ajaxSuccess();
    }

    public function test()
    {
        // exit('a');
        $test = library('unit_test');
        $test->use_strict(true);
        // $this->load->library('unit_test');
        // var_dump($this->unit);exit;
        // $this->unit->run('1', 1, '类型测试');
        // $this->unit->run('1', true, '类型测试');
        $test->run(intval('1'), 1, '测试1');
        $test->run('1', true, '测试2');
        // var_dump($test->result());
        echo $test->report();
        // $this->display();
    }
}
