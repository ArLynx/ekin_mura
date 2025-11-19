<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pekerjaan_maping_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.pekerjaan_maping";
        $this->primary_key    = 'id';
    }

}
