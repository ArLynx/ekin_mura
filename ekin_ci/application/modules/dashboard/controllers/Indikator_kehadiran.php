<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Indikator_kehadiran extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('absen_enroll_model');
        $this->load->model('kinerja/rekap_indikator_kehadiran_model', 'rekap_indikator_kehadiran_model');
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor = decode_crypt($this->input->get('unor', true));
        } else {
            $unor = get_session('unor');
        }

        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);
        $tipe_pegawai = $this->input->get('tipe_pegawai', true);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_indikator_kehadiran?unor={$unor}&month={$month}&year={$year}&tipe_pegawai={$tipe_pegawai}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data = $this->makeRequest($requireOption);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $data['page_title'] = 'Indikator Kehadiran';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'indikator kehadiran', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'rekapitulasi';

        $requireOption = [
            'method'      => 'GET',
            'url'         => ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_tipe_pegawai?is_tpp=true" : base_url("api/get_tipe_pegawai?is_tpp=true"),
            'headers'     => [],
            'body'        => [],
            'returnArray' => true,
        ];
        $data['all_tipe_pegawai'] = $this->makeRequest($requireOption)->data;

        $data['id_groups'] = $id_groups;
        $this->render('indikator_kehadiran/list', $data);
    }
}
