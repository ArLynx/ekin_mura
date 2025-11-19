<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users_groups_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'users_groups';
        $this->primary_key = 'id';
    }

}
