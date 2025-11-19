<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Absen_enroll_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table       = 'absen_enroll';
        $this->primary_key = 'id';

        $this->load->model('setting_model');
    }

    public function get_hari_kerja_pegawai($unor, $type = 0)
    {
        if ($type != 0) {
            $join   = " LEFT JOIN tkd_detail ON tkd_detail.id_tkd = pns.id LEFT JOIN pengaturan_shift ON pengaturan_shift.nip = pns.PNS_PNSNIP ";
            $select = " tkd_detail.hari_kerja, pengaturan_shift.jam_masuk, pengaturan_shift.jam_pulang ";
        } else {
            $join   = "";
            $select = " '5' AS hari_kerja, '07:00' AS jam_masuk, '15:30' AS jam_pulang ";
        }

        $sql = "
            SELECT pns.PNS_PNSNIP, {$select} FROM pns {$join} WHERE pns.PNS_UNOR = {$unor}
        ";
        return $this->db->query($sql)->result();
    }

    public function get_all_absen_enroll($unor, $month, $year, $type = 0, $nip = '')
    {
        $days_in_month    = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $select_max       = "";
        $select_max_empty = "";
        $select_if_cond   = "";
        $union            = "";

        $where_nip = !empty($nip) ? " AND b.PNS_PNSNIP = '{$nip}'" : "";
        $tipe_pegawai_tpp = [0, 5];

        if (!in_array($type, $tipe_pegawai_tpp)) {
            $where            = " AND b.PNS_PNSNIP LIKE '%TKD%' AND tkd_detail.id_tipe_pegawai = {$type} {$where_nip}";
            $join             = " LEFT JOIN tkd_detail ON tkd_detail.id_tkd = pns.id LEFT JOIN pengaturan_shift ON pengaturan_shift.nip = pns.PNS_PNSNIP ";
            $select_jam_kerja = " tkd_detail.hari_kerja, pengaturan_shift.jam_masuk, pengaturan_shift.jam_pulang, ";
            $order_by         = ' PNS_PNSNIP';
        } else {
            $select_max .= ' kelas_jabatan, ';
            $select_max_empty .= ' kelas_jabatan, ';
            $select_if_cond .= ' master_kelas_jabatan.kelas_jabatan, ';
            $where            = " AND b.PNS_PNSNIP NOT LIKE '%TKD%' {$where_nip}";
            $join             = " LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns.id_master_kelas_jabatan ";
            $select_jam_kerja = " '5' AS hari_kerja, '07:00' AS jam_masuk, '15:30' AS jam_pulang, ";
            $order_by         = '  PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC';
        }

        for ($i = 1; $i <= $days_in_month; $i++) {
            $i = date('d', strtotime("{$year}-{$month}-{$i}"));
            $select_max .= "
                MAX(in{$i}) in{$i},
                MAX(out{$i}) out{$i},
                MAX(ket{$i}) ket{$i},
                MAX(uraian{$i}) uraian{$i},
                MAX(uraian_in_{$i}) uraian_in_{$i},
                MAX(uraian_out_{$i}) uraian_out_{$i}
            ";
            $select_max_empty .= "
                '' in{$i},
                '' out{$i},
                '' ket{$i},
                '' uraian{$i},
                '' uraian_in_{$i},
                '' uraian_out_{$i}
            ";

            $select_if_cond .= "
                MAX(IF(
                    DAY(b.tanggal) = '{$i}'
                    AND b.jenis = 'in',
                    DATE_FORMAT(b.waktu, '%H:%i'),
                    ''
                )) in{$i},
                MAX(IF(
                    DAY(b.tanggal) = '{$i}'
                    AND b.jenis = 'out',
                    DATE_FORMAT(b.waktu, '%H:%i'),
                    ''
                )) out{$i},
                MAX(IF(
                    DAY(b.tanggal) = '{$i}'
                    AND b.jenis = 'ket',
                    k.singkatan,
                    ''
                )) ket{$i},
                b.uraian uraian{$i},
                IF(
                    DAY(b.tanggal) = '{$i}'
                    AND b.jenis = 'in'
                    AND b.uraian LIKE '%manual%',
                    b.uraian,
                    ''
                ) AS uraian_in_{$i},
                IF(
                    DAY(b.tanggal) = '{$i}'
                    AND b.jenis = 'out'
                    AND b.uraian LIKE '%manual%',
                    b.uraian,
                    ''
                ) AS uraian_out_{$i}
            ";
            if ($i != $days_in_month) {
                $select_max .= ", ";
                $select_max_empty .= ", ";
                $select_if_cond .= ", ";
            }
        }

        if (in_array($type, $tipe_pegawai_tpp)) {

            $union .= "

                UNION

                SELECT
                    b.ID_TIPE_PEGAWAI,
                    b.PNS_PNSNIP,
                    b.PNS_UNOR,
                    b.PNS_GOLRU,
                    IF(
                    b.PNS_GLRDPN IS NOT NULL
                    AND b.PNS_GLRBLK IS NOT NULL,
                    CONCAT(
                        CONCAT(b.PNS_GLRDPN, '. '),
                        CONCAT(
                        b.PNS_PNSNAM,
                        CONCAT(', ', b.PNS_GLRBLK)
                        )
                    ),
                    IF(
                        b.PNS_GLRDPN IS NOT NULL,
                        CONCAT(
                        CONCAT(b.PNS_GLRDPN, '. '),
                        b.PNS_PNSNAM
                        ),
                        IF(
                        b.PNS_GLRBLK IS NOT NULL,
                        CONCAT(
                            b.PNS_PNSNAM,
                            CONCAT(', ', b.PNS_GLRBLK)
                        ),
                        b.PNS_PNSNAM
                        )
                    )
                    ) AS PNS_NAMA,
                    {$select_jam_kerja}
                    {$select_max_empty}
                FROM
                    pns b
                    LEFT JOIN master_kelas_jabatan
                        ON master_kelas_jabatan.id = b.id_master_kelas_jabatan
                WHERE b.PNS_UNOR = '{$unor}' {$where}
                    AND b.PNS_PNSNIP NOT IN
                    (SELECT
                        nip
                    FROM
                     pns_ex
                    WHERE unor = '{$unor}'
                        AND DATE <= STR_TO_DATE('1,{$month},{$year}', '%d,%m,%Y'))
                    AND b.ID_TIPE_PEGAWAI = '{$type}'
                GROUP BY b.PNS_PNSNIP
            ";

            $union .= "
                    UNION
                        
                    SELECT ID_TIPE_PEGAWAI, 
                    PNS_PNSNIP,
                    PNS_UNOR,
                    PNS_GOLRU,
                    PNS_NAMA,
                    hari_kerja,
                    jam_masuk,
                    jam_pulang,
                    {$select_max}
                    FROM (
                        SELECT
                    pns.ID_TIPE_PEGAWAI,
                    pns.PNS_PNSNIP,
                    pns.PNS_UNOR,
                    pns.PNS_GOLRU,
                    IF(
                    pns.PNS_GLRDPN IS NOT NULL
                    AND pns.PNS_GLRBLK IS NOT NULL,
                    CONCAT(
                        CONCAT(pns.PNS_GLRDPN, '. '),
                        CONCAT(
                        pns.PNS_PNSNAM,
                        CONCAT(', ', pns.PNS_GLRBLK)
                        )
                    ),
                    IF(
                        pns.PNS_GLRDPN IS NOT NULL,
                        CONCAT(
                        CONCAT(pns.PNS_GLRDPN, '. '),
                        pns.PNS_PNSNAM
                        ),
                        IF(
                        pns.PNS_GLRBLK IS NOT NULL,
                        CONCAT(
                            pns.PNS_PNSNAM,
                            CONCAT(', ', pns.PNS_GLRBLK)
                        ),
                        pns.PNS_PNSNAM
                        )
                    )
                    ) AS PNS_NAMA,
                    {$select_jam_kerja}
                        {$select_if_cond}
                FROM
                    absen_enroll b
                    LEFT JOIN pns
                        ON pns.PNS_PNSNIP = b.PNS_PNSNIP
                    LEFT JOIN kehadiran k
                        ON k.id = b.keterangan
                    {$join}
                    LEFT JOIN mutasi_detail
                        ON mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP
                        AND mutasi_detail.status = '0'
                    LEFT JOIN mutasi
                        ON mutasi.id = mutasi_detail.mutasi_id
                WHERE MONTH(b.tanggal) = {$month} {$where}
                    AND YEAR(b.tanggal) = {$year}
                    AND mutasi_detail.pns_unor_baru = '{$unor}'
                    AND mutasi_detail.pns_pnsnip NOT IN
                    (SELECT
                        nip
                    FROM
                        pns_ex
                    WHERE unor = '{$unor}'
                        AND date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))
                    AND mutasi.tanggal <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y')
                    AND pns.ID_TIPE_PEGAWAI = '{$type}'
                GROUP BY b.PNS_PNSNIP,
                    b.tanggal,
                    b.jenis) aaa
                GROUP BY PNS_PNSNIP";
        }

        $sql = "
            SELECT * FROM (
                SELECT 
                    ID_TIPE_PEGAWAI, 
                    PNS_PNSNIP,
                    PNS_UNOR,
                    PNS_GOLRU,
                    PNS_NAMA,
                    hari_kerja,
                    jam_masuk,
                    jam_pulang,
                    {$select_max}
                    FROM (
                        SELECT
                    pns.ID_TIPE_PEGAWAI,
                    pns.PNS_PNSNIP,
                    pns.PNS_UNOR,
                    pns.PNS_GOLRU,
                    IF(
                    pns.PNS_GLRDPN IS NOT NULL
                    AND pns.PNS_GLRBLK IS NOT NULL,
                    CONCAT(
                        CONCAT(pns.PNS_GLRDPN, '. '),
                        CONCAT(
                        pns.PNS_PNSNAM,
                        CONCAT(', ', pns.PNS_GLRBLK)
                        )
                    ),
                    IF(
                        pns.PNS_GLRDPN IS NOT NULL,
                        CONCAT(
                        CONCAT(pns.PNS_GLRDPN, '. '),
                        pns.PNS_PNSNAM
                        ),
                        IF(
                        pns.PNS_GLRBLK IS NOT NULL,
                        CONCAT(
                            pns.PNS_PNSNAM,
                            CONCAT(', ', pns.PNS_GLRBLK)
                        ),
                        pns.PNS_PNSNAM
                        )
                    )
                    ) AS PNS_NAMA,
                    {$select_jam_kerja}
                        {$select_if_cond}
                FROM
                    absen_enroll b
                    LEFT JOIN pns
                    ON pns.PNS_PNSNIP = b.PNS_PNSNIP
                    LEFT JOIN kehadiran k
                    ON k.id = b.keterangan
                    {$join}
                WHERE MONTH(b.tanggal) = {$month}
                    AND YEAR(b.tanggal) = {$year}
                    {$where}
                    AND pns.PNS_UNOR = '{$unor}'
                    AND pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex WHERE unor = '{$unor}' AND date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))
                    AND pns.ID_TIPE_PEGAWAI = '{$type}'
                GROUP BY b.PNS_PNSNIP,
                    b.tanggal,
                    b.jenis) aaa
                GROUP BY PNS_PNSNIP
                {$union}
            ) zzz
            GROUP BY PNS_PNSNIP
            ORDER BY {$order_by}
        ";
        return $this->db->query($sql)->result();
    }

}
