<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_pekerjaan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.kegiatan";
        $this->primary_key    = 'id';
    }

    public function get_pns_list($year, $month, $unor)
    {
      if($unor == null) {
          $unor = 0;
      }
        $qry = "SELECT 
            p.id, p.PNS_PNSNIP, PNS_GLRDPN, PNS_PNSNAM, PNS_GLRBLK, KD_GOL, NM_PKT, NM_GOL, PNS_PHOTO,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (0, 1, 2, 3, 4, 6, 7, 8, 9),1,0)) jml_pekerjaan,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6, 7, 8, 9),1,0)) jml_disetujui,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6), k.norma_waktu, 0)) wkt_efektif 
        FROM
            pns p 
            LEFT JOIN gol g ON p.PNS_GOLRU = g.KD_GOL 
            LEFT JOIN kinerja.kegiatan k ON p.PNS_PNSNIP = k.PNS_PNSNIP AND YEAR(k.waktu_mulai) = '$year' AND MONTH(k.waktu_mulai) = '$month' 
        WHERE PNS_UNOR = '$unor' 
            AND p.PNS_PNSNIP NOT IN 
            (SELECT nip FROM pns_ex WHERE YEAR(DATE) <= '{$year}') 
            AND p.PNS_PNSNIP NOT IN 
            (SELECT p.PNS_PNSNIP FROM pns WHERE p.PNS_PNSNIP LIKE '%TKD%') 
        GROUP BY PNS_PNSNIP 
        ORDER BY KD_GOL DESC";

        return $this->db->query($qry)->result();
    }

}