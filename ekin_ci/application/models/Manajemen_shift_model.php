<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_shift_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'manajemen_shift';
        $this->primary_key = 'id';
    }

}
