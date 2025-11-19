<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_pekerjaan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_pekerjaan';
        // $get_config_dbkinerja = get_config_item('dbkinerja');
        // $this->table          = "{$get_config_dbkinerja}.pekerjaan";
        $this->primary_key    = 'id';
    }

}
