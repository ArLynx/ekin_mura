<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_jam_kerja_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_jam_kerja';
        $this->primary_key = 'id';
    }

}
