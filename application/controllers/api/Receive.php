<?php

require_once APPCORE_PATH . 'ApiController.php';

class Receive extends ApiController
{
    public function index()
    {
        $client_key = $this->post('secret_key');
        $project    = $this->post('project');

        $server_key = load_config('upload.server.secret_key');
        if (!is_string($client_key) || $client_key != $server_key) {
            $this->error('请设置正确的密钥');
        }
        $this->error(1);
        $allow_projects = load_config('upload.server.allow_projects');
        if (!is_string($project) || !in_array($project, $allow_projects)) {
            $this->error('请指定一个允许上传的项目名');
        }
    }

    public function test()
    {
        // sleep(2);
        $this->success(['test' => 'success']);
    }
}
