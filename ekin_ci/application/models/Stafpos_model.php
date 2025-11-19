<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stafpos_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'stafpos';
        $this->primary_key = 'KD_STAFPOS';
    }

}
