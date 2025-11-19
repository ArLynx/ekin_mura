<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kinerja_bawahan_ditolak extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kegiatan_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('pekerjaan_maping_model');
        $this->load->model('pns_model');
        $this->load->model('pekerjaan_model');
        $this->load->model('rincian_pekerjaan_model');

        $this->now        = date('Y-m-d H:i:s');
        $this->page_title = 'Kinerja Bawahan Ditolak';
        $this->auth       = false;
    }

    public function get_data()
    {
        $id_temp        = decode_crypt($this->input->get('id_temp', true));
        $dbkinerja      = get_config_item('dbkinerja');
        $selected_year  = $this->input->get('selected_year', true);
        $selected_month = $this->input->get('selected_month', true);

        $data['pns'] = $this->pns_model->all(
            array(
                'where' => array(
                    'pns.id' => $id_temp,
                ),
            ), false
        );

        $year  = $selected_year;
        $month = ($selected_month == 0 ? date("m") : ($selected_month < 10 ? '0' . $selected_month : $selected_month));

        $data = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    "{$dbkinerja}.kegiatan.pns_pnsnip" => $data['pns']->PNS_PNSNIP,
                    'YEAR(waktu_mulai)'                => $year,
                    'MONTH(waktu_mulai)'               => $month,
                    'status'                           => 4,
                ),
                'order_by'  => 'waktu_mulai ASC',
            )
        );

        $tmp = array();
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                $tmp[$key][$childkey] = $childrow;
            }
            $tmp[$key]['id_encrypt'] = encode_crypt($row->id);
        }
        $data = $tmp;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);

        $data['pns'] = $this->pns_model->all(
            array(
                'fields'    => 'pns.*, gol.NM_PKT as nm_pangkat, gol.NM_GOL as nm_golongan, master_kelas_jabatan.nama_jabatan as nm_jab',
                'left_join' => array(
                    'gol'                  => 'gol.KD_GOL = pns.PNS_GOLRU',
                    'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                ),
                'where'     => array(
                    'pns.id' => decode_crypt($id_encrypt),
                ),
            ), false
        );

        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Kegiatan', 'icon' => '', 'active' => '1'],
        ];

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);
        $data['month']      = date("m");

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $this->render('kinerja_bawahan_ditolak/list', $data);
    }

}
