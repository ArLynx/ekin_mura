<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'groups';
        $this->primary_key = 'id';
    }

}
