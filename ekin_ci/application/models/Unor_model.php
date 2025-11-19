<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unor_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'unor';
        $this->primary_key = 'KD_UNOR';
    }

}
