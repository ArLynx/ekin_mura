<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Unit_profiles_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'unit_profiles';
        $this->primary_key = 'unit_id';
    }

}
