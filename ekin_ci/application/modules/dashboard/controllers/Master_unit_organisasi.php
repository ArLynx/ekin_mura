<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_unit_organisasi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_unit_organisasi_model');
        $this->load->model('unor_model');
        $this->page_title = 'Master Unit Organisasi';
        $this->load->library('session');
    }

    public function get_data()
    {
        $selected_sopd = $this->input->get('selected_sopd', true);
        if ($selected_sopd) {
            $data = $this->master_unit_organisasi_model->all(
                array(
                    'where'    => array(
                        'unor' => decode_crypt($selected_sopd),
                        'status' => 'aktif'
                    ),
                    'order_by' => 'index_jabatan ASC',
                )
            );
            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_encrypt']   = encode_crypt($row->id);
                $tmp[$key]['unor_encrypt'] = $selected_sopd;
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {

        $data['unor_selected'] = $this->session->unor_select;
        $data['nama_unor'] = $this->session->nama_unor;
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Unit Organisasi', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('master_unit_organisasi/list', $data);
    }

    public function add($unor_encrypt = null)
    {
        if (is_null($unor_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unit_organisasi', 'unit organisasi', 'required');

        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => decode_crypt($unor_encrypt),
            )
        );
        if (!$check_unor_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-unit-organisasi'), 'title' => 'Master Unit Organisasi', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Master Unit Organisasi', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'master';

            $data['selected_unor'] = $check_unor_exists->NM_UNOR;
            $this->render('master_unit_organisasi/edit', $data);
        } else {
            $data = array(
                'unor'            => decode_crypt($unor_encrypt),
                'unit_organisasi' => $this->input->post('unit_organisasi', true),
                'index_jabatan' => $this->input->post('index_jabatan', true),
            );
            $this->master_unit_organisasi_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master-unit-organisasi');
        }
    }

    public function edit($id_encrypt = null, $unor_encrypt = null)
    {
        if (is_null($id_encrypt) && is_null($unor_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unit_organisasi', 'unit organisasi', 'required');

        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => decode_crypt($unor_encrypt),
            )
        );
        if (!$check_unor_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['master_unit_organisasi'] = $this->master_unit_organisasi_model->first(
                array('id' => decode_crypt($id_encrypt))
            );
            if (!$data['master_unit_organisasi']) {
                show_404();
            }
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-unit-organisasi'), 'title' => 'Master Unit Organisasi', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Master Unit Organisasi', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'master';

            $data['selected_unor'] = $check_unor_exists->NM_UNOR;

            $this->render('master_unit_organisasi/edit', $data);
        } else {
            $data = array(
                'unor'            => decode_crypt($unor_encrypt),
                'unit_organisasi' => $this->input->post('unit_organisasi', true),
                'index_jabatan' => $this->input->post('index_jabatan', true),
            );
           
           
           
           
            $action = $this->master_unit_organisasi_model->edit(decode_crypt($id_encrypt), $data);

            $_SESSION['unor_select'] = decode_crypt($unor_encrypt);
            $_SESSION['nama_unor'] = $check_unor_exists->NM_UNOR;


            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master-unit-organisasi');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {
            $action = $this->master_unit_organisasi_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master-unit-organisasi');
        }
    }

}
