<?php

class NodeLogic extends MyLogic
{
    const NODES_CACHE_KEY = 'all_nodes';

    public function validate($data)
    {
        $rules = [
            ['field' => 'pid', 'rules' => 'required|integer'],
            ['field' => 'name', 'rules' => 'required|max_length[255]'],
            ['field' => 'url', 'rules' => 'required|max_length[255]'],
            ['field' => 'icon', 'rules' => 'max_length[255]'],
            ['field' => 'status', 'rules' => 'integer'],
            ['field' => 'sort', 'rules' => 'integer'],
        ];
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            $this->setErrInfo(10001, $this->form_validation->error_array());
            return false;
        }

        return true;
    }

    // 左侧菜单
    public function leftMenu($left_nodes)
    {
        if (!is_array($left_nodes) && !$left_nodes) {
            return '';
        }

        $result = '';
        foreach ($left_nodes as $node) {
            $href  = empty($node['children']) ? $node['url'] : '#';
            $class = $node['icon'] ? $node['icon'] : 'fa fa-link';
            $result .= '<li class="treeview">'.
                '<a class="leaf-menu" data-url="' . $href . '">' .
                '<i class="' . $class . '"></i><span>' . $node['name'] . '</span>';
            if (!empty($node['children'])) {
                $result .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
            }
            $result .= '</a>';
            if (!empty($node['children'])) {
                $result .= $this->subtree($node['children']);
            }
            $result .= '</li>';
        }
        $result .= "<script>// 切换主内容" . 
            "$('.leaf-menu').click(function() {" .
            "let url = $(this).data('url');" . 
            "console.log(url);" .
            "});</script>";

        return $result;
    }

    // 左侧菜单子菜单
    public function subtree($nodes)
    {
        if (!is_array($nodes)) {
            return '';
        }

        $res = '<ul class="treeview-menu" style="display: none;">';
        foreach ($nodes as $node) {
            $name     = $node['name'];
            $url      = $node['url'];
            $icon     = $node['icon'] ? $node['icon'] : 'fa fa-link';
            $children = empty($node['children']) ? false : $node['children'];

            if (!$children) {
                $res .= '<li class="treeview"><a class="leaf-menu" data-url="' . $url . '"><i class="' . $icon . '"></i> ' . $name . '</a></li>';
            } else {
                $res .= '<li class="treeview">'
                . '<a href="#"><i class="' . $icon . '"></i> ' . $name
                . '<span class="pull-right-container">'
                . '<i class="fa fa-angle-left pull-right"></i>'
                . '</span>'
                . '</a>'
                . $this->subtree($children)
                . '</li>';
            }
        }
        $res .= '</ul>';

        return $res;
    }


    public function getAllNodes()
    {
        $this->load->driver('cache');
        $cache = $this->cache->file->get(self::NODES_CACHE_KEY);
        if ($cache) {
            return unjson($cache);
        }

        $data = model('Node')->where('status', 1)->orderBy('sort desc')->fetchAll();
        $this->cache->file->save(self::NODES_CACHE_KEY, json($data));

        return $data;
    }

    public function rmAllNodesCache()
    {
        $this->load->driver('cache');
        $this->cache->file->delete(self::NODES_CACHE_KEY);
    }

    public function menuTree()
    {
        $data = $this->getAllNodes();
        $tree = array2tree($data);

        return $tree;
    }
}
