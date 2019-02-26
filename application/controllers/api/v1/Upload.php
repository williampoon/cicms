<?php

require_once APPCORE_PATH . 'ApiController.php';

class Upload extends ApiController
{
    public function index()
    {
        $token = $this->post('token');
        if (!$token) {
            return $this->ajaxError('参数错误');
        }

        $logic = logic('Upload');
        $logic->upload();
    }

    public function token()
    {
        $app_id     = $this->get('app_id');
        $app_secret = $this->get('app_secret');

        if (!$app_id || !$app_secret) {
            return $this->ajaxError('参数错误');
        }
        $model = model('System');
        $row   = $model->where('app_id', $app_id)->where('app_secret', $app_secret)->fetchOne();
        if (!$row) {
            return $this->ajaxError('未授权系统');
        }
        if (!$row['slots']) {
            return $this->ajaxError('未分配槽点');
        }

        $slots      = explode('|', $row['slots']);
        $slot       = $slots[mt_rand(0, count($slots) - 1)];
        $token      = md5($app_id . $app_secret . uniqid($slot, true));
        $uri_string = $this->uri->uri_string;
        $upload_url = $this->input->server('REQUEST_SCHEME') . '://' . $this->input->server('SERVER_NAME') . DS . $slot . DS . substr($uri_string, 0, strrpos($uri_string, '/')) . DS . 'index';

        // save to redis
        $redis = library('MyRedis');
        $redis_key = 'upload_token_' . $token;
        $redis->hMSet($redis_key, ['app_id' => $app_id]);
        $redis->setTimeout($redis_key, 600);

        $res = [
            'token'      => $token,
            'slot'       => $slot,
            'upload_url' => $upload_url,
        ];

        return $this->ajaxSuccess($res);
    }
}
