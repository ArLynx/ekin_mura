<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan_shift_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'pengaturan_shift';
        $this->primary_key = 'id';
    }
}
