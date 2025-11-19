<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tipe_pegawai_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'tipe_pegawai';
        $this->primary_key = 'id';
    }

}
