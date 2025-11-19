<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Absen_libur extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('absen_libur_model');
    }

    public function get_data()
    {
        $data = $this->absen_libur_model->all([
            'order_by' => 'tanggal DESC',
        ]);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Absen Libur';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'setup', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Absen Libur', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'setup';

        $this->render('absen_libur/list', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal', 'tanggal libur', 'required');
        $this->form_validation->set_rules('nama_libur', 'keterangan', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah Absen Libur';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/absen-libur'), 'title' => 'Absen Libur', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Absen Libur', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'setup';
            $this->render('absen_libur/edit', $data);
        } else {
            $data = array(
                'tanggal'    => $this->input->post('tanggal', true),
                'nama_libur' => $this->input->post('nama_libur', true),
            );
            $action = $this->absen_libur_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/absen-libur');
        }
    }

    public function delete()
    {
        $id = $this->input->get('id', true);
        if ($id) {
            $action = $this->absen_libur_model->delete($id);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/absen-libur');
        }
    }

}
