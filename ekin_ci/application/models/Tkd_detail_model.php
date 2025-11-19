<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tkd_detail_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'tkd_detail';
        $this->primary_key = 'id';
    }

}
