<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tpp_max_before_2020_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.tpp_max_before_2020";
        $this->primary_key    = 'id';
    }

}
