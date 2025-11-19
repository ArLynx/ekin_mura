<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_absen_bulanan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.rekap_absen_bulanan";
        $this->primary_key    = 'id';
    }

}
