<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Atasan_skpd_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'atasan_skpd';
        $this->primary_key = 'id';
    }

}
