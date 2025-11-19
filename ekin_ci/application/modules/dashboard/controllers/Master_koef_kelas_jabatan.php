<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_koef_kelas_jabatan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_jabatan_pns_model');
        $this->load->model('master_koef_kelas_jabatan_model');
        $this->page_title = 'Master Koef Kelas Jabatan';
    }

    public function get_data()
    {
        $data = $this->master_koef_kelas_jabatan_model->all(
            array(
                'fields'   => 'master_koef_kelas_jabatan.*, master_jabatan_pns.jabatan_pns',
                'join'     => array(
                    'master_jabatan_pns' => 'master_jabatan_pns.id = master_koef_kelas_jabatan.id_master_jabatan_pns',
                ),
                'order_by' => 'master_koef_kelas_jabatan.tahun ASC, master_koef_kelas_jabatan.kelas_jabatan DESC, master_koef_kelas_jabatan.id_master_jabatan_pns DESC',
            )
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Koef Kelas Jabatan', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';

        $this->render('master_koef_kelas_jabatan/list', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_master_jabatan_pns', 'jabatan pns', 'required');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('koef', 'koef', 'required');
        $this->form_validation->set_rules('tahun', 'tahun', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-koef-kelas-jabatan'), 'title' => 'Master Koef Kelas Jabatan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Master Koef Kelas Jabatan', 'icon' => '', 'active' => '1'],
            ];

            $data['master_jabatan_pns'] = $this->master_jabatan_pns_model->all();

            $this->render('master_koef_kelas_jabatan/edit', $data);
        } else {
            $data = array(
                'id_master_jabatan_pns' => $this->input->post('id_master_jabatan_pns', true),
                'kelas_jabatan'         => $this->input->post('kelas_jabatan', true),
                'koef'                  => $this->input->post('koef', true),
                'tahun'                 => $this->input->post('tahun', true),
            );
            $action = $this->master_koef_kelas_jabatan_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master-koef-kelas-jabatan');
        }
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_master_jabatan_pns', 'jabatan pns', 'required');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('koef', 'koef', 'required');
        $this->form_validation->set_rules('tahun', 'tahun', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-koef-kelas-jabatan'), 'title' => 'Master Koef Kelas Jabatan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Master Koef Kelas Jabatan', 'icon' => '', 'active' => '1'],
            ];

            $data['master_jabatan_pns']        = $this->master_jabatan_pns_model->all();
            $data['master_koef_kelas_jabatan'] = $this->master_koef_kelas_jabatan_model->first($id);

            $this->render('master_koef_kelas_jabatan/edit', $data);
        } else {
            $data = array(
                'id_master_jabatan_pns' => $this->input->post('id_master_jabatan_pns', true),
                'kelas_jabatan'         => $this->input->post('kelas_jabatan', true),
                'koef'                  => $this->input->post('koef', true),
                'tahun'                 => $this->input->post('tahun', true),
            );
            $action = $this->master_koef_kelas_jabatan_model->edit($id, $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master-koef-kelas-jabatan');
        }
    }

}
