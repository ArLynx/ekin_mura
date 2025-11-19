<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Absen_libur_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'absen_libur';
        $this->primary_key = 'id';
    }

    public function get_absen_libur($month, $year)
    {
        return $this->all(
            array(
                'where' => array(
                    'MONTH(tanggal)' => $month,
                    'YEAR(tanggal)'  => $year,
                ),
            )
        );
    }

}
