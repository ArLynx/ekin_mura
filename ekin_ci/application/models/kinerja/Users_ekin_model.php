<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_ekin_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.users";
        $this->primary_key    = 'id';
    }

    public function get_users($id_user = null, $selected_sopd)
    {
        $dbpresensi = get_config_item('dbpresensi');

        $select = "users.*, IF(
                    {$dbpresensi}.pns.PNS_GLRDPN IS NOT NULL
                    AND {$dbpresensi}.pns.PNS_GLRBLK IS NOT NULL,
                    CONCAT(
                    CONCAT({$dbpresensi}.pns.PNS_GLRDPN, '. '),
                    CONCAT(
                        {$dbpresensi}.pns.PNS_PNSNAM,
                        CONCAT(', ', {$dbpresensi}.pns.PNS_GLRBLK)
                    )
                    ),
                    IF(
                    {$dbpresensi}.pns.PNS_GLRDPN IS NOT NULL,
                    CONCAT(
                        CONCAT({$dbpresensi}.pns.PNS_GLRDPN, '. '),
                        {$dbpresensi}.pns.PNS_PNSNAM
                    ),
                    IF(
                        {$dbpresensi}.pns.PNS_GLRBLK IS NOT NULL,
                        CONCAT(
                        {$dbpresensi}.pns.PNS_PNSNAM,
                        CONCAT(', ', {$dbpresensi}.pns.PNS_GLRBLK)
                        ),
                        {$dbpresensi}.pns.PNS_PNSNAM
                    )
                    )
                ) AS PNS_NAMA, {$dbpresensi}.pns.PNS_UNOR, {$dbpresensi}.unit_profiles.unit_id, {$dbpresensi}.unit_profiles.unor, {$dbpresensi}.unor.NM_UNOR AS sopd_name, 'General User (PNS)' AS groups_name";

        $left_join = [
            "{$dbpresensi}.pns"           => "{$dbpresensi}.pns.PNS_PNSNIP = users.nip",
            "{$dbpresensi}.unit_profiles" => "{$dbpresensi}.unit_profiles.unor ={$dbpresensi}.pns.PNS_UNOR",
            "{$dbpresensi}.unor"          => "{$dbpresensi}.unor.KD_UNOR ={$dbpresensi}.pns.PNS_UNOR",
        ];

        $where = [
            "users.id NOT IN "                     => "(SELECT user_id FROM users_groups)",
            "{$dbpresensi}.pns.PNS_UNOR"           => "'{$selected_sopd}'",
            "{$dbpresensi}.pns.PNS_PNSNIP NOT IN " => "(SELECT nip FROM {$dbpresensi}.pns_ex WHERE status != 'cpns')",
        ];

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
