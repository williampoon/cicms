<?php

class Auth extends ApiController
{
    public function login()
    {
        $this->returnSuccess([
            'token' => 'aaa',
            'menus' => [
                'path'      => '/auth',
                'icon'      => 'key',
                'name'      => 'auth',
                'title'     => '权限系统',
                'component' => 'Main',
                'children'  => [
                    [
                        'path'      => 'index',
                        'title'     => '权限管理',
                        'name'      => 'access_index',
                        'component' => '@/views/access/index.vue',
                    ],
                    [
                        'path'      => 'menu',
                        'title'     => '菜单管理',
                        'name'      => 'access_menu',
                        'component' => '@/views/access/menu.vue',
                    ],
                    [
                        'path'      => 'role',
                        'title'     => '角色管理',
                        'name'      => 'access_role',
                        'component' => '@/views/access/role.vue',
                    ],
                ],
            ],
        ]);
    }
}
