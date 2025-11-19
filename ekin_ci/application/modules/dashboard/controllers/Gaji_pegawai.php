<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gaji_pegawai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('pns_model');
        $this->load->model('kinerja/gaji_pegawai_model', 'gaji_pegawai_model');
        $this->page_title = 'Gaji Pegawai';
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor = decode_crypt($this->input->get('unor', true));
        } else {
            $unor = get_session('unor');
        }

        if (!empty($unor)) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_gaji_pegawai/{$unor}",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data = $this->makeRequest($requireOption)->data;

            $tmp = [];
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['nip_encrypt'] = encode_crypt($row->PNS_PNSNIP);
            }

            $data = $tmp;
        } else {
            $data = [];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'setup', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Gaji Pegawai', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'setup';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('gaji_pegawai/list', $data);
    }

    public function action()
    {
        $modal_nip_encrypt    = $this->input->post('modal_nip_encrypt', true);
        $modal_unor_encrypt   = $this->input->post('modal_unor_encrypt', true);
        $modal_gaji_kotor     = $this->input->post('modal_gaji_kotor', true);
        $modal_iw_sudah_bayar = $this->input->post('modal_iw_sudah_bayar', true);

        if ($modal_nip_encrypt && $modal_unor_encrypt && $modal_gaji_kotor && $modal_iw_sudah_bayar) {

            $nip              = decode_crypt($modal_nip_encrypt);
            $unor             = decode_crypt($modal_unor_encrypt);
            $check_pns_exists = $this->pns_model->first([
                'PNS_PNSNIP' => $nip,
            ]);

            if ($check_pns_exists) {
                $check_gaji_pegawai_exists = $this->gaji_pegawai_model->first([
                    'nip' => $nip,
                ]);

                $data_gaji_pegawai = [
                    'nip'            => $nip,
                    'gaji_kotor'     => replace_dot($modal_gaji_kotor),
                    'iw_sudah_bayar' => replace_dot($modal_iw_sudah_bayar),
                ];

                if ($check_gaji_pegawai_exists) {
                    $this->gaji_pegawai_model->edit($check_gaji_pegawai_exists->id, $data_gaji_pegawai);
                } else {
                    $this->gaji_pegawai_model->save($data_gaji_pegawai);
                }

                set_session(['selected_sopd_gaji_pegawai' => $unor]);

                $message = 'Action Successfully..';
                $class   = 'alert-success';
            } else {
                $message = 'PNS tidak ditemukan';
                $class   = 'alert-danger';
            }
            $this->session->set_flashdata('message', array('message' => $message, 'class' => $class));
            redirect('dashboard/gaji_pegawai');
        } else {
            $this->session->set_flashdata('message', array('message' => 'Inputan tidak boleh kosong', 'class' => 'alert-danger'));
            redirect('dashboard/gaji_pegawai');
        }
    }
}
