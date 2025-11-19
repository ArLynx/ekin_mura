<?php
# @Author: Awan Tengah
# @Date:   2019-08-19T08:01:24+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-19T08:01:53+07:00

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_jenis_cuti_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_jenis_cuti';
        $this->primary_key = 'id';
    }

}
