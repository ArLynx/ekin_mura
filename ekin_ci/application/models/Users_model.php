<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'users';
        $this->primary_key = 'id';
    }

    public function get_users($id_user = null, $selected_sopd = null, $selected_groups)
    {

        $select = "users.*, unit_profiles.nama_unit AS PNS_NAMA, unit_profiles.unit_id, unit_profiles.unor, unit_profiles.unor AS PNS_UNOR, unor.NM_UNOR AS sopd_name, 'Kepegawaian' AS groups_name";

        $left_join = [
            "unit_profiles" => "unit_profiles.unit_id = users.unit",
            "users_groups"  => "users_groups.user_id = users.id",
            "unor"          => "unor.KD_UNOR = unit_profiles.unor",
        ];

        $where = [
            "users_groups.group_id" => $selected_groups,
        ];

        if(!is_null($selected_sopd)) {
            $where = $where + ["unit_profiles.unor"    => "'{$selected_sopd}'"];
        }

        if (!is_null($id_user)) {
            $where = $where + ["users.id" => $id_user];
        }

        return $this->all([
            'fields'      => $select,
            'from'        => $this->table,
            'left_join'   => $left_join,
            'where_false' => $where,
            'order_by'    => 'PNS_NAMA ASC',
        ], (!is_null($id_user) ? false : true));
    }

}
