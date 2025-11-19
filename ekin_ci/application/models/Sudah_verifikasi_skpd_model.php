<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sudah_verifikasi_skpd_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'sudah_verifikasi_skpd';
        $this->primary_key = 'id';
    }

}
