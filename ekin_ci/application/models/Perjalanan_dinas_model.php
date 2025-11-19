<?php
# @Author: Awan Tengah
# @Date:   2019-08-12T14:09:42+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-12T14:10:12+07:00

defined('BASEPATH') OR exit('No direct script access allowed');

class Perjalanan_dinas_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'perjalanan_dinas';
        $this->primary_key = 'id';
    }

}
