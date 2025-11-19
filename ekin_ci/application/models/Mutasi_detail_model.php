<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_detail_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'mutasi_detail';
        $this->primary_key = 'id';
    }

}
