<?php
# @Author: Awan Tengah
# @Date:   2019-08-09T20:29:22+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-09T20:29:53+07:00

defined('BASEPATH') OR exit('No direct script access allowed');

class Perintah_tugas_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'perintah_tugas';
        $this->primary_key = 'id';
    }

}
