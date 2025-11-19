<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bawahan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.pns_atasan";
        $this->primary_key    = 'id';
    }

    public function get_bawahan_list($year, $month, $nip, $unor, $like)
    {
        if($unor == null) {
            $unor = 0;
        }
        if($nip > 10 || $nip == 1) {
            $qry = "SELECT p.id, p.PNS_PNSNIP, PNS_GLRDPN, PNS_PNSNAM, PNS_GLRBLK, KD_GOL, NM_PKT, NM_GOL, PNS_PHOTO,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 0,1,0)) jml_diajukan,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6, 7, 8, 9),1,0)) jml_disetujui,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (2, 3),1,0)) jml_dikoreksi,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 4,1,0)) jml_ditolak,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6), k.norma_waktu, 0)) wkt_efektif
                    FROM
                        pns p 
                        LEFT JOIN gol g ON p.PNS_GOLRU = g.KD_GOL 
                        LEFT JOIN kinerja.kegiatan k ON p.PNS_PNSNIP = k.PNS_PNSNIP AND YEAR(k.waktu_mulai) = '$year' AND MONTH(k.waktu_mulai) = '$month'
                        LEFT JOIN kinerja.pns_atasan pa ON p.PNS_PNSNIP = pa.PNS_PNSNIP 
                    WHERE pa.pns_atasan = '$nip' 
                        AND p.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex WHERE YEAR(DATE) <= '2020') 
                        AND p.PNS_PNSNIP NOT IN (SELECT p.PNS_PNSNIP FROM pns WHERE p.PNS_PNSNIP LIKE '%TKD%') 
                    GROUP BY PNS_PNSNIP 
                    ORDER BY KD_GOL DESC";
        }else{
            //superadmin
            $qry = "SELECT 
                        p.id, p.PNS_PNSNIP, PNS_GLRDPN, PNS_PNSNAM, PNS_GLRBLK, KD_GOL, NM_PKT, NM_GOL, PNS_PHOTO,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 0,1,0)) jml_diajukan,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6, 7, 8, 9),1,0)) jml_disetujui,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (2, 3),1,0)) jml_dikoreksi,
                        SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 4,1,0)) jml_ditolak,
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
        }

        // if($nip > 10 || $nip == 1)
        // $qry = "a.pns_atasan = '$nip' AND";
        // else {
        //     if($unor == '8836000000' || $unor == '8837000000' || $unor == '8838000000' || $unor == '8839000000')
        //     $unor_qry = " IN (SELECT kd_unor
        //     FROM presensi.unor WHERE kd_unor LIKE (SELECT CONCAT(SUBSTR('$unor', 1, 7),'%'))
        //     AND (nm_unor LIKE 'desa%' OR nm_unor LIKE '%camat%'))";
        //     else if(substr($unor,7,3) == '000' && $unor != '8818000000')
        //     $unor_qry = " LIKE (SELECT CONCAT(SUBSTR('$unor', 1, 7),'%'))";
        //     else
        //     $unor_qry = "= '".$unor."'";

        //     $qry = "p.PNS_UNOR $unor_qry AND";
        // }

        // $qry = "SELECT id,PNS_PNSNIP,PNS_GLRDPN,PNS_PNSNAM,PNS_GLRBLK,NM_PKT,NM_GOL,PNS_PHOTO,jml_diajukan,jml_disetujui,jml_dikoreksi,jml_ditolak,wkt_efektif,is_isi
        // FROM (SELECT p.*, g.NM_GOL, g.NM_PKT,SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 0, 1, 0)) jml_diajukan,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1,6,7,8,9), 1, 0)) jml_disetujui,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (2,3), 1, 0)) jml_dikoreksi,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 4, 1, 0)) jml_ditolak,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1,6), k.norma_waktu, 0)) wkt_efektif, 1 is_isi
        // FROM kinerja.pns_atasan a
        // INNER JOIN presensi.pns p ON a.PNS_PNSNIP = p.PNS_PNSNIP
        // LEFT JOIN kinerja.kegiatan k ON a.PNS_PNSNIP = k.pns_pnsnip AND YEAR(k.waktu_mulai) = '$year' AND MONTH(k.waktu_mulai) = '$month'
        // LEFT JOIN presensi.gol g ON p.PNS_GOLRU = g.KD_GOL
        // WHERE $qry (a.PNS_PNSNIP LIKE '%$like%' OR p.PNS_PNSNAM LIKE '%$like%')
        // GROUP BY PNS_PNSNIP
        // UNION
        // SELECT p.*, g.NM_GOL, g.NM_PKT,0 jml_diajukan,0 jml_disetujui,0 jml_dikoreksi,0 jml_ditolak,0 wkt_efektif, 0 is_isi
        // FROM kinerja.pns_atasan a
        // INNER JOIN presensi.pns p ON a.PNS_PNSNIP = p.PNS_PNSNIP
        // LEFT JOIN presensi.gol g ON p.PNS_GOLRU = g.KD_GOL
        // WHERE $qry (a.PNS_PNSNIP LIKE '%$like%' OR p.PNS_PNSNAM LIKE '%$like%'))a
        // WHERE PNS_PNSNIP NOT IN (SELECT nip FROM presensi.pns_ex WHERE YEAR(DATE) <= '{$year}')
        // GROUP BY PNS_PNSNIP
        // ORDER BY PNS_UNOR ASC, PNS_GOLRU DESC, PNS_JABSTR, PNS_TMTGOL, PNS_PNSNAM ASC";

        return $this->db->query($qry)->result();
    }

    // public function get_pns_skpd_list($year, $month, $nip, $unor)
    public function get_pns_skpd_list($year, $month, $nip, $unor)
    {
        // if($nip > 10 || $nip == 1)
        // $qry = "a.pns_atasan = '$nip' AND";
        // else {
        //     //untuk kecamatan yang punya kelurahan
        //     if($unor == '8836000000' || $unor == '8837000000' || $unor == '8838000000' || $unor == '8839000000')
        //     $unor_qry = " IN (SELECT kd_unor
        //     FROM presensi.unor WHERE kd_unor LIKE (SELECT CONCAT(SUBSTR('$unor', 1, 7),'%'))
        //     AND (nm_unor LIKE 'desa%' OR nm_unor LIKE '%camat%'))";
        //     //untuk semua skpd yg akhiran unor 000 kecuali dinkes
        //     else if(substr($unor,7,3) == '000' && $unor != '8818000000')
        //     $unor_qry = " LIKE (SELECT CONCAT(SUBSTR('$unor', 1, 7),'%'))";
        //     //dinkes, PKM dan kantor lurah
        //     else
        //     $unor_qry = "= '".$unor."'";

        //     $qry = "p.PNS_UNOR $unor_qry";
        // }

        // $qry = "SELECT id,PNS_PNSNIP,PNS_GLRDPN,PNS_PNSNAM,PNS_GLRBLK,NM_PKT,NM_GOL,PNS_PHOTO,jml_diajukan,jml_disetujui,jml_dikoreksi,jml_ditolak,wkt_efektif,is_isi
        // FROM (SELECT p.*, g.NM_GOL, g.NM_PKT,SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 0, 1, 0)) jml_diajukan,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1,6,7,8,9), 1, 0)) jml_disetujui,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (2,3), 1, 0)) jml_dikoreksi,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 4, 1, 0)) jml_ditolak,
        // SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1,6), k.norma_waktu, 0)) wkt_efektif, 1 is_isi
        // FROM kinerja.pns_atasan a
        // INNER JOIN presensi.pns p ON a.PNS_PNSNIP = p.PNS_PNSNIP
        // LEFT JOIN kinerja.kegiatan k ON a.PNS_PNSNIP = k.pns_pnsnip AND YEAR(k.waktu_mulai) = '$year' AND MONTH(k.waktu_mulai) = '$month'
        // LEFT JOIN presensi.gol g ON p.PNS_GOLRU = g.KD_GOL
        // WHERE $qry
        // GROUP BY PNS_PNSNIP
        // UNION
        // SELECT p.*, g.NM_GOL, g.NM_PKT,0 jml_diajukan,0 jml_disetujui,0 jml_dikoreksi,0 jml_ditolak,0 wkt_efektif, 0 is_isi
        // FROM kinerja.pns_atasan a
        // INNER JOIN presensi.pns p ON a.PNS_PNSNIP = p.PNS_PNSNIP
        // LEFT JOIN presensi.gol g ON p.PNS_GOLRU = g.KD_GOL
        // WHERE $qry )a
        // WHERE PNS_PNSNIP NOT IN (SELECT nip FROM presensi.pns_ex WHERE YEAR(DATE) <= '{$year}')
        // GROUP BY PNS_PNSNIP
        // ORDER BY PNS_UNOR ASC, PNS_GOLRU DESC, PNS_JABSTR, PNS_TMTGOL, PNS_PNSNAM ASC";

      if($unor == null) {
          $unor = 0;
      }
        $qry = "SELECT 
            p.id, p.PNS_PNSNIP, PNS_GLRDPN, PNS_PNSNAM, PNS_GLRBLK, KD_GOL, NM_PKT, NM_GOL, PNS_PHOTO,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 0,1,0)) jml_diajukan,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (1, 6, 7, 8, 9),1,0)) jml_disetujui,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status IN (2, 3),1,0)) jml_dikoreksi,
            SUM(IF(k.nama_kegiatan IS NOT NULL AND k.status = 4,1,0)) jml_ditolak,
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
