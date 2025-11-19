<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_kelas_jabatan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('master_unit_organisasi_model');
        $this->load->model('unor_model');
        $this->page_title = 'Master Kelas Jabatan';
    }

    public function get_data()
    {
        $selected_sopd = $this->input->get('selected_sopd', true);
        if ($selected_sopd) {
            $data = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi, master_jabatan_pns.jabatan_pns',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        'master_jabatan_pns'     => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => decode_crypt($selected_sopd),
                            'Status_Jabatan' => 'aktif'
                    ),
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
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
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Kelas Jabatan', 'icon' => '', 'active' => '1'],
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

        $this->render('master_kelas_jabatan/list', $data);
    }

    public function add($unor_encrypt = null)
    {
        if (is_null($unor_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_master_jabatan_pns', 'kategori pns', 'required');
        $this->form_validation->set_rules('id_master_unit_organisasi', 'unit organisasi', 'required');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required');

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
                ['link' => site_url('dashboard/master-kelas-jabatan'), 'title' => 'Master Kelas Jabatan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Master Kelas Jabatan', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'master';

            $link_get_all_master_jabatan_pns = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_master_jabatan_pns" : base_url('api/get_all_master_jabatan_pns');
            $get_all_master_jabatan_pns      = file_get_contents($link_get_all_master_jabatan_pns);
            $data['master_jabatan_pns']      = json_decode($get_all_master_jabatan_pns);

            $data['master_unit_organisasi'] = $this->master_unit_organisasi_model->all(
                array(
                    'where'    => array(
                        'unor' => decode_crypt($unor_encrypt),
                    ),
                    'order_by' => 'unit_organisasi ASC',
                )
            );
            $data['selected_unor'] = $check_unor_exists->NM_UNOR;
            $this->render('master_kelas_jabatan/edit', $data);
        } else {
            $data = array(
                'unor'                      => decode_crypt($unor_encrypt),
                'id_master_jabatan_pns'     => $this->input->post('id_master_jabatan_pns', true),
                'id_master_unit_organisasi' => $this->input->post('id_master_unit_organisasi', true),
                'kelas_jabatan'             => $this->input->post('kelas_jabatan', true),
                'nama_jabatan'              => $this->input->post('nama_jabatan', true),
            );
            $action = $this->master_kelas_jabatan_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_kelas_jabatan');
        }
    }

    public function edit($id_encrypt = null, $unor_encrypt = null)
    {
        if (is_null($id_encrypt) && is_null($unor_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_master_jabatan_pns', 'kategori pns', 'required');
        $this->form_validation->set_rules('id_master_unit_organisasi', 'unit organisasi', 'required');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('nama_jabatan', 'nama jabatan', 'required');

        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => decode_crypt($unor_encrypt),
            )
        );
        if (!$check_unor_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['master_kelas_jabatan'] = $this->master_kelas_jabatan_model->first(
                array('id' => decode_crypt($id_encrypt))
            );
            if (!$data['master_kelas_jabatan']) {
                show_404();
            }
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-kelas-jabatan'), 'title' => 'Master Kelas Jabatan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Master Kelas Jabatan', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'master';

            $link_get_all_master_jabatan_pns = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_master_jabatan_pns" : base_url('api/get_all_master_jabatan_pns');
            $get_all_master_jabatan_pns      = file_get_contents($link_get_all_master_jabatan_pns);
            $data['master_jabatan_pns']      = json_decode($get_all_master_jabatan_pns);

            $data['master_unit_organisasi'] = $this->master_unit_organisasi_model->all(
                array(
                    'where'    => array(
                        'unor' => decode_crypt($unor_encrypt),
                    ),
                    'order_by' => 'unit_organisasi ASC',
                )
            );
            $data['selected_unor'] = $check_unor_exists->NM_UNOR;
            $this->render('master_kelas_jabatan/edit', $data);
        } else {
            $data = array(
                'unor'                      => decode_crypt($unor_encrypt),
                'id_master_jabatan_pns'     => $this->input->post('id_master_jabatan_pns', true),
                'id_master_unit_organisasi' => $this->input->post('id_master_unit_organisasi', true),
                'kelas_jabatan'             => $this->input->post('kelas_jabatan', true),
                'nama_jabatan'              => $this->input->post('nama_jabatan', true),
            );
            $action = $this->master_kelas_jabatan_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_kelas_jabatan');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {
            $action = $this->master_kelas_jabatan_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_kelas_jabatan');
        }
    }

}
