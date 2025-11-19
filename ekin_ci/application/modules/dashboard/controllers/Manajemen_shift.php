<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_shift extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
    }

    public function get_data()
    {
        $unor            = $this->input->get('unor', true);
        $id_tipe_pegawai = $this->input->get('id_tipe_pegawai', true);
        $month           = $this->input->get('month', true);
        $year            = $this->input->get('year', true);

        set_session([
            'selected_sopd_shift'         => $unor,
            'selected_tipe_pegawai_shift' => $id_tipe_pegawai,
            'selected_month_shift'        => $month,
            'selected_year_shift'         => $year,
        ]);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_manajemen_shift?unor={$unor}&id_tipe_pegawai={$id_tipe_pegawai}&month={$month}&year={$year}",
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
        $data['page_title'] = 'Manajemen Shift';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'manajemen shift', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'manajemen_shift';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $link_get_all_tipe_pegawai = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_tipe_pegawai" : base_url('api/get_all_tipe_pegawai');
        $get_all_tipe_pegawai      = file_get_contents($link_get_all_tipe_pegawai);
        $data['all_tipe_pegawai']  = json_decode($get_all_tipe_pegawai);

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $this->render('manajemen_shift/list', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $modal_id_tipe_pegawai = $this->input->post('modal_id_tipe_pegawai', true);
            $modal_unor            = $this->input->post('modal_unor', true);
            $modal_nip             = $this->input->post('modal_nip', true);
            $modal_mulai_tanggal   = $this->input->post('modal_mulai_tanggal', true);
            $modal_sampai_tanggal  = $this->input->post('modal_sampai_tanggal', true);
            $modal_absen_masuk     = $this->input->post('modal_absen_masuk', true);
            $modal_absen_pulang    = $this->input->post('modal_absen_pulang', true);

            if ($modal_unor &&
                $modal_nip &&
                $modal_mulai_tanggal &&
                $modal_sampai_tanggal &&
                $modal_absen_masuk &&
                $modal_absen_pulang) {

                $id_groups = get_session('id_groups');
                if ($id_groups != 1 && $id_groups != 5) {
                    if (get_session('unor') != $modal_unor) {
                        $this->session->set_flashdata('message', array('message' => 'Inputan tidak diijinkan', 'class' => 'alert-danger'));
                        redirect('dashboard/manajemen_shift');
                    }
                }

                $data_post = [];

                $getDateFromRange = get_date_from_range($modal_mulai_tanggal, $modal_sampai_tanggal);

                foreach ($getDateFromRange as $row) {
                    $data_post[] = [
                        'id_tipe_pegawai' => $modal_id_tipe_pegawai,
                        'unor'            => $modal_unor,
                        'nip'             => $modal_nip,
                        'tanggal'         => $row,
                        'absen_masuk'     => $modal_absen_masuk,
                        'absen_pulang'    => $modal_absen_pulang,
                        'created_at'      => $this->now,
                    ];
                }

                $requireOption = [
                    'method'      => 'POST',
                    'url'         => $this->svc . "api/save_manajemen_shift",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => $data_post,
                    'returnArray' => true,
                ];
                $save = $this->makeRequest($requireOption);

                if ($save) {
                    $this->session->set_flashdata('message', ['message' => $save->message, 'class' => ($save->status == true ? 'alert-success' : 'alert-danger')]);
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong..', 'class' => 'alert-danger'));
                }
            } else {
                $this->session->set_flashdata('message', array('message' => 'Semua inputan wajib diisi..', 'class' => 'alert-danger'));
            }

            redirect('dashboard/manajemen_shift');
        }
    }

    public function delete()
    {
        if ($this->input->post()) {
            $modal_id_tipe_pegawai = $this->input->post('modal_id_tipe_pegawai', true);
            $modal_unor            = $this->input->post('modal_unor', true);
            $modal_nip             = $this->input->post('modal_nip', true);
            $modal_mulai_tanggal   = $this->input->post('modal_mulai_tanggal', true);
            $modal_sampai_tanggal  = $this->input->post('modal_sampai_tanggal', true);

            if ($modal_unor &&
                $modal_nip &&
                $modal_mulai_tanggal &&
                $modal_sampai_tanggal) {

                $id_groups = get_session('id_groups');
                if ($id_groups != 1 && $id_groups != 5) {
                    if (get_session('unor') != $modal_unor) {
                        $this->session->set_flashdata('message', array('message' => 'Inputan tidak diijinkan', 'class' => 'alert-danger'));
                        redirect('dashboard/manajemen_shift');
                    }
                }

                $optionGetDetailPNS = [
                    'method'      => 'GET',
                    'url'         => $this->svc . "api/get_detail_pns?nip={$modal_nip}",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [],
                    'returnArray' => true,
                ];
                $get_detail_pns = $this->makeRequest($optionGetDetailPNS);
                $detail_pns     = $get_detail_pns ? $get_detail_pns->data : null;

                if (is_null($detail_pns)) {
                    $this->session->set_flashdata('message', array('message' => 'Pegawai tidak ditemukan', 'class' => 'alert-danger'));
                    redirect('dashboard/manajemen_shift');
                }

                $data_delete = [
                    'id_pns' => $detail_pns->id,
                    'nip'    => $detail_pns->PNS_PNSNIP,
                    'uraian' => 'up_face',
                ];

                $getDateFromRange = get_date_from_range($modal_mulai_tanggal, $modal_sampai_tanggal);

                foreach ($getDateFromRange as $row) {
                    $data_delete['tanggal'][] = $row;
                }

                $requireOption = [
                    'method'      => 'DELETE',
                    'url'         => $this->svc . "api/delete_manajemen_shift",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => $data_delete,
                    'returnArray' => true,
                ];
                $delete = $this->makeRequest($requireOption);

                if ($delete) {
                    $this->session->set_flashdata('message', ['message' => $delete->message, 'class' => ($delete->status == true ? 'alert-success' : 'alert-danger')]);
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong..', 'class' => 'alert-danger'));
                }
            } else {
                $this->session->set_flashdata('message', array('message' => 'Semua inputan wajib diisi..', 'class' => 'alert-danger'));
            }

            redirect('dashboard/manajemen_shift');

        }
    }

}
