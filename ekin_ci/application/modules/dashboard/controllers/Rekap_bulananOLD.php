<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_bulanan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('absen_enroll_model');
        $this->load->model('atasan_skpd_model');
        $this->load->model('pns_model');
        $this->load->model('unor_model');
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 5) {
            $unor = $this->input->get('unor', true);
        } else {
            $unor = get_session('unor');
        }
        $month = $this->input->get('month', true);
        $year  = $this->input->get('year', true);

        $nip = $this->input->get('nip', true);
        $day = $this->input->get('day', true);

        $tipe_pegawai = $this->input->get('tipe_pegawai', true);

        if (!empty($unor) && !empty($month) && !empty($year)) {
            if ($nip) {
                $where = array(
                    'pns.PNS_PNSNIP'        => $nip,
                    'pns.PNS_UNOR'          => $unor,
                    'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                );
            } else {
                $where = array(
                    'pns.PNS_UNOR'          => $unor,
                    'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                );
            }

            if ($tipe_pegawai != 'null' && $tipe_pegawai != 0) {
                $where = array_merge($where, array('tkd_detail.id_tipe_pegawai' => $tipe_pegawai));
            }

            $search_name = $this->input->get('search_name', true);
            if ($search_name) {
                $like = array(
                    'pns.PNS_PNSNIP' => 'TKD',
                    'PNS_PNSNAM'     => $search_name,
                );
            } elseif ($tipe_pegawai == 0) {
                $select    = "'5' as hari_kerja, '07:00:00' as jam_masuk, '15:30:00' as jam_pulang";
                $left_join = array();
                $get_total = $this->pns_model->all(
                    array(
                        'fields'      => 'COUNT(pns.id) as count_id',
                        'where_false' => $where,
                        'not_like'    => array(
                            'pns.PNS_PNSNIP' => 'TKD',
                        ),
                    ),
                    false
                );
            } else {
                $select    = "tkd_detail.hari_kerja, pengaturan_shift.jam_masuk, pengaturan_shift.jam_pulang";
                $left_join = array(
                    'tkd_detail'       => 'tkd_detail.id_tkd = pns.id',
                    'pengaturan_shift' => 'pengaturan_shift.nip = pns.PNS_PNSNIP',
                );
                $get_total = $this->pns_model->all(
                    array(
                        'fields'      => 'COUNT(pns.id) as count_id',
                        'left_join'   => array(
                            'tkd_detail' => 'tkd_detail.id_tkd = pns.id',
                        ),
                        'where_false' => $where,
                        'like'        => array(
                            'pns.PNS_PNSNIP' => 'TKD',
                        ),
                    ),
                    false
                );
            }

            $page     = $this->input->get('page', true) ? $this->input->get('page', true) : 0;
            $per_page = $this->input->get('per_page', true) ? $this->input->get('per_page', true) : 10;

            $total       = $get_total ? (int) $get_total->count_id : 0;
            $total_pages = ceil($total / $per_page);

            if ($tipe_pegawai != 0) {
                $data = $this->pns_model->all(
                    array(
                        'fields'      => "pns.PNS_PNSNIP, pns.PNS_PNSNAM, pns.PNS_GLRBLK, pns.PNS_UNOR, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, {$select}",
                        'left_join'   => $left_join,
                        'where_false' => $where,
                        'like'        => array(
                            'pns.PNS_PNSNIP' => 'TKD',
                        ),
                        'order_by'    => 'pns.PNS_PNSNAM ASC',
                        'limit'       => array(
                            'start' => $page * $per_page,
                            'end'   => $per_page,
                        ),
                    )
                );
            } else {
                $data = $this->pns_model->all(
                    array(
                        'fields'      => "pns.PNS_PNSNIP, pns.PNS_PNSNAM, pns.PNS_GLRBLK, pns.PNS_UNOR, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, {$select}",
                        'left_join'   => $left_join,
                        'where_false' => $where,
                        'not_like'    => array(
                            'pns.PNS_PNSNIP' => 'TKD',
                        ),
                        'group_by'    => 'pns.PNS_PNSNIP',
                        'order_by'    => 'pns.PNS_GOLRU DESC',
                        'limit'       => array(
                            'start' => $page * $per_page,
                            'end'   => $per_page,
                        ),
                    )
                );
            }
            // echo $this->db->last_query();
            // die();

            $tmp = array();

            // $tmp['page'] = $page;
            // $tmp['per_page'] = $per_page;
            $tmp['total']       = $total;
            $tmp['total_pages'] = $total_pages;

            if ($data) {
                foreach ($data as $key => $row) {
                    $tmp['data'][$key]['nip']        = $row->PNS_PNSNIP;
                    $tmp['data'][$key]['nama']       = $row->PNS_NAMA;
                    $tmp['data'][$key]['hari_kerja'] = $row->hari_kerja;

                    if (!empty($row->jam_masuk) && !empty($row->jam_pulang)) {
                        $tmp['data'][$key]['jam_kerja'] = "Masuk: {$row->jam_masuk} | Pulang: {$row->jam_pulang}";
                    } else {
                        $tmp['data'][$key]['jam_kerja'] = '';
                    }

                    // $cal_days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    // for($i_days = 1; $i_days <= $cal_days_in_month; $i_days++) {
                    $arr_get_absen_enroll = $this->get_absen_enroll($unor, $month, $year, $row->PNS_PNSNIP, $day, $tipe_pegawai);
                    // echo $this->db->last_query();die();
                    $inTime1  = '00:00';
                    $outTime1 = '00:00';
                    $inTime2  = '00:00';
                    $outTime2 = '00:00';
                    if (!is_null($row->jam_masuk) && !is_null($row->jam_pulang)) {
                        foreach ($arr_get_absen_enroll as $key2 => $row2) {
                            if (!empty($row->jam_masuk) && !empty($row->jam_pulang)) {
                                $allowed_time_in_before = date('H:i:s', strtotime($row->jam_masuk) - 7200); //-2jam
                                $allowed_time_in_before = $allowed_time_in_before != "00:00:00" ? $allowed_time_in_before : "24:00:00";

                                $allowed_time_in_after = date('H:i:s', strtotime($row->jam_masuk) + 14400); //4jam
                                $allowed_time_in_after = $allowed_time_in_after != "00:00:00" ? $allowed_time_in_after : "24:00:00";

                                $allowed_time_out_before = date('H:i:s', strtotime($row->jam_pulang) - 7200); //-2jam
                                $allowed_time_out_before = $allowed_time_out_before != "00:00:00" ? $allowed_time_out_before : "24:00:00";

                                $allowed_time_out_after = date('H:i:s', strtotime($row->jam_pulang) + 14400); //4jam
                                $allowed_time_out_after = $allowed_time_out_after != "00:00:00" ? $allowed_time_out_after : "24:00:00";
                            }

                            if ($row2->jenis == 'in' || $row2->jenis == 'out') {
                                if (!empty($row->jam_masuk) && !empty($row->jam_pulang)) {
                                    if ($row2->waktu >= $allowed_time_in_before && $row2->waktu <= $allowed_time_in_after) {
                                        $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in1'] = to_date_format($row2->waktu, 'H:i');
                                    } elseif ($row2->waktu >= $allowed_time_out_before && $row2->waktu <= $allowed_time_out_after) {
                                        $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out1'] = to_date_format($row2->waktu, 'H:i');
                                    }
                                }

                                $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in']  = isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in3']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in3'] : (isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in2']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in2'] : (isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in1']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['in1'] : ''));
                                $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out'] = isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out3']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out3'] : (isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out2']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out2'] : (isset($tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out1']) ? $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['out1'] : ''));

                                $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['jenis'] = '-';
                            } else {
                                $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['jenis'] = $row2->jenis;
                            }
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['keterangan'] = $row2->keterangan;
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['uraian']     = $row2->uraian;
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['singkatan']  = $row2->singkatan;

                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['allowed_time_in_before']  = $allowed_time_in_before;
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['allowed_time_in_after']   = $allowed_time_in_after;
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['allowed_time_out_before'] = $allowed_time_out_before;
                            $tmp['data'][$key]['absen'][to_date_format($row2->tanggal, 'd')]['allowed_time_out_after']  = $allowed_time_out_after;
                        }
                    }
                    // }
                }
            } else {
                $tmp['data'] = array();
            }

            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_absen_enroll($unor, $month, $year, $nip, $day = '', $tipe_pegawai)
    {
        if (!empty($unor) && !empty($month) && !empty($year) && !empty($nip)) {
            if (!empty($day)) {
                $where = array(
                    // 'absen_enroll.PNS_UNOR'       => $unor,
                    'DAY(absen_enroll.tanggal)'   => $day,
                    'MONTH(absen_enroll.tanggal)' => $month,
                    'year(absen_enroll.tanggal)'  => $year,
                    'absen_enroll.PNS_PNSNIP'     => $nip,
                );
            } else {
                $where = array(
                    // 'absen_enroll.PNS_UNOR'       => $unor,
                    'MONTH(absen_enroll.tanggal)' => $month,
                    'year(absen_enroll.tanggal)'  => $year,
                    'absen_enroll.PNS_PNSNIP'     => $nip,
                );
            }

            if ($tipe_pegawai != 0) {
                $data = $this->absen_enroll_model->all(
                    array(
                        'fields'    => 'absen_enroll.code, absen_enroll.PNS_PNSNIP, absen_enroll.PNS_UNOR, absen_enroll.tanggal, absen_enroll.waktu, absen_enroll.jenis, absen_enroll.keterangan, absen_enroll.uraian, kehadiran.singkatan',
                        'left_join' => array(
                            'kehadiran' => 'kehadiran.id = absen_enroll.keterangan',
                        ),
                        'where'     => $where,
                        'like'      => array(
                            'absen_enroll.PNS_PNSNIP' => 'TKD',
                        ),
                        'order_by'  => 'absen_enroll.tanggal ASC, absen_enroll.waktu ASC',
                    )
                );
            } else {
                $data = $this->absen_enroll_model->all(
                    array(
                        'fields'    => 'absen_enroll.code, absen_enroll.PNS_PNSNIP, absen_enroll.PNS_UNOR, absen_enroll.tanggal, absen_enroll.waktu, absen_enroll.jenis, absen_enroll.keterangan, absen_enroll.uraian, kehadiran.singkatan',
                        'left_join' => array(
                            'kehadiran' => 'kehadiran.id = absen_enroll.keterangan',
                        ),
                        'where'     => $where,
                        'not_like'  => array(
                            'absen_enroll.PNS_PNSNIP' => 'TKD',
                        ),
                        'order_by'  => 'absen_enroll.tanggal ASC, absen_enroll.waktu ASC',
                    )
                );
            }
        } else {
            $data = array();
        }
        return $data;
    }

    public function index()
    {
        $data['page_title'] = 'Rekap Bulanan';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'rekap bulanan', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'rekap_bulanan';

        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');

        $this->render('rekap_bulanan/index', $data);
    }

    public function report()
    {
        if ($this->input->get()) {
            $unor         = $this->input->get('unor', true);
            $month        = $this->input->get('month', true);
            $year         = $this->input->get('year', true);
            $tipe_pegawai = $this->input->get('tipe_pegawai', true);

            $data['id_groups']    = get_session('id_groups');
            $data['unor']         = $unor;
            $data['month']        = $month;
            $data['year']         = $year;
            $data['tipe_pegawai'] = $tipe_pegawai;

            if (!empty($unor) && !empty($month) && !empty($year)) {
                if ($unor == get_session('unor') || get_session('id_groups') == 5) {
                    $get_dinas = $this->unor_model->first(
                        array(
                            'KD_UNOR' => $unor,
                        )
                    );
                    $data['dinas'] = $get_dinas ? strtoupper($get_dinas->NM_UNOR) . ' KABUPATEN KOTAWARINGIN BARAT' : '';

                    $data['atasan_skpd'] = $this->atasan_skpd_model->all(
                        array(
                            'fields'      => "atasan_skpd.title, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA",
                            'left_join'   => array(
                                'pns' => "pns.id_master_kelas_jabatan = atasan_skpd.id_master_kelas_jabatan",
                            ),
                            'where_false' => array(
                                'atasan_skpd.unor'                       => "'{$unor}'",
                                'pns.PNS_PNSNIP NOT IN '                 => "(SELECT nip FROM pns_ex)",
                                'atasan_skpd.id_master_kelas_jabatan !=' => '0',
                            ),
                        ),
                        false
                    );

                } else {
                    show_404();
                }
            }
            $this->load->view('rekap_bulanan/report', $data);
        }
    }
}
