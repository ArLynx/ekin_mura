<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_rincian_pekerjaan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_rincian_pekerjaan';
        $this->primary_key = 'id';
    }

}
