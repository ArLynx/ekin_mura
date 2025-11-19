<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rincian_pekerjaan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.rincian_pekerjaan";
        $this->primary_key    = 'id';
    }

}
