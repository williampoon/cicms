<?php

class Node extends MyController
{
    public function index()
    {
        $this->display();
    }

    public function tree()
    {
        $data = model('Node')->orderBy('sort desc')->fetchAll();
        $tree = array2tree($data);
        tree2array($tree, $nodes);

        return $this->ajaxSuccess($nodes);
    }

    public function add()
    {
        $input = $this->post();

        $rules = [
            [
                'field' => 'pid',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'PID必填',
                ],
            ],
            ['field' => 'name', 'rules' => 'required|max_length[255]'],
            ['field' => 'url', 'rules' => 'required|max_length[255]'],
            ['field' => 'icon', 'rules' => 'max_length[255]'],
            ['field' => 'status', 'rules' => 'integer'],
            ['field' => 'sort', 'rules' => 'integer'],
        ];
        $validator = library('form_validation');
        $validator->set_data($input);
        $validator->set_rules($rules);
        if (!$validator->run()) {
            return $this->error(current($validator->error_array()));
        }

        $model = model('Node');
        $data  = $model->where('url', $input['url'])->fetchOne();
        if ($data) {
            return $this->error("URL: {$input['url']} 已经存在");
        }

        $res = $model->insert($input);
        if (!$res) {
            return $this->error('添加失败，请稍后再试');
        }

        return $this->ajaxSuccess();
    }

    public function edit()
    {
        $input = $this->post();

        $rules = [
            ['field' => 'id', 'rules' => 'required|integer'],
            ['field' => 'pid', 'rules' => 'integer'],
            ['field' => 'name', 'rules' => 'required|max_length[255]'],
            ['field' => 'url', 'rules' => 'required|max_length[255]'],
            ['field' => 'icon', 'rules' => 'max_length[255]'],
            ['field' => 'status', 'rules' => 'integer'],
            ['field' => 'sort', 'rules' => 'integer'],
        ];
        $validator = library('form_validation');
        $validator->set_data($input);
        $validator->set_rules($rules);
        if (!$validator->run()) {
            return $this->error(current($validator->error_array()));
        }

        $id = $input['id'];
        unset($input['id']);
        if (isset($input['pid']) && $input['pid'] == $id) {
            return $this->error('不能成为自己的父菜单');
        }

        $model = model('Node');
        $data  = $model->fetchOne($id);
        if (!$data) {
            return $this->error('该菜单不存在');
        }
        $data = $model->where(['id !=' => $id, 'url' => $input['url']])->fetchOne();
        if ($data) {
            return $this->error("URL: {$input['url']} 已经存在");
        }

        $res = $model->where('id', $id)->update($input);
        if (!$res) {
            return $this->error('更新失败，请稍后再试');
        }

        return $this->ajaxSuccess();
    }
}
