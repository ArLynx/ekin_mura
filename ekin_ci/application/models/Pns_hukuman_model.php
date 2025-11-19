<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pns_hukuman_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'pns_hukuman';
        $this->primary_key = 'id';
    }

}
