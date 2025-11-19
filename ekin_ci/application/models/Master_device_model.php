<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_device_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'master_device';
        $this->primary_key = 'id';
    }

}
