<?php
# @Author: Awan Tengah
# @Date:   2019-08-22T22:49:12+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-22T23:00:30+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Bantuan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kinerja/pengaduan_model', 'pengaduan_model');
    }

    public function get_data() {
        $data = $this->pengaduan_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index() {
        $data['page_title'] = 'Bantuan';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'pengaturan', 'icon' => '', 'active' => '1']
        ];
        $data['active_menu'] = 'bantuan';
        $this->render('bantuan/index', $data);
    }

}
