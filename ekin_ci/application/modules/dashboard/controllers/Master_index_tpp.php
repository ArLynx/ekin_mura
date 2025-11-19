<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_index_tpp extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_index_tpp_model');

        $this->page_title = 'Master Index TPP';
    }

    public function get_data()
    {
        $data = $this->master_index_tpp_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'master index TPP', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';
        $this->render('master_index_tpp/list', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ikfd', 'ikfd', 'required');
        $this->form_validation->set_rules('ikk', 'ikk', 'required');
        $this->form_validation->set_rules('ippd', 'ippd', 'required');
        $this->form_validation->set_rules('tahun', 'tahun', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $this->render('master_index_tpp/edit', $data);
        } else {
            $data = array(
                'ikfd'  => $this->input->post('ikfd', true),
                'ikk'   => $this->input->post('ikk', true),
                'ippd'  => $this->input->post('ippd', true),
                'tahun' => $this->input->post('tahun', true),
            );
            $action = $this->master_index_tpp_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_index_tpp');
        }
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ikfd', 'ikfd', 'required');
        $this->form_validation->set_rules('ikk', 'ikk', 'required');
        $this->form_validation->set_rules('ippd', 'ippd', 'required');
        $this->form_validation->set_rules('tahun', 'tahun', 'required');

        if ($this->form_validation->run() == false) {
            $data['master_index_tpp'] = $this->master_index_tpp_model->first(
                array('id' => $id)
            );
            if (!$data['master_index_tpp']) {
                show_404();
            }
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $this->render('master_index_tpp/edit', $data);
        } else {
            $data = array(
                'ikfd'  => $this->input->post('ikfd', true),
                'ikk'   => $this->input->post('ikk', true),
                'ippd'  => $this->input->post('ippd', true),
                'tahun' => $this->input->post('tahun', true),
            );
            $action = $this->master_index_tpp_model->edit($id, $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_index_tpp');
        }
    }

    public function delete($id)
    {
        $action = $this->master_index_tpp_model->delete($id);

        $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
        redirect('dashboard/master_index_tpp');
    }

}
