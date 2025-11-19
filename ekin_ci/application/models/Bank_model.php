<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bank_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'bank';
        $this->primary_key = 'id';
    }

}
