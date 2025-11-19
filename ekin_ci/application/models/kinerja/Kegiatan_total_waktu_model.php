<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_total_waktu_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.kegiatan_total_waktu";
        $this->primary_key    = 'id';
    }

}
