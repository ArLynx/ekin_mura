<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_group_jam_kerja_shift_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_group_jam_kerja_shift';
        $this->primary_key = 'id';
    }
}
