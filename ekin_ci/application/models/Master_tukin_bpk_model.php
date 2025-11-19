<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_tukin_bpk_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_tukin_bpk';
        $this->primary_key = 'id';
    }

}
