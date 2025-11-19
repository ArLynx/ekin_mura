<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Verifikasi_skpd extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('unor_model');
        $this->load->model('sudah_verifikasi_skpd_model');

        $this->page_title = 'Verifikasi SKPD';
        $this->auth       = false;
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Verifikasi SKPD', 'icon' => '', 'active' => '1'],
        ];

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);
        $data['month']      = date("m");

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $this->render('verifikasi_skpd/list', $data);
    }

    public function get_data()
    {
   
        $id_users       = get_session('id_users');
        $dbkinerja      = get_config_item('dbkinerja');
        $dbpresensi     = get_config_item('dbpresensi');
        $selected_year  = $this->input->get('selected_year', true);
        $selected_month = $this->input->get('selected_month', true);

        $id_groups = get_session('id_groups');

        $year  = $selected_year;
        $month = ($selected_month == 0 ? date("m") : ($selected_month < 10 ? '0' . $selected_month : $selected_month));

        if ($id_groups == 1 || $id_groups == 5) {
            $data = $this->unor_model->all(
                array(
                    'fields'      => "unor.*, IF(
                        sudah_verifikasi_skpd.unor IS NULL,
                        'belum',
                        'sudah'
                    ) AS status, sudah_verifikasi_skpd.tanggal, sudah_verifikasi_skpd.cair, sudah_verifikasi_skpd.tanggal_cair",
                    'left_join'   => array(
                        "{$dbpresensi}.sudah_verifikasi_skpd" => "sudah_verifikasi_skpd.unor = unor.KD_UNOR AND sudah_verifikasi_skpd.bulan = '{$month}' AND sudah_verifikasi_skpd.tahun = '{$year}'",
                    ),
                    // 'where_false' => array(
                    //     'unor.NM_UNOR NOT LIKE ' => "'%PUSKESMAS%' AND unor.NM_UNOR NOT LIKE '%UKK%' AND unor.NM_UNOR NOT LIKE '%RSUD%' ESCAPE '!'",
                    //     'unor.KD_UNOR LIKE ' => "'88%'"
                    // ),
                    'group_by'    => 'KD_UNOR',
                    'order_by'    => 'NM_UNOR ASC',
                )
            );
        } else if ($id_groups == 4) {
            $data = $this->unor_model->all(
                array(
                    'fields'      => "unor.*, IF(
                        sudah_verifikasi_skpd.unor IS NULL,
                        'belum',
                        'sudah'
                    ) AS status, sudah_verifikasi_skpd.tanggal, sudah_verifikasi_skpd.cair, sudah_verifikasi_skpd.tanggal_cair",
                    'left_join'   => array(
                        "{$dbpresensi}.sudah_verifikasi_skpd" => "sudah_verifikasi_skpd.unor = unor.KD_UNOR AND sudah_verifikasi_skpd.bulan = '{$month}' AND sudah_verifikasi_skpd.tahun = '{$year}'",
                        "{$dbkinerja}.pembagian_skpd_penilai" => "pembagian_skpd_penilai.unor = unor.KD_UNOR",
                    ),
                    'where'       => array(
                        // 'pembagian_skpd_penilai.id_users' => $id_users,
                    ),
                    'where_false' => array(
                        // 'unor.NM_UNOR NOT LIKE ' => "'%PUSKESMAS%' AND unor.NM_UNOR NOT LIKE '%UKK%' AND unor.NM_UNOR NOT LIKE '%RSUD%' ESCAPE '!'",
                        // 'unor.KD_UNOR LIKE ' => "'88%'"
                    ),
                    'group_by'    => 'KD_UNOR',
                    'order_by'    => 'NM_UNOR ASC',
                )
            );
        }
        
        $tmp = array();
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                $tmp[$key][$childkey] = $childrow;
            }
            $tmp[$key]['kd_unor_encrypt'] = encode_crypt($row->KD_UNOR);
        }
        $data = $tmp;
       
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));

            
    }

    public function verifikasi_all_skpd()
    {
        $dbkinerja     = get_config_item('dbkinerja');
        $unorvercektop = $this->input->post('unorvercektop', true);
        $countmaxs     = $this->input->post('countmax', true);
        $countmax_ar   = explode(', ', $countmaxs);
        $jml_armax     = count($countmax_ar);
        $summax        = $this->input->post('summax', true);

        if ($jml_armax == $summax) {
            for ($i = 0; $i < count($countmax_ar); $i++) {
                $data_ver_skpd = $this->unor_model->first(
                    array(
                        "unor.KD_UNOR" => $countmax_ar[$i],
                    )
                );

                if ($data_ver_skpd) {
                    $data = array(
                        'unor'    => $countmax_ar[$i],
                        'bulan'   => $this->input->post('bulan', true),
                        'tahun'   => $this->input->post('tahun', true),
                        'tanggal' => $this->now,
                    );

                    $this->sudah_verifikasi_skpd_model->save($data);
                    $this->session->set_flashdata('message', array('message' => 'Semua data berhasil diverifikasi', 'class' => 'alert-success'));
                } else {
                    show_404();
                }
            }
        } else {
            for ($i = 0; $i < count($unorvercektop); $i++) {
                $data_ver_skpd = $this->unor_model->first(
                    array(
                        "unor.KD_UNOR" => $unorvercektop[$i],
                    )
                );

                if ($data_ver_skpd) {
                    $data = array(
                        'unor'    => $unorvercektop[$i],
                        'bulan'   => $this->input->post('bulan', true),
                        'tahun'   => $this->input->post('tahun', true),
                        'tanggal' => $this->now,
                    );

                    $this->sudah_verifikasi_skpd_model->save($data);
                    $this->session->set_flashdata('message', array('message' => 'Data berhasil diverifikasi', 'class' => 'alert-success'));
                } else {
                    show_404();
                }
            }
        }

        redirect('dashboard/verifikasi_skpd');
    }

}
