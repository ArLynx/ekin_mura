<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
    }

    public function index()
    {
        if (!$this->input->post()) {
            $data['page_title'] = 'Pengaturan';
            $data['breadcrumb'] = [
                ['link' => '', 'title' => 'Pengaturan', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'setting';

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/setting",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['setting'] = $this->makeRequest($requireOption)->data;

            $data['setting_placeholder'] = [
                '4'  => 'Tanggal Maksimal Input Kinerja',
                '5'  => 'Tanggal Maksimal Approve Kinerja',
                '13' => 'Tanggal Maksimal Perubahan Presensi',
            ];

            $this->render('setting/index', $data);
        } else {
            $post = $this->input->post();
            $body = [];
            foreach ($post as $key => $value) {
                $body[$key] = $value;
            }

            $requireOption = [
                'method'      => 'PUT',
                'url'         => $this->svc . "api/setting",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => $body,
                'returnArray' => true,
            ];
            $this->makeRequest($requireOption);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/setting');
        }
    }

}
