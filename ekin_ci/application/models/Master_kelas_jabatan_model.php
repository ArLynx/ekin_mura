<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_kelas_jabatan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'master_kelas_jabatan';
        $this->primary_key = 'id';
    }

}
