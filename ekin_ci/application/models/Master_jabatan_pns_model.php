<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_jabatan_pns_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_jabatan_pns';
        $this->primary_key = 'id';
    }
}
