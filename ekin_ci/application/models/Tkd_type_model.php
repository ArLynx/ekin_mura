<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tkd_type_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'tkd_type';
        $this->primary_key = 'id';
    }

}
