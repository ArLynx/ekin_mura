<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gol_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'gol';
        $this->primary_key = 'KD_GOL';
    }

}
