<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pns_plt_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'pns_plt';
        $this->primary_key = 'id';
    }

       public function get_detail_pns_plt($pns_pnsnip, $unor)
    {
        return $this->all(
            array(
                'fields'    => "pns_plt.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                master_kelas_jabatan.nama_jabatan, (SELECT nama_jabatan FROM master_kelas_jabatan WHERE id = pns_plt.id_master_kelas_jabatan_plt) as nama_jabatan_plt, pns_plt.pns_unor_plt,
                unor.NM_UNOR, (SELECT NM_UNOR FROM unor WHERE KD_UNOR = pns_plt.pns_unor_plt) AS NM_UNOR_PLT, pns_plt.awal_plt, pns_plt.akhir_plt, pns_plt.sk_plt",
                'left_join' => array(
                     'pns'                  => 'pns.PNS_PNSNIP = pns_plt.pns_pnsnip',
                    'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                     'unor'                 => 'unor.KD_UNOR = pns.PNS_UNOR',
                ),
                'where'     => array(
                    'pns_plt.pns_pnsnip' => $pns_pnsnip,
                    'pns_plt.pns_unor_plt'=> $unor,
                    'pns_plt.akhir_plt' => NULL
                 
                ),
            ), false
        );
    }

}
