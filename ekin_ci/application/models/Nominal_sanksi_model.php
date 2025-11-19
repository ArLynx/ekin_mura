<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nominal_sanksi_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'nominal_sanksi';
        $this->primary_key = 'id';
    }

}
