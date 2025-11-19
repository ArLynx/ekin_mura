<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pns_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table       = 'pns';
        $this->primary_key = 'id';
        $this->dbkinerja   = get_config_item('dbkinerja');
    }

    public function count_all_pegawai()
    {
        $this->db->like('pns.PNS_PNSNIP', 'TKD');
        return $this->db->count_all_results('pns');
    }

    public function get_detail_pns($pns_pnsnip)
    {
        return $this->all(
            array(
                'fields'    => "pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                pns.PNS_UNOR, master_kelas_jabatan.nama_jabatan",
                'left_join' => array(
                    'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                ),
                'where'     => array(
                    'pns.PNS_PNSNIP' => $pns_pnsnip,
                ),
            ), false
        );
    }

    public function get_atasan_langsung($unor, $kelas_jabatan)
    {
        $this->db->select("pns.PNS_PNSNIP,IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan");
        $this->db->join('master_kelas_jabatan', 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan', 'left');
        

        $arr_kecamatan = [
            '30201740',
            '30201741',
            '30201742',
            '30201743',
            '30201744',
            '30201745',
            '30201746',
            '30201747',
            '30201748',
            '30201749',
        ];
        $unorKec = '';
        if($unor == '30201753'){
            $unorKec = '30201741';
        }
        else if($unor == '30201754'){
            $unorKec = '30201741';
        }
        else if($unor == '30201755'){
            $unorKec = '30201741';
        }
        //BERIWIT
            else if($unor == '30201750'){
            $unorKec = '30201742';
        }
            else if($unor == '30201751'){
            $unorKec = '30201742';
        }
            else if($unor == '30201752'){
            $unorKec = '30201747';
        }
            else if($unor == '30201756'){
            $unorKec = '30201743';
        }
            else if($unor == '30201757'){
            $unorKec = '30201743';
        }
            else if($unor == '30201758'){
            $unorKec = '30201745';
        }

        if ($kelas_jabatan == 12) {
            $check = in_array($unor, $arr_kecamatan);
        } 
         if ($kelas_jabatan == 9) {
            $check = in_array($unorKec, $arr_kecamatan);
        } else {
            $check = array();
        }

        if (($kelas_jabatan >= 6 || $kelas_jabatan <= 9) && !empty($check)) {
            $this->db->where('(master_kelas_jabatan.kelas_jabatan', '11 OR master_kelas_jabatan.kelas_jabatan = 12)', false);
            $this->db->where('pns.PNS_UNOR', $unorKec); //Camatnya
        } else if (($kelas_jabatan == 12 && !empty($check)) || $kelas_jabatan == 14) {
            $this->db->where('master_kelas_jabatan.kelas_jabatan', 15);
            $this->db->or_where('master_kelas_jabatan.kelas_jabatan', 14);
            $this->db->where('pns.PNS_UNOR', '30201711'); //Sekretariat Daerah
        } else {
            $this->db->where('master_kelas_jabatan.kelas_jabatan', 15);
            $this->db->or_where('master_kelas_jabatan.kelas_jabatan', 14);
            $this->db->where('pns.PNS_UNOR', '30201711'); //Sekretariat Daerah
            $this->db->or_where('pns.PNS_UNOR', $unor);
            $this->db->where('master_kelas_jabatan.kelas_jabatan > ', $kelas_jabatan);
        }
        $this->db->where('pns.PNS_PNSNIP NOT IN', "(SELECT nip FROM pns_ex where unor = '{$unor}')", false);
        $this->db->not_like('pns.PNS_PNSNIP', 'TKD');
        $query1 = $this->db->get_compiled_select('pns');

        $this->db->select("pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, master_kelas_jabatan.kelas_jabatan, CONCAT('Plt. ', master_kelas_jabatan.nama_jabatan) AS nama_jabatan");
        $this->db->join('pns', 'pns.PNS_PNSNIP = pns_plt.pns_pnsnip', 'left');
        $this->db->join('master_kelas_jabatan', 'master_kelas_jabatan.id = pns_plt.id_master_kelas_jabatan_plt', 'left');
        $this->db->where('pns_plt.pns_unor_plt', $unor);
        $this->db->where('pns_plt.pns_pnsnip NOT IN', "(SELECT nip FROM pns_ex where unor = '{$unor}')", false);
        $query2 = $this->db->get_compiled_select('pns_plt');

        $this->db->select("pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan");
        $this->db->join('mutasi_detail', 'mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP');
        $this->db->join('master_kelas_jabatan', 'master_kelas_jabatan.id = mutasi_detail.id_master_kelas_jabatan_baru');
        $this->db->where('mutasi_detail.pns_unor_baru', $unor);
        $this->db->where('mutasi_detail.status', '0');
        $this->db->where('pns.pns_pnsnip NOT IN', "(SELECT nip FROM pns_ex where unor = '{$unor}')", false);
        $query3 = $this->db->get_compiled_select('pns');

        return $this->db->query("SELECT * FROM ({$query1} UNION {$query2} UNION {$query3}) AS zzz ORDER BY kelas_jabatan DESC")->result();
    }
}
