<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pns_jam_extra_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'pns_jam_extra';
        $this->primary_key = 'id';
    }

}
