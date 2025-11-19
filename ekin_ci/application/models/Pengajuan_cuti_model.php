<?php
# @Author: Awan Tengah
# @Date:   2019-08-19T01:44:56+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-19T01:45:21+07:00

defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_cuti_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'pengajuan_cuti';
        $this->primary_key = 'id';
    }

}
