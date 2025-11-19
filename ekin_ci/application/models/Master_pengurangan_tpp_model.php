<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_pengurangan_tpp_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_pengurangan_tpp';
        $this->primary_key = 'id';
    }

}
