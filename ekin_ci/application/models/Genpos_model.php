<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Genpos_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'genpos';
        $this->primary_key = 'KD_GENPOS';
    }

}
