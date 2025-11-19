<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_harga_kelas_jabatan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_index_tpp_model');
        $this->load->model('master_tukin_bpk_model');

        $this->page_title = 'Master Harga Kelas Jabatan';
    }

    public function get_data()
    {
        $selected_year = $this->input->get('year', true);
        if ($selected_year) {
            $data = $this->master_tukin_bpk_model->all([
                'fields'    => 'master_tukin_bpk.*, master_koef_kelas_jabatan.koef, master_jabatan_pns.jabatan_pns AS jabatan',
                'left_join' => [
                    'master_koef_kelas_jabatan' => "master_koef_kelas_jabatan.kelas_jabatan = master_tukin_bpk.kelas_jabatan AND master_koef_kelas_jabatan.tahun = {$selected_year}",
                    'master_jabatan_pns'        => 'master_jabatan_pns.id = master_koef_kelas_jabatan.id_master_jabatan_pns',
                ],
                'order_by'  => 'master_tukin_bpk.kelas_jabatan',
            ]);
            $get_index_tpp = $this->master_index_tpp_model->first(
                array(
                    'tahun' => $selected_year,
                )
            );
            if ($get_index_tpp) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    foreach ($row as $childkey => $childrow) {
                        $tmp[$key][$childkey] = $childrow;
                    }
                    $tmp[$key]['ikfd']      = $get_index_tpp->ikfd;
                    $tmp[$key]['ikk']       = $get_index_tpp->ikk;
                    $tmp[$key]['ippd']      = $get_index_tpp->ippd;
                    $tmp[$key]['tpp_basic'] = format_currency($get_index_tpp->ikfd * $get_index_tpp->ikk * $get_index_tpp->ippd * $row->tukin_bpk * $row->koef, false);
                }
                $data = $tmp;
            } else {
                $data = array();
            }
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Harga Kelas Jabatan', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');

        $get_all_year     = file_get_contents($link_get_all_year);
        $data['all_year'] = json_decode($get_all_year);
        $this->render('master_harga_kelas_jabatan/list', $data);
    }

}
