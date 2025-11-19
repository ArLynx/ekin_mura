<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Information extends MY_Controller
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
            $data['active_menu'] = 'information';

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/information",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['information'] = $this->makeRequest($requireOption)->data;

            $this->render('information/index', $data);
        } else {
            $requireOption = [
                'method'      => 'PUT',
                'url'         => $this->svc . "api/information",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [
                    'title'      => $this->input->post('title', true),
                    'content'    => $this->input->post('content'),
                    'updated_at' => $this->now,
                ],
                'returnArray' => true,
            ];
            $this->makeRequest($requireOption);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/information');
        }
    }

}
