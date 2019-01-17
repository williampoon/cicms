<?php

class NodeLogic extends MyLogic
{
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

    public function menuTree() {
        $data = model('Node')->where('status', 1)->orderBy('sort desc')->fetchAll();
        $tree = array2tree($data);

        return $tree;
    }
}
