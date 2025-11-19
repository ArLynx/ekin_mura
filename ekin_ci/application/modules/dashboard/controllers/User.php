<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('groups_model');
        $this->load->model('pns_model');
        $this->load->model('unit_profiles_model');
        $this->load->model('unor_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('users_model', 'users_presensi_model');
        $this->load->model('users_groups_model');
    }

    public function get_data()
    {
        $selected_sopd   = $this->input->get('selected_sopd', true);
        $selected_groups = $this->input->get('selected_groups', true);

        if ($selected_groups) {

            $selected_sopd   = decode_crypt($selected_sopd) != false ? decode_crypt($selected_sopd) : null;
            $selected_groups = decode_crypt($selected_groups);

            // 2 = Kepegawaian => users_presensi_model
            // 3 = PNS => users_ekin_model
            // 4 = Verifikator 2 => users_ekin_model

            if ($selected_groups == '2' || $selected_groups == '13') {
                $data = $this->users_presensi_model->get_users(null, $selected_sopd, $selected_groups);
            } else if ($selected_groups == '3') {
                $data = $this->users_ekin_model->get_users(null, $selected_sopd);
            } else {
                $data = [];
            }

            if (!empty($data)) {
                $tmp = [];
                foreach ($data as $key => $row) {
                    foreach ($row as $childkey => $childrow) {
                        $tmp[$key][$childkey] = $childrow;
                    }
                    $tmp[$key]['id_encrypt']   = encode_crypt($row->id);
                    $tmp[$key]['unor_encrypt'] = encode_crypt($row->PNS_UNOR);
                }
                $data = $tmp;
            }

        } else {
            $data = [];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'User';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'User', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'setup';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $data['groups'] = $this->groups_model->all([
            'where_in' => [
                'id' => [
                    2, 3, 13,
                ],
            ],
        ]);

        $this->render('user/list', $data);
    }

    public function add($selected_sopd = null, $selected_groups = null)
    {
        if (is_null($selected_sopd) || is_null($selected_groups)) {
            show_404();
        }

        $selected_sopd   = decode_crypt($selected_sopd);
        $selected_groups = decode_crypt($selected_groups);

        $this->load->library('form_validation');
        if ($selected_groups == '3') { // If PNS
            $this->form_validation->set_rules('nip', 'nip', 'required');
        }
        $this->form_validation->set_rules('username', 'username', "trim|required|callback_is_unique_username[{$selected_groups}]");
        $this->form_validation->set_rules('password', 'password', 'trim|required');

        $check_unor_exists = $this->unor_model->first([
            'KD_UNOR' => $selected_sopd,
        ]);
        if (!$check_unor_exists) {
            show_404();
        }

        $check_groups_exists = $this->groups_model->first([
            'id' => $selected_groups,
        ]);
        if (!$check_groups_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah User';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/user'), 'title' => 'User', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah User', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'setup';

            $data['selected_unor']      = $check_unor_exists->NM_UNOR;
            $data['id_selected_groups'] = $selected_groups;
            $data['selected_groups']    = $check_groups_exists->description;

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$selected_sopd}",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['pns'] = $this->makeRequest($requireOption)->data;

            $this->render('user/edit', $data);
        } else {
            $salt          = $this->getSalt();
            $password_hash = $salt . substr(sha1($salt . $this->input->post('password', true)), 0, -10);

            $get_unit_profiles = $this->unit_profiles_model->first([
                'unor' => $selected_sopd,
            ]);

            $data = [
                'ip_address' => '',
                'username'   => $this->input->post('username', true),
                'password'   => $password_hash,
                'email'      => '',
                'created_on' => '',
                'active'     => '1',
                'nip'        => $selected_groups == '3' ? $this->input->post('nip', true) : '',
                'unit'       => ($get_unit_profiles ? $get_unit_profiles->unit_id : ''),
            ];
            if ($selected_groups == '2' || $selected_groups == '13') { //Kepegawaian OR Kepegawaian Puskesmas
                $id_users = $this->users_presensi_model->save($data);

                $data_users_groups = [
                    'user_id'  => $id_users,
                    'group_id' => $selected_groups,
                ];
                $this->users_groups_model->save($data_users_groups);

                $message = 'Action Successfully..';
                $class   = 'alert-success';
            } else if ($selected_groups == '3') { //PNS
                $id_users = $this->users_ekin_model->save($data);

                $message = 'Action Successfully..';
                $class   = 'alert-success';
            } else {
                $message = 'Groups not allowed..';
                $class   = 'alert-danger';
            }

            $this->session->set_flashdata('message', array('message' => $message, 'class' => $class));
            redirect('dashboard/user');
        }
    }

    public function edit($id_encrypt = null, $selected_sopd = null, $selected_groups = null)
    {
        if (is_null($id_encrypt) || is_null($selected_groups)) {
            show_404();
        }

        $selected_sopd   = decode_crypt($selected_sopd);
        $selected_groups = decode_crypt($selected_groups);

        $this->load->library('form_validation');
        if ($selected_groups == '3') {
            $this->form_validation->set_rules('nip', 'nip', 'required');
        }
        $this->form_validation->set_rules('username', 'username', "trim|required|callback_is_unique_username[{$selected_groups}, {$id_encrypt}]");

        if ($selected_groups == '2' || $selected_groups == '13') { //Kepegawaian OR Kepegawaian Puskesmas
            $get_user = $this->users_presensi_model->get_users(decode_crypt($id_encrypt), $selected_sopd, $selected_groups);
        } else if ($selected_groups == '3') {
            $get_user = $this->users_ekin_model->get_users(decode_crypt($id_encrypt), $selected_sopd);
        }

        if (!$get_user) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Ubah User';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/user'), 'title' => 'User', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah User', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'setup';

            $data['selected_unor']      = $get_user->sopd_name;
            $data['id_selected_groups'] = $selected_groups;
            $data['selected_groups']    = $get_user->groups_name;

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$selected_sopd}",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['pns'] = $this->makeRequest($requireOption)->data;

            $data['user'] = $get_user;

            $this->render('user/edit', $data);
        } else {
            if (!empty($this->input->post('password', true))) {
                $salt          = $this->getSalt();
                $password_hash = $salt . substr(sha1($salt . $this->input->post('password', true)), 0, -10);
            } else {
                $password_hash = $get_user->password;
            }

            $data = [
                'ip_address' => '',
                'username'   => $this->input->post('username', true),
                'password'   => $password_hash,
                'email'      => '',
                'created_on' => '',
                'active'     => '1',
                'nip'        => $selected_groups == '3' ? $this->input->post('nip', true) : '',
                'unit'       => $get_user->unit_id,
            ];
            if ($selected_groups == '2' || $selected_groups == '13') { //Kepegawaian OR Kepegawaian Puskesmas
                $this->users_presensi_model->edit(decode_crypt($id_encrypt), $data);
            } else if ($selected_groups == '3') {
                $this->users_ekin_model->edit(decode_crypt($id_encrypt), $data);
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/user');
        }
    }

    public function delete()
    {
        $id_encrypt      = $this->input->get('id_encrypt', true);
        $selected_groups = $this->input->get('selected_groups', true);

        if ($id_encrypt && $selected_groups) {
            $id              = decode_crypt($id_encrypt);
            $selected_groups = decode_crypt($selected_groups);

            if ($selected_groups == '2' || $selected_groups == '13') { //Kepegawaian OR Kepegawaian Puskesmas
                $this->users_groups_model->delete([
                    'user_id' => $id,
                ]);
                $this->users_presensi_model->delete($id);
            } else if ($selected_groups == '3') {
                $this->users_ekin_model->delete($id);
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/user');
        }
    }

    public function is_unique_username($username, $params)
    {
        $params          = explode(', ', $params);
        $selected_groups = $params[0];
        $id_encrypt      = isset($params[1]) ? $params[1] : null;

        $id = !is_null($id_encrypt) ? decode_crypt($id_encrypt) : null;

        $where = [
            'username' => $username,
        ];

        if (!is_null($id)) {
            $where = $where + ['id' => $id];
        }

        if ($selected_groups == '2' || $selected_groups == '13') { //Kepegawaian OR Kepegawaian Puskesmas
            $check = $this->users_presensi_model->first($where);
            if ($check && is_null($id)) {
                $this->form_validation->set_message('is_unique_username', 'Username sudah digunakan..');
                return false;
            }
            return true;
        } else if ($selected_groups == '3') {
            $check = $this->users_ekin_model->first($where);
            if ($check && is_null($id)) {
                $this->form_validation->set_message('is_unique_username', 'Username sudah digunakan..');
                return false;
            }
            return true;
        } else {
            $this->form_validation->set_message('is_unique_username', 'Group tidak diijinkan..');
            return false;
        }
    }

}
