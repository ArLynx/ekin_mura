<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pekerjaan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pekerjaan_maping_model');

        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.pekerjaan";
        $this->primary_key    = 'id';
    }

    public function get_pekerjaan_per_jabatan_and_non_tupoksi($unor, $genpos)
    {
        $sql = "SELECT * FROM (
            (SELECT
                p.id,CONCAT('&radic; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = $unor
            AND p.id_jabatan = $genpos
            ORDER BY p.prioritas ASC)
            UNION
            (SELECT
                p.id,CONCAT('&omicron; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = '0'
            AND p.id_jabatan = '0'
            ORDER BY p.id ASC)
        ) as aaa";

        return $this->db->query($sql)->result();
    }

    public function get_pekerjaan_per_jabatan_and_non_tupoksi_jfu($unor, $no_jabfus)
    {
        $sql = "SELECT * FROM (
            (SELECT
                p.id,CONCAT('&radic; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = $unor
            AND p.id_jabatan = $no_jabfus
            ORDER BY p.prioritas ASC)
            UNION
            (SELECT
                p.id,CONCAT('&omicron; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = '0'
            AND p.id_jabatan = '0'
            ORDER BY p.id ASC)
        ) as aaa";

        return $this->db->query($sql)->result();
    }

    public function get_pekerjaan_per_jabatan_and_non_tupoksi_jft($unor, $kd_fpos)
    {
        $sql = "SELECT * FROM (
            (SELECT
                p.id,CONCAT('&radic; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = $unor
            AND p.id_jabatan = $kd_fpos
            ORDER BY p.prioritas ASC)
            UNION
            (SELECT
                p.id,CONCAT('&omicron; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
            FROM
                kinerja.pekerjaan p
            WHERE p.PNS_UNOR = '0'
            AND p.id_jabatan = '0'
            ORDER BY p.id ASC)
        ) as aaa";

        return $this->db->query($sql)->result();
    }

    public function get_pekerjaan_per_jabatan_and_non_tupoksi_jabatan_baru($unor, $id_mkj)
    {
        $dbkinerja = get_config_item('dbkinerja');

        $check_maping = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.pekerjaan_maping.id_master_kelas_jabatan" => $id_mkj,
                ),
            )
        );

        if ($check_maping != null) {
            $tmp = "";
            $i   = 0;
            foreach ($check_maping as $rowCM) {
                $genpos                  = $rowCM->KD_GENPOS;
                $no_jabfus               = $rowCM->no_master_jabfus;
                $kd_fpos                 = $rowCM->KD_FPOS;
                $id_master_kelas_jabatan = $rowCM->id_master_kelas_jabatan;

                if ($genpos == '9999') {
                    // if(decode_crypt($selected_sopd) == '8818000000' && ($no_jabfus == '1' || $no_jabfus == '155'))
                    //untuk dinkes pengadministrasian umum dua orang tupoksi beda
                    if ($id_master_kelas_jabatan == '209' || $id_master_kelas_jabatan == '1188') {
                        $data = $this->all(
                            array(
                                'fields' => "pekerjaan.id, CONCAT('&radic; ', pekerjaan.nama_pekerjaan) nama_pekerjaan, pekerjaan.PNS_UNOR, pekerjaan.id_jabatan, pekerjaan.prioritas, pekerjaan.id_master_kelas_jabatan",
                                'where'  => array(
                                    "pekerjaan.PNS_UNOR"                => $unor,
                                    "pekerjaan.id_master_kelas_jabatan" => $id_master_kelas_jabatan,
                                ),
                            ), true, true
                        );
                    } else {
                        $data = $this->all(
                            array(
                                'fields' => "pekerjaan.id, CONCAT('&radic; ', pekerjaan.nama_pekerjaan) nama_pekerjaan, pekerjaan.PNS_UNOR, pekerjaan.id_jabatan, pekerjaan.prioritas, pekerjaan.id_master_kelas_jabatan",
                                'where'  => array(
                                    "pekerjaan.PNS_UNOR"   => $unor,
                                    "pekerjaan.id_jabatan" => $no_jabfus,
                                ),
                            ), true, true
                        );
                    }
                } else if ($genpos == 'FT') {
                    $data = $this->all(
                        array(
                            'fields' => "pekerjaan.id, CONCAT('&radic; ', pekerjaan.nama_pekerjaan) nama_pekerjaan, pekerjaan.PNS_UNOR, pekerjaan.id_jabatan, pekerjaan.prioritas, pekerjaan.id_master_kelas_jabatan",
                            'where'  => array(
                                "pekerjaan.PNS_UNOR"   => $unor,
                                "pekerjaan.id_jabatan" => $kd_fpos,
                            ),
                        ), true, true
                    );
                } else { //untuk jabatan JS
                    $data = $this->all(
                        array(
                            'fields' => "pekerjaan.id, CONCAT('&radic; ', pekerjaan.nama_pekerjaan) nama_pekerjaan, pekerjaan.PNS_UNOR, pekerjaan.id_jabatan, pekerjaan.prioritas, pekerjaan.id_master_kelas_jabatan",
                            'where'  => array(
                                "pekerjaan.PNS_UNOR"   => $unor,
                                "pekerjaan.id_jabatan" => $genpos,
                            ),
                        ), true, true
                    );
                }

                $tmp .= $data . " UNION ";
            }

            $data2 = $this->all(
                array(
                    'fields' => "pekerjaan.id, CONCAT('&omicron; ', pekerjaan.nama_pekerjaan) nama_pekerjaan, pekerjaan.PNS_UNOR, pekerjaan.id_jabatan, pekerjaan.prioritas, pekerjaan.id_master_kelas_jabatan",
                    'where'  => array(
                        "pekerjaan.PNS_UNOR"   => '0',
                        "pekerjaan.id_jabatan" => '0',
                    ),
                ), true, true
            );

            $query = $this->query("SELECT * FROM ({$tmp} {$data2}) AS zzz ORDER BY id_jabatan DESC, prioritas ASC")->result();
            return $query;
        } else {
            $sql = "SELECT * FROM (
                (SELECT
                    p.id,CONCAT('&radic; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
                FROM
                    kinerja.pekerjaan p
                WHERE p.PNS_UNOR = $unor
                AND p.id_master_kelas_jabatan = $id_mkj
                ORDER BY p.prioritas ASC)
                UNION
                (SELECT
                    p.id,CONCAT('&omicron; ',p.nama_pekerjaan) nama_pekerjaan,p.PNS_UNOR,p.id_jabatan,p.prioritas,p.id_master_kelas_jabatan
                FROM
                    kinerja.pekerjaan p
                WHERE p.PNS_UNOR = '0'
                AND p.id_jabatan = '0'
                ORDER BY p.id ASC)
            ) as aaa";

            $query = $this->query($sql)->result();
            return $query;
        }

    }

}
