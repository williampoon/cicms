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

    public function leftMenu()
    {
        $pid = $this->get('pid');
        if ($pid <= 0) {
            echo '';
            return ;
        }
        $all_nodes = logic('Node')->menuTree();
        if (!isset(current($all_nodes)['children'])) {
            echo '';
            return ;
        }
        $top_nodes = current($all_nodes)['children'];
        if (!isset($top_nodes[$pid]['children'])) {
            echo '';
            return ;
        }

        $left_nodes = $top_nodes[$pid]['children'];

        $this->display(['left_nodes' => $left_nodes]);
    }

    public function add()
    {
        $input = $this->post();

        $rules = [
            [
                'field'  => 'pid',
                'rules'  => 'required|integer',
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
            return $this->ajaxError(current($validator->error_array()));
        }

        $model = model('Node');
        $data  = $model->where('url', $input['url'])->fetchOne();
        if ($data) {
            return $this->ajaxError("URL: {$input['url']} 已经存在");
        }

        $res = $model->insert($input);
        if (!$res) {
            return $this->ajaxError('添加失败，请稍后再试');
        }

        logic('Node')->rmAllNodesCache();

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
            return $this->ajaxError(current($validator->error_array()));
        }

        $id = $input['id'];
        unset($input['id']);
        if (isset($input['pid']) && $input['pid'] == $id) {
            return $this->ajaxError('不能成为自己的父菜单');
        }

        $model = model('Node');
        $data  = $model->fetchOne($id);
        if (!$data) {
            return $this->ajaxError('该菜单不存在');
        }
        $data = $model->where(['id !=' => $id, 'url' => $input['url']])->fetchOne();
        if ($data) {
            return $this->ajaxError("URL: {$input['url']} 已经存在");
        }

        $res = $model->where('id', $id)->update($input);
        if (!$res) {
            return $this->ajaxError('更新失败，请稍后再试');
        }

        logic('Node')->rmAllNodesCache();

        return $this->ajaxSuccess();
    }

    public function del()
    {
        $id = intval($this->post('id'));
        if ($id <= 0) {
            return $this->ajaxError('无效ID');
        }

        $model = model('Node');
        $node = $model->where(['id' => $id])->fetchOne();
        if (!$node) {
            return $this->ajaxError('菜单节点已删除');
        }
        $children = $model->where(['pid' => $id])->fetchOne();
        if ($children) {
            return $this->ajaxError('该菜单存在子菜单，无法删除');
        }
        $result = $model->where(['id' => $id])->delete();
        if (!$result) {
            return $this->ajaxError('删除失败，请稍后重试');
        }

        logic('Node')->rmAllNodesCache();

        return $this->ajaxSuccess();
    }
}
