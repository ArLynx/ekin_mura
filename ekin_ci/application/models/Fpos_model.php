<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fpos_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'fpos';
        $this->primary_key = 'KD_FPOS';
    }

}
