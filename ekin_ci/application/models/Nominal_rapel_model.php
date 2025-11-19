<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nominal_rapel_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'nominal_rapel';
        $this->primary_key = 'id';
    }

}
