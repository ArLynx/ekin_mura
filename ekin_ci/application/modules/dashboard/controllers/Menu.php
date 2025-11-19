<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-01T22:41:02+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('menu_model');

        $this->page_title = 'Menu';
    }

    public function get_data()
    {
        $sql  = "SELECT a.id, a.title, a.controller, a.icon, IF(a.id_parent = 0, 'Main Menu', (SELECT b.title FROM menu b WHERE b.id = a.id_parent)) AS parent, a.url, a.order, a.created_at FROM menu a WHERE a.deleted_at IS NULL";
        $data = $this->menu_model->query($sql)->result();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'menu', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'akses';
        $this->render('menu/list', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'menu', 'required');
        $this->form_validation->set_rules('id_parent', 'menu induk', 'required');
        $this->form_validation->set_rules('url', 'url', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/menu'), 'title' => 'menu', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'tambah menu', 'icon' => '', 'active' => '1'],
            ];
            $data['parent'] = $this->menu_model->all();
            $this->render('menu/edit', $data);
        } else {
            $data = array(
                'title'      => $this->input->post('title', true),
                'id_parent'  => $this->input->post('id_parent', true),
                'controller' => $this->input->post('controller', true),
                'url'        => $this->input->post('url', true),
                'order'      => $this->input->post('order', true),
                'icon'       => $this->input->post('icon', true),
                'created_at' => $this->now,
            );
            $action = $this->menu_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/menu');
        }
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'menu', 'required');
        $this->form_validation->set_rules('id_parent', 'menu induk', 'required');
        $this->form_validation->set_rules('url', 'url', 'required');

        if ($this->form_validation->run() == false) {
            $data['menu'] = $this->menu_model->first(
                array('id' => $id)
            );
            $data['parent']     = $this->menu_model->all();
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/menu'), 'title' => 'menu', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'ubah menu', 'icon' => '', 'active' => '1'],
            ];
            $this->render('menu/edit', $data);
        } else {
            $data = array(
                'title'      => $this->input->post('title', true),
                'id_parent'  => $this->input->post('id_parent', true),
                'controller' => $this->input->post('controller', true),
                'url'        => $this->input->post('url', true),
                'order'      => $this->input->post('order', true),
                'icon'       => $this->input->post('icon', true),
                'updated_at' => $this->now,
            );
            $action = $this->menu_model->edit($id, $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/menu');
        }
    }

    public function delete($id)
    {
        $action = $this->menu_model->delete($id);

        $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
        redirect('dashboard/menu');
    }

    public function get_parent($id_parent)
    {
        $parent = $this->menu_model->first(
            array('id' => $id_parent)
        )->title;
        return $parent;
    }

}
