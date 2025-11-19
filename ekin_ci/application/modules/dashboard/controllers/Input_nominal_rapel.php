<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Input_nominal_rapel extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('nominal_rapel_model');
        $this->load->model('pns_model');
        $this->load->model('unor_model');
    }

    public function get_data()
    {
        $selected_sopd = $this->input->get('selected_sopd', true);

        if ($selected_sopd) {
            $where = array(
                'pns.PNS_UNOR' => decode_crypt($selected_sopd),
            );
        } else {
            $where = array();
        }

        $data = $this->nominal_rapel_model->all(
            array(
                'fields'    => "nominal_rapel.*, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA",
                'left_join' => array(
                    'pns' => 'pns.PNS_PNSNIP = nominal_rapel.pns_pnsnip',
                ),
                'where'     => $where,
            )
        );

        $tmp = array();
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                $tmp[$key][$childkey] = $childrow;
            }
            $tmp[$key]['id_encrypt']       = encode_crypt($row->id);
            $tmp[$key]['bulan_rapel_text'] = indonesian_date(strtotime("{$row->tahun_rapel}-{$row->bulan_rapel}-01"), 'F');
        }
        $data = $tmp;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Rapel TPP';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Rapel TPP', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'Input Selisih';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('input_nominal_rapel/list', $data);
    }

    public function add($unor_encrypt = null)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pns_pnsnip', 'PNS', 'required');
        $this->form_validation->set_rules('nominal', 'nominal', 'required');
        $this->form_validation->set_rules('bulan_rapel', 'bulan rapel', 'required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'required');

        if (!is_null($unor_encrypt)) {
            $check_unor_exists = $this->unor_model->first(
                array(
                    'KD_UNOR' => decode_crypt($unor_encrypt),
                )
            );
            if (!$check_unor_exists) {
                show_404();
            }
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/input-nominal-rapel'), 'title' => 'Rapel TPP', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Rapel TPP', 'icon' => '', 'active' => '1'],
            ];

            $id_groups = get_session('id_groups');
            if ($id_groups == 1 || $id_groups == 5) {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            } else {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            }
            $data['all_sopd'] = json_decode($get_all_sopd);

            $data['selected_unor'] = !is_null($unor_encrypt) ? $check_unor_exists->KD_UNOR : null;

            $this->render('input_nominal_rapel/edit', $data);
        } else {
            $get_bulan_rapel     = $this->input->post('bulan_rapel');
            $explode_bulan_rapel = explode('-', $get_bulan_rapel);
            $bulan_rapel         = $explode_bulan_rapel ? $explode_bulan_rapel[1] : null;
            $tahun_rapel         = $explode_bulan_rapel ? $explode_bulan_rapel[0] : null;

            $data = array(
                'pns_pnsnip'  => $this->input->post('pns_pnsnip', true),
                'nominal'     => replace_dot($this->input->post('nominal', true)),
                'bulan_rapel' => $bulan_rapel,
                'tahun_rapel' => $tahun_rapel,
                'keterangan'  => $this->input->post('keterangan', true),
                'created_at'  => $this->now,
            );
            $this->nominal_rapel_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/input-nominal-rapel');
        }
    }

    public function edit($id_encrypt = null)
    {
        if (is_null($id_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pns_pnsnip', 'PNS', 'required');
        $this->form_validation->set_rules('nominal', 'nominal', 'required');
        $this->form_validation->set_rules('bulan_rapel', 'bulan rapel', 'required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'required');

        $check_exists = $this->nominal_rapel_model->first(decode_crypt($id_encrypt));
        if (!$check_exists) {
            show_404();
        } else {
            $detail_pns = $this->pns_model->get_detail_pns($check_exists->pns_pnsnip);
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/input-nominal-rapel'), 'title' => 'Rapel TPP', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Rapel TPP', 'icon' => '', 'active' => '1'],
            ];

            $id_groups = get_session('id_groups');
            if ($id_groups == 1 || $id_groups == 5) {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            } else {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            }
            $data['all_sopd'] = json_decode($get_all_sopd);

            $data['selected_unor'] = isset($detail_pns) ? $detail_pns->PNS_UNOR : null;
            $data['detail_pns']    = $detail_pns;

            $data['nominal_rapel']              = $check_exists;
            $data['nominal_rapel']->bulan_rapel = $data['nominal_rapel']->tahun_rapel . '-' . $data['nominal_rapel']->bulan_rapel;

            $this->render('input_nominal_rapel/edit', $data);
        } else {
            $get_bulan_rapel     = $this->input->post('bulan_rapel');
            $explode_bulan_rapel = explode('-', $get_bulan_rapel);
            $bulan_rapel         = $explode_bulan_rapel ? $explode_bulan_rapel[1] : null;
            $tahun_rapel         = $explode_bulan_rapel ? $explode_bulan_rapel[0] : null;

            $data = array(
                'pns_pnsnip'  => $this->input->post('pns_pnsnip', true),
                'nominal'     => replace_dot($this->input->post('nominal', true)),
                'bulan_rapel' => $bulan_rapel,
                'tahun_rapel' => $tahun_rapel,
                'keterangan'  => $this->input->post('keterangan', true),
                'updated_at'  => $this->now,
            );
            $this->nominal_rapel_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/input-nominal-rapel');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {
            $action = $this->nominal_rapel_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/input-nominal-rapel');
        }
    }

}
