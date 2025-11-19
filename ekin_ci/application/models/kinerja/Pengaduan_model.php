<?php
# @Author: Awan Tengah
# @Date:   2019-08-22T22:52:00+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-22T22:57:33+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaduan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.pengaduan";
        $this->primary_key    = 'id';
    }

}
