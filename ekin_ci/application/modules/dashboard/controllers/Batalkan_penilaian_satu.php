<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Batalkan_penilaian_satu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('bawahan_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('pns_model');
        $this->load->model('unor_model');

        $this->page_title = 'Batalkan Penilaian Satu';
        $this->auth       = false;
    }

    public function get_data()
    {
        $id_users       = get_session('id_users');
        $dbkinerja      = get_config_item('dbkinerja');
        $selected_year  = $this->input->get('selected_year', true);
        $selected_month = $this->input->get('selected_month', true);
        $selected_sopd  = $this->input->get('selected_sopd', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $nip   = $data['users_ekin']->nip;
        $unor  = decode_crypt($selected_sopd);
        $year  = $selected_year;
        $month = ($selected_month == 0 ? date("m") : ($selected_month < 10 ? '0' . $selected_month : $selected_month));

        $data = $this->bawahan_model->get_pns_skpd_list($year, $month, $nip, $unor);

        $tmp = array();
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                $tmp[$key][$childkey] = $childrow;
            }
            $tmp[$key]['id_encrypt'] = encode_crypt($row->id);
        }
        $data = $tmp;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Batalkan Penilaian Satu', 'icon' => '', 'active' => '1'],
        ];

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);
        $data['month']      = date("m");

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else if ($id_groups == 4) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . 'api/get_pembagian_sopd?id_users=' . $this->_user_login->id : base_url('api/get_pembagian_sopd?id_users=' . $this->_user_login->id);
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('batalkan_penilaian_satu/list', $data);
    }

}
