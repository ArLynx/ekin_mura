<?php

class Privilage_level_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->table="privilage_level_menu";
        $this->primary_id="id_privilage";
    }
}