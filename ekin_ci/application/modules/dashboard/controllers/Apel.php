<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Apel extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('unor_model');
 

        $this->page_title = 'Apel';
        $this->auth       = false;
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Verifikasi SKPD', 'icon' => '', 'active' => '1'],
        ];

           $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5 || $id_groups == 6 || $this->_user_login->PNS_PNSNIP == '197712242005012006') { //superadmin & BPKAD
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }

        $data['all_sopd'] = json_decode($get_all_sopd);


        $this->render('apel/list', $data);
    }
}