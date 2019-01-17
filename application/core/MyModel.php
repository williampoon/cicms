<?php

class MyModel extends CI_Model
{
    // 对应的表名
    protected $table;

    public function __construct($db_config = 'default')
    {
        parent::__construct();

        // 连接数据库
        $this->load->database($db_config);
    }

    /**
     * 获取一条记录
     * @return [type] [description]
     */
    public function fetchOne($id = 0)
    {
        if ($id > 0) {
            $this->db->where('id', $id);
        }

        $query = $this->db->get($this->table, 1);

        return $this->resultArray($query, false);
    }

    public function fetchAll()
    {
        $query = $this->db->get($this->table);

        return $this->resultArray($query);
    }

    /**
     * [fetchByIds description]
     * @param  array  $ids    [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     */
    public function fetchByIds($ids = [], $fields = '*')
    {
        if (!$ids) {
            return [];
        }

        $this->db->select($fields)->where_in('id', $ids);
        $query = $this->db->get($this->table);

        return $this->resultArray($query);
    }

    public function limit($limit = 10, $offset = 0)
    {
        $this->db->limit($limit, $offset);

        return $this;
    }

    public function simplePaginate() {}

    /**
     * datatable分页方法
     * @param  [type] $limit  [description]
     * @param  [type] $offset [description]
     * @param  [type] $draw   [description]
     * @return [type]         [description]
     */
    public function dtPaginate($limit, $offset, $draw)
    {
        $data             = $this->limit($limit, $offset)->fetchAll();
        $records_filtered = $this->db->count_all_results($this->table);
        $records_total    = count($data);

        return [
            'draw'            => intval($draw),
            'data'            => $data,
            'recordsTotal'    => $records_total,
            'recordsFiltered' => $records_filtered,
        ];
    }

    public function orderBy($order_by = 'id desc')
    {
        $this->db->order_by($order_by);

        return $this;
    }

    /**
     * [fetchByVals description]
     * @param  array  $vals      [description]
     * @param  string $ref_field [description]
     * @param  string $fields    [description]
     * @return [type]            [description]
     */
    public function fetchByVals($vals = [], $ref_field = '', $fields = '*')
    {
        if (!$vals || !$ref_field) {
            return [];
        }

        $this->db->select($fields)->where_in($ref_field, $vals);
        $query = $this->db->get($this->table);

        return $this->resultArray($query);
    }

    /**
     * 记录SQL日志，并以数组形式返回结果
     * @param  [type]  $query     [description]
     * @param  boolean $fetch_one [description]
     * @return [type]             [description]
     */
    public function resultArray($query, $fetch_all = true)
    {
        // 记录SQL日志
        // $sql = $this->db->last_query();
        // $this->_logSql($sql);

        if ($fetch_all) {
            return $query->result_array();
        }

        return $query->row_array();
    }

    public function insert($data = [], $insert_batch = false)
    {
        if (!$data) {
            return false;
        }

        // 记录SQL日志
        $sql = $this->db->set($data)->get_compiled_insert($this->table, false);
        $this->_logSql($sql);

        if ($insert_batch) {
            return $this->db->insert_batch($this->table, $data);
        } else {
            return $this->db->insert($this->table, $data);
        }
    }

    public function delete($where = [])
    {
        // 记录SQL日志
        $sql = $this->db->where($where)->get_compiled_delete($this->table, false);
        $this->_logSql($sql);

        return $this->db->delete($this->table, $where);
    }

    public function update($data = [], $update_batch = false)
    {
        if (!$data) {
            return false;
        }

        // 记录SQL日志
        $sql = $this->db->set($data)->get_compiled_update($this->table, false);
        $this->_logSql($sql);

        if ($update_batch) {
            return $this->db->update_batch($this->table, $data);
        } else {
            return $this->db->update($this->table, $data);
        }
    }

    /**
     * 透明地调用db的方法
     * @param  [type] $method    [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($method, $arguments)
    {
        call_user_func_array([$this->db, $method], $arguments);

        return $this;
    }

    /**
     * 设置查询条件、字段
     * @param mixed     $where  查询条件
     * @param string    $fields 需要查询的字段
     */
    protected function _setConditions($where, $fields)
    {
        if (is_numeric($where)) {
            $this->db->where('id', $where);
        } elseif ($where) {
            $this->db->where($where);
        }

        if ($fields) {
            $this->db->select($fields);
        }
    }

    /**
     * 记录日志
     * @param  string   $sql    SQL语句
     */
    protected function _logSql($sql)
    {
        if (in_array(ENVIRONMENT, ['development', 'testing'], true)) {
            $sql = str_replace("\n", ' ', $sql);
            log_message('sql', $sql);
        }
    }
}
