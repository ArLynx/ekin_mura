<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'setting';
        $this->primary_key = 'id';
    }

}
