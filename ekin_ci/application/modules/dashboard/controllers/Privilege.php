<?php
# @Author: Awan Tengah
# @Date:   2017-05-04T21:17:33+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-01T22:41:19+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Privilege extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('groups_model');
        $this->load->model('menu_model');
        $this->load->model('privilege_model');

        $this->page_title = 'Privilege';
    }

    public function index()
    {
        $data['active_menu'] = 'akses';
        if (!$_POST) {
            $data['page_title'] = $this->page_title;
            $data['groups']     = $this->groups_model->all();
            $this->render('privilege/index', $data);
        } else {
            $id_groups = $this->input->post('id_groups', true);
            $this->privilege_model->delete(
                array('id_groups' => $id_groups)
            );
            $menu = $this->input->post('menu', true);
            foreach ($menu as $key => $value) {
                $view   = isset($value['view']) ? 1 : 0;
                $create = isset($value['create']) ? 1 : 0;
                $update = isset($value['update']) ? 1 : 0;
                $delete = isset($value['delete']) ? 1 : 0;

                $data = array(
                    'id_groups'  => $id_groups,
                    'id_menu'    => $key,
                    'view'       => $view,
                    'create'     => $create,
                    'update'     => $update,
                    'delete'     => $delete,
                    'created_at' => $this->now,
                );
                $this->privilege_model->save($data);
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/privilege');
        }
    }

    public function get_privilege($id_groups, $id_menu)
    {
        $privilege = $this->privilege_model->first(
            array(
                'id_groups' => $id_groups,
                'id_menu'   => $id_menu,
            )
        );
        return $privilege;
    }

    public function lists($id_groups = null)
    {
        if ($id_groups != null) {
            $data['id_groups'] = $id_groups;
            $data['menu']      = $this->menu_model->all(
                array(
                    'where' => array(
                        'controller !=' => '',
                    ),
                )
            );
            echo $this->load->view('privilege/list', $data, true);
        } else {
            show_404();
        }
    }

}
