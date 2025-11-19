<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_unit_organisasi_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_unit_organisasi';
        $this->primary_key = 'id';
    }

}
