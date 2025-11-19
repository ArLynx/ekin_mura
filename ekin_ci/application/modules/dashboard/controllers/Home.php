<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_device_model');
        $this->load->model('master_tukin_bpk_model');
        $this->load->model('mutasi_detail_model');
        $this->load->model('unor_model');
        $this->load->model('pns_model');
    }

    public function get_count_pns_by_unor()
    {
        $month = date('m');
        $year  = date('Y');
        $data  = $this->pns_model->all(
            array(
                'fields'      => 'unor.NM_UNOR, COUNT(pns.id) AS count',
                'left_join'   => array(
                    'unor' => 'unor.KD_UNOR = pns.PNS_UNOR',
                ),
                'where_false' => array(
                    'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                ),
                'not_like'    => array(
                    'pns.PNS_PNSNIP' => 'TKD',
                ),
                'group_by'    => 'pns.PNS_UNOR',
                'order_by'    => 'unor.NM_UNOR ASC',
            )
        );
        $tmp = array();
        foreach ($data as $key => $row) {
            $tmp[$key]['name'] = $row->NM_UNOR;
            $tmp[$key]['y']    = (int) $row->count;
        }
        $data = $tmp;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_count_non_pns_by_unor()
    {
        $data = $this->pns_model->all(
            array(
                'fields'    => 'unor.NM_UNOR, COUNT(pns.id) AS count',
                'left_join' => array(
                    'unor' => 'unor.KD_UNOR = pns.PNS_UNOR',
                ),
                'like'      => array(
                    'pns.PNS_PNSNIP' => 'TKD',
                ),
                'group_by'  => 'pns.PNS_UNOR',
                'order_by'  => 'unor.NM_UNOR ASC',
            )
        );
        $tmp = array();
        foreach ($data as $key => $row) {
            $tmp[$key]['name'] = $row->NM_UNOR;
            $tmp[$key]['y']    = (int) $row->count;
        }
        $data = $tmp;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Dashboard';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'dashboard', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'home';

        if (get_session('id_groups') == '2') {
            $data['count_pns'] = $this->pns_model->count_false(
                array(
                    'PNS_UNOR'             => "'{$this->_user_login->unor}'",
                    'PNS_PNSNIP NOT LIKE ' => "'%TKD%'",
                    'PNS_PNSNIP NOT IN '   => "(SELECT nip FROM pns_ex WHERE unor = '{$this->_user_login->unor}')",
                )
            );
            $data['count_tkd'] = $this->pns_model->count_false(
                array(
                    'PNS_UNOR'           => "'{$this->_user_login->unor}'",
                    'PNS_PNSNIP LIKE '   => "'%TKD%'",
                    'PNS_PNSNIP NOT IN ' => "(SELECT nip FROM pns_ex WHERE unor = '{$this->_user_login->unor}')",
                )
            );
            $data['status_verifikasi_bkpp'] = $this->unor_model->all(
                array(
                    'fields'    => "unor.KD_UNOR, unor.NM_UNOR, IF(sudah_verifikasi_skpd.unor IS NULL, 'belum', 'sudah') AS status, sudah_verifikasi_skpd.tanggal",
                    'left_join' => array(
                        'sudah_verifikasi_skpd' => 'sudah_verifikasi_skpd.unor = unor.KD_UNOR AND sudah_verifikasi_skpd.bulan = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND sudah_verifikasi_skpd.tahun = IF(MONTH(NOW()) = 1, YEAR(CURDATE()) - 1, YEAR(CURDATE()))',
                    ),
                    'where'     => array(
                        'unor.KD_UNOR' => $this->_user_login->unor,
                    ),
                ), false
            );
        } elseif (get_session('id_groups') == '3') {
            $check_mutasi_pending = $this->mutasi_detail_model->all(
                array(
                    'fields'    => 'mutasi_detail.*, mutasi.tanggal, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        'mutasi'                 => 'mutasi.id = mutasi_detail.mutasi_id',
                        'master_kelas_jabatan'   => 'master_kelas_jabatan.id = mutasi_detail.id_master_kelas_jabatan_baru',
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'mutasi_detail.pns_pnsnip' => $this->_user_login->PNS_PNSNIP,
                        'mutasi_detail.status'     => '0', //pending
                    ),
                    'order_by'  => 'mutasi_detail.id DESC',
                    'limit'     => '1',
                ), false
            );

            $data['mutasi_pending'] = $check_mutasi_pending ? ((date('Y-m-d') >= $check_mutasi_pending->tanggal) ? $check_mutasi_pending : null) : null;
        } elseif (get_session('id_groups') == '1' || get_session('id_groups') == '5') {
            $data['skpd_verifikasi_tpp'] = $this->unor_model->all(
                array(
                    'fields'      => "unor.KD_UNOR, unor.NM_UNOR, IF(sudah_verifikasi_skpd.unor IS NULL, 'belum', 'sudah') AS status, sudah_verifikasi_skpd.tanggal",
                    'left_join'   => array(
                        'sudah_verifikasi_skpd' => 'sudah_verifikasi_skpd.unor = unor.KD_UNOR AND sudah_verifikasi_skpd.bulan = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND sudah_verifikasi_skpd.tahun = IF(MONTH(NOW()) = 1, YEAR(CURDATE()) - 1, YEAR(CURDATE()))',
                    ),
                    'where_false' => array(
                        // 'unor.NM_UNOR NOT LIKE ' => "'%PUSKESMAS%' AND unor.NM_UNOR NOT LIKE '%UKK%' AND unor.NM_UNOR NOT LIKE '%RSUD%' ESCAPE '!'",
                        // 'unor.KD_UNOR LIKE '     => "'88%'",
                    ),
                    'order_by'    => 'unor.NM_UNOR ASC',
                )
            );

            $data['master_device'] = $this->master_device_model->all([
                'order_by' => 'instansi ASC',
            ]);
        }

        $this->render('index', $data);
    }
}
