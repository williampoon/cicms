<?php

require_once APPCORE_PATH . 'ApiController.php';

class User extends ApiController
{
    public function index()
    {
        $page     = $this->input->get('page');
        $pageSize = $this->input->get('pageSize');

        $data = [];
        for ($i = 0; $i < $pageSize; ++$i) {
            $id     = ($page - 1) * $pageSize + $i + 1;
            $data[] = [
                'id'     => $id,
                'email'  => md5($id) . '@qq.com',
                'name'   => md5($id),
                'role'   => $id % 8 == 0 ? '超级管理员' : '管理员',
                'status' => $id % 8 > 1 ? '正常' : '离职',
            ];
        }

        $res = [
            'total' => 100,
            'data'  => $data,
        ];

        return $this->ajaxSuccess($res);
    }

    public function create() {}

    public function store() {}

    public function show() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}
}
