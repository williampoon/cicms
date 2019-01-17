<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->view('welcome_message');
    }

    /*
    HTTP方法    路径                控制器#动作          用途
    GET         /photos             photos#index        显示所有照片的列表
    GET         /photos/new         photos#new          返回用于新建照片的 HTML 表单
    POST        /photos             photos#create       新建照片
    GET         /photos/:id         photos#show         显示指定照片
    GET         /photos/:id/edit    photos#edit         返回用于修改照片的 HTML 表单
    PATCH/PUT   /photos/:id         photos#update       更新指定照片
    DELETE      /photos/:id         photos#destroy      删除指定照片
    */

    // 显示所有照片的列表
    // public function index() {}

    // 返回用于新建照片的 HTML 表单
    public function new() {}

    // 新建照片
    public function create() {}

    // 显示指定照片
    public function show() {}

    // 返回用于修改照片的 HTML 表单
    public function edit() {}

    // 更新指定照片
    public function update() {}

    // 删除指定照片
    public function destroy() {}
}
