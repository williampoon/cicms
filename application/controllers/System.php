<?php

class System extends MyController
{
    public function __construct()
    {
        parent::__construct();

        $this->system = $this->loadModel('SystemModel');
    }

    public function index()
    {
        $this->display();
    }

    public function show()
    {
        $draw   = intval($this->get('draw'));
        $limit  = intval($this->get('length'));
        $offset = intval($this->get('start'));

        $result = $this->system->orderBy('id desc')->dt_paginate($limit, $offset, $draw);

        return $this->ajaxSuccess($result);
    }

    public function add()
    {
        $name = trim($this->post('name'));
        $url  = trim($this->post('addr'));
        if (!$name || !$url) {
            return $this->ajaxError('请填写系统名称和地址');
        }

        if ($this->system->where('name', $name)->or_where('url', $url)->fetchOne()) {
            return $this->ajaxError('该名称或地址已存在');
        }

        if (!$this->system->insert(['name' => $name, 'url' => $url])) {
            return $this->ajaxError('新增失败');
        }

        return $this->ajaxSuccess();
    }

    public function update()
    {
        $id   = intval($this->post('id'));
        $name = trim($this->post('name'));
        $url  = trim($this->post('addr'));
        if (!$id) {
            return $this->ajaxError('参数错误');
        }
        if (!$name || !$url) {
            return $this->ajaxError('请填写系统名称和地址');
        }

        $system = $this->system->where('id', $id)->fetchOne();
        if (!$system) {
            return $this->ajaxError('该系统不存在');
        }

        if ($this->system->where('id !=', $id)
            ->group_start()->where('name', $name)->or_where('url', $url)->group_end()
            ->fetchOne()) {
            return $this->ajaxError('该名称或地址已存在');
        }

        $update = [];
        if ($name != $system['name']) {
            $update['name'] = $name;
        }
        if ($url != $system['url']) {
            $update['url'] = $url;
        }
        // 没有修改就直接返回
        if (!$update) {
            return $this->ajaxSuccess();
        }

        if (!$this->system->where('id', $id)->update($update)) {
            return $this->ajaxError('新增失败');
        }

        return $this->ajaxSuccess();
    }

    public function delete()
    {
        $id = intval($this->post('id'));

        if ($this->system->delete(['id' => $id])) {
            return $this->ajaxError('删除失败');
        }

        return $this->ajaxSuccess();
    }
}
