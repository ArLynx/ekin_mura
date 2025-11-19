<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2017-05-05T20:23:44+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table;
    protected $selected_db;
    protected $primary_key;

    public $salt_length = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_selected_db($selected_db)
    {
        return $this->selected_db = $selected_db;
    }

    public function set_table($table)
    {
        return $this->table = $table;
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function all($config = array(), $result = true, $get_compiled_select = false)
    {
        if (!empty($config)) {

            if (isset($config['from']) && !empty($config['from'])) {
                $from = $config['from'];

                $this->db->from($from);
            }

            if (isset($config['group_start_end']) && !empty($config['group_start_end'])) {
                $where    = $config['group_start_end']['where'];
                $or_where = $config['group_start_end']['or_where'];

                $this->db->group_start();
                $this->db->where($where);
                $this->db->or_where($or_where);
                $this->db->group_end();
            }

            if (isset($config['where_false']) && !empty($config['where_false'])) {
                $where = $config['where_false'];

                $this->db->where($where, null, false);
            }

            if (isset($config['where']) && !empty($config['where'])) {
                $where = $config['where'];

                $this->db->where($where);
            }

            if (isset($config['or_where']) && !empty($config['or_where'])) {
                $where = $config['or_where'];

                $this->db->or_where($where);
            }

            if (isset($config['where_in']) && !empty($config['where_in'])) {
                $where = $config['where_in'];

                foreach ($where as $key => $val) {
                    $this->db->where_in($key, $val);
                }
            }

            if (isset($config['like']) && !empty($config['like'])) {
                $where = $config['like'];

                foreach ($where as $key => $val) {
                    $this->db->like($key, $val);
                }
            }

            if (isset($config['not_like']) && !empty($config['not_like'])) {
                $where = $config['not_like'];

                foreach ($where as $key => $val) {
                    $this->db->not_like($key, $val);
                }
            }

            if (isset($config['and_not_like']) && !empty($config['and_not_like'])) {
                $where = $config['and_not_like'];

                foreach ($where as $key => $val) {
                    $this->db->not_like($key, $val);
                }
            }

            if (isset($config['or_like']) && !empty($config['or_like'])) {
                $where = $config['or_like'];

                foreach ($where as $key => $val) {
                    $this->db->or_like($key, $val);
                }
            }

            if (isset($config['fields']) && !empty($config['fields'])) {
                $fields = $config['fields'];

                if (is_array($fields)) {
                    $this->db->select(implode(",", $fields));
                } elseif (is_string($fields)) {
                    $this->db->select($fields, false);
                }
            }

            if (isset($config['group_by']) && !empty($config['group_by'])) {
                $groupBy = $config['group_by'];

                $this->db->group_by($groupBy);
            }

            if (isset($config['order_by']) && !empty($config['order_by'])) {
                $orderBy = $config['order_by'];

                $this->db->order_by($orderBy);
            }

            if (isset($config['limit']) && !empty($config['limit'])) {
                if (isset($config['limit']['start']) && isset($config['limit']['end'])) {
                    $start = $config['limit']['start'];
                    $limit = $config['limit']['end'];
                    $this->db->limit($limit, $start);
                } elseif (is_numeric($config['limit'])) {
                    $limit = $config['limit'];
                    $this->db->limit($limit);
                }
            }

            if (isset($config['join']) && !empty($config['join'])) {
                $join = $config['join'];

                foreach ($join as $table => $on) {
                    $this->db->join($table, $on);
                }
            }

            if (isset($config['left_join']) && !empty($config['left_join'])) {
                $join = $config['left_join'];

                foreach ($join as $table => $on) {
                    $this->db->join($table, $on, "left");
                }
            }

            if (isset($config['right_join']) && !empty($config['right_join'])) {
                $join = $config['right_join'];

                foreach ($join as $table => $on) {
                    $this->db->join($table, $on, "right");
                }
            }
        }
        if ($get_compiled_select == false) {
            if ($result == true) {
                if (isset($config['from']) && !empty($config['from'])) {
                    return $this->db->get()->result();
                } else {
                    return $this->db->get($this->table)->result();
                }
            } else {
                if (isset($config['from']) && !empty($config['from'])) {
                    return $this->db->get()->row();
                } else {
                    return $this->db->get($this->table)->row();
                }
            }
        } else {
            if (isset($config['from']) && !empty($config['from'])) {
                return $this->db->get_compiled_select();
            } else {
                return $this->db->get_compiled_select($this->table);
            }
        }
    }

    public function first($where = null, $last = true)
    {
        if (!is_null($where)) {
            if (is_numeric($where)) {
                $this->db->where($this->primary_key, $where);
            } else {
                $this->db->where($where);
            }
        }
        if ($last == false) {
            $this->db->order_by($this->primary_key, 'ASC');
        } else {
            $this->db->order_by($this->primary_key, 'DESC');
        }
        $this->db->limit(1);
        return $this->db->get($this->table)->row();
    }

    public function count($where = null)
    {
        if (!is_null($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->table);
    }

    public function count_false($where = null)
    {
        if (!is_null($where)) {
            $this->db->where($where, null, false);
        }
        return $this->db->count_all_results($this->table);
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save_batch($data)
    {
        $this->db->insert_batch($this->table, $data);
        return $this->db->insert_id();
    }

    public function edit($id, $data)
    {
        $this->db->set($data, false);
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table);
    }

    public function edit_where($where = array(), $data)
    {
        $this->db->where($where);
        return $this->db->update($this->table, $data);
    }

    public function delete($where)
    {
        if ($where) {
            if (is_numeric($where)) {
                $this->db->where($this->primary_key, $where);
            } else {
                $this->db->where($where);
            }
        }
        return $this->db->delete($this->table);
    }
}
