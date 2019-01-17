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

        $this->ajaxReturn($result);
    }

    public function add()
    {
        $name = trim($this->post('name'));
        $url  = trim($this->post('addr'));
        if (!$name || !$url) {
            $this->error('请填写系统名称和地址');
        }

        if ($this->system->where('name', $name)->or_where('url', $url)->fetchOne()) {
            $this->error('该名称或地址已存在');
        }

        if (!$this->system->insert(['name' => $name, 'url' => $url])) {
            $this->error('新增失败');
        }

        $this->success();
    }

    public function update()
    {
        $id   = intval($this->post('id'));
        $name = trim($this->post('name'));
        $url  = trim($this->post('addr'));
        if (!$id) {
            $this->error('参数错误');
        }
        if (!$name || !$url) {
            $this->error('请填写系统名称和地址');
        }

        $system = $this->system->where('id', $id)->fetchOne();
        if (!$system) {
            $this->error('该系统不存在');
        }

        if ($this->system->where('id !=', $id)
            ->group_start()->where('name', $name)->or_where('url', $url)->group_end()
            ->fetchOne()) {
            $this->error('该名称或地址已存在');
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
            $this->success();
        }

        if (!$this->system->where('id', $id)->update($update)) {
            $this->error('新增失败');
        }

        $this->success();
    }

    public function delete()
    {
        $id = intval($this->post('id'));

        if ($this->system->delete(['id' => $id])) {
            $this->error('删除失败');
        }

        $this->success();
    }
}
