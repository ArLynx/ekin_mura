<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2017-05-05T10:57:41+07:00

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'menu';
        $this->primary_key = 'id';
    }

}
