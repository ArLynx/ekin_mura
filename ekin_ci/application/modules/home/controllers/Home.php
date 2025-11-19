<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('unor_model');
        $this->auth = false;
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');

        $requireOptionGenerateToken = [
            'method'      => 'POST',
            'url'         => $this->svc . "api/generate_token",
            'headers'     => [],
            'body'        => [
                'username' => get_config_item('USER_CREDENTIAL'),
                'password' => get_config_item('PASS_CREDENTIAL'),
            ],
            'returnArray' => true,
        ];
        $generate_token = $this->makeRequest($requireOptionGenerateToken);

        if ($this->form_validation->run() == false) {
            $data['skpd_verifikasi_tpp'] = $this->unor_model->all(
                array(
                    'fields'      => "unor.KD_UNOR, unor.NM_UNOR, IF(sudah_verifikasi_skpd.unor IS NULL, 'belum', 'sudah') AS status, sudah_verifikasi_skpd.tanggal",
                    'left_join'   => array(
                        'sudah_verifikasi_skpd' => 'sudah_verifikasi_skpd.unor = unor.KD_UNOR AND sudah_verifikasi_skpd.bulan = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND sudah_verifikasi_skpd.tahun = YEAR(CURDATE())',
                    ),
                    'where_false' => array(
                        'unor.NM_UNOR NOT LIKE ' => "'%PUSKESMAS%' AND unor.NM_UNOR NOT LIKE '%UKK%' AND unor.NM_UNOR NOT LIKE '%RSUD%' ESCAPE '!'",
                    ),
                    'order_by'    => 'unor.NM_UNOR ASC',
                )
            );

            if ($generate_token && !empty($generate_token->data)) {
                $requireOption = [
                    'method'      => 'GET',
                    'url'         => $this->svc . "api/information",
                    'headers'     => [
                        'Authorization' => $generate_token->data->token,
                    ],
                    'body'        => [],
                    'returnArray' => true,
                ];
                $data['information'] = $this->makeRequest($requireOption)->data;
            }

            $this->render('index', $data);
        } else {
            if ($generate_token && !empty($generate_token->data)) {
                $auth_token  = $generate_token->data->token;
                $akses_login = $this->input->post('akses_login', true);
                $username    = $this->input->post('username', true);
                $password    = $this->input->post('password', true);

                $requireOptionLogin = [
                    'method'      => 'POST',
                    'url'         => $this->svc . "api/login",
                    'headers'     => [
                        'Authorization' => $auth_token,
                    ],
                    'body'        => [
                        'akses_login' => $akses_login,
                        'username'    => $username,
                        'password'    => $password,
                    ],
                    'returnArray' => true,
                ];
                $login = $this->makeRequest($requireOptionLogin);

                if ($login && !empty($login->data)) {
                    $sess = array(
                        'auth_token'  => $generate_token->data->token,
                        'is_login'    => true,
                        'id_users'    => $login->data->id,
                        'id_groups'   => $login->data->group_id,
                        'akses_login' => $akses_login,
                        'unor'        => $login->data->unor,
                    );
                    $this->session->set_userdata($sess);
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('message', array('message' => $login->message, 'class' => 'alert-danger'));
                    redirect(site_url());
                }
            } else {
                $this->session->set_flashdata('message', array('message' => 'Something went wrong, please contact Administrator.', 'class' => 'alert-danger'));
                redirect(site_url());
            }
        }
    }

}
