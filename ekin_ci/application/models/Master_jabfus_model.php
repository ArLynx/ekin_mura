<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_jabfus_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_jabfus';
        $this->primary_key = 'no';
    }
}
