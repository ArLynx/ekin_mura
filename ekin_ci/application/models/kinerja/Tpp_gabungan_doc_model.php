<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tpp_gabungan_doc_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.tpp_gabungan_doc";
        $this->primary_key    = 'id';
    }

}
