<?php

/*
 * @Author: Awan Tengah
 * @Date: 2020-09-02 10:26:49
 * @Last Modified by:   Awan Tengah
 * @Last Modified time: 2020-09-02 10:26:49
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Pns_atasan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.pns_atasan";
        $this->primary_key    = 'id';
    }

}
