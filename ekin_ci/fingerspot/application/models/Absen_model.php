<?php

class Absen_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->table="att_log";
        $this->primary_id="sn";
    }
}