<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{

    protected $path;
    private $width  = 200;
    private $height = 200;

    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('pns_model');
        $this->load->model('users_model', 'users_presensi_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('unit_profiles_model');
        $this->load->model('unor_model');
        $this->load->model('atasan_skpd_model');

        $this->page_title = 'Profile';
        $this->auth       = false;
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'required');

        $id          = get_session('id_users');
        $akses_login = get_session('akses_login');

        if ($akses_login == '1') {
            $data['user'] = $this->_user_login;
        } else {
            $data['user'] = $this->users_presensi_model->all([
                'fields'      => 'users.*, unor.KD_UNOR AS unor, unor.NM_UNOR AS sopd_name, atasan_skpd.title as title_atasan_skpd, pns.PNS_PNSNIP as atasan_sopd',
                'left_join'   => [
                    'unit_profiles' => 'unit_profiles.unit_id = users.unit',
                    'unor'          => 'unor.KD_UNOR = unit_profiles.unor',
                    'atasan_skpd'   => 'atasan_skpd.unor = unor.KD_UNOR',
                    'pns'           => 'pns.id_master_kelas_jabatan = atasan_skpd.id_master_kelas_jabatan',
                ],
                'where_false' => [
                    'users.id'               => $this->_user_login->id,
                    'pns.PNS_PNSNIP NOT IN ' => '(SELECT nip FROM pns_ex)',
                ],
            ], false);
            // echo $this->db->last_query();die;

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$this->_user_login->unor}&is_plt=true",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['pns'] = $this->makeRequest($requireOption)->data;
        }

        if (!$data['user']) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Edit ' . $this->page_title;
            $this->render('profile/edit', $data);
        } else {
            $name         = 'photo';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(path_image('user_path'));
                $type  = 'image';
                $photo = $this->upload_file->upload($name, path_image('user_path'), $type, $this->width, $this->height, false, false, current_url());
                unlink_file(path_image('user_path') . $data['user']->photo);
            } else {
                $photo = $data['user']->photo;
            }
            if ($this->input->post('password', true) == $data['user']->password) {
                $password = $this->input->post('password', true);
            } else {
                $salt_length = 10;
                $salt        = substr(md5(uniqid(rand(), true)), 0, $salt_length);
                $password    = $salt . substr(sha1($salt . $this->input->post('password', true)), 0, -$salt_length);
            }

            if ($akses_login == 1) {
                $update_users = array(
                    'password' => $password,
                    'email'    => $this->input->post('email', true),
                );
                $this->users_ekin_model->edit($id, $update_users);

                if (!is_null($this->_user_login->id_pns)) {
                    $update_pns = array(
                        'PNS_PHOTO' => $photo,
                    );
                    $this->pns_model->edit($this->_user_login->id_pns, $update_pns);
                }
            } else {
                $update_users = [
                    'password' => $password,
                    'email'    => $this->input->post('email', true),
                    'photo'    => $photo,
                ];
                $this->users_presensi_model->edit($id, $update_users);

                $update_unit_profiles = [
                    'nama_unit' => $this->input->post('sopd_name', true),
                ];
                $this->unit_profiles_model->edit($data['user']->unit, $update_unit_profiles);

                $update_unor = [
                    'NM_UNOR' => $this->input->post('sopd_name', true),
                ];
                $this->unor_model->edit_where(['KD_UNOR' => $data['user']->unor], $update_unor);

                $update_atasan_skpd = [
                    'title'                   => $this->input->post('title_atasan_skpd', true),
                ];
                $this->atasan_skpd_model->edit_where(['unor' => $data['user']->unor], $update_atasan_skpd);

                if (!empty($this->input->post('atasan_sopd', true))) {
                    $get_detail_pns = $this->pns_model->first([
                        'PNS_PNSNIP' => $this->input->post('atasan_sopd', true),
                    ]);

                    if ($get_detail_pns) {
                        $update_atasan_skpd = [
                            'id_master_kelas_jabatan' => $get_detail_pns->id_master_kelas_jabatan,
                        ];
                        $this->atasan_skpd_model->edit_where(['unor' => $data['user']->unor], $update_atasan_skpd);
                    } else {
                        $this->session->set_flashdata('message', array('message' => 'Data PNS sebagai atasan SKPD tidak ditemukan..', 'class' => 'alert-danger'));
                        redirect('dashboard/profile');
                    }
                }
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/profile');
        }
    }

}
