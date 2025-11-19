<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cpns_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpns';
        $this->primary_key = 'id';
    }

}
