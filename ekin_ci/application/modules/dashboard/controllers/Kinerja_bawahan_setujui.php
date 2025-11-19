<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kinerja_bawahan_setujui extends MY_Controller
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
        $this->page_title = 'Kinerja Bawahan Setujui';
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
                    'status'                           => 1,
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

        $this->render('kinerja_bawahan_setujui/list', $data);
    }

    // public function tanggapan_all_kegiatan(){
    //     $id_users = get_session('id_users');
    //     $dbkinerja = get_config_item('dbkinerja');
    //     $idkegiatancektop = $this->input->post('idkegiatancektop', true);
    //     $id_temp = decode_crypt($this->input->post('id_temps', true));
    //     $stt = $this->input->post('stt', true);

    //     $data['users_ekin'] = $this->users_ekin_model->all(
    //         array(
    //             'where'     => array(
    //                 "{$dbkinerja}.users.id" => $id_users,
    //             ),
    //         ), false
    //     );

    //     for($i=0;$i<count($idkegiatancektop);$i++){
    //         $data_tanggapan_keg = $this->kegiatan_model->first(
    //             array(
    //                 "kegiatan.id"=>$idkegiatancektop[$i]
    //             )
    //         );

    //         $data['kegiatan'] = $this->kegiatan_model->all(
    //             array(
    //                 'fields' => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
    //                 'left_join' => array(
    //                     "{$dbkinerja}.pekerjaan" => 'pekerjaan.id = kegiatan.pekerjaan_id',
    //                     "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
    //                 ),
    //                 'where' => array(
    //                     'kegiatan.id' => $idkegiatancektop[$i],
    //                 ),
    //             ), false
    //         );
    //         $status = $data['kegiatan']->status;

    //         if($data_tanggapan_keg){
    //             if($stt == '1'){
    //                 if($status == 3){
    //                     $data_updated = array(
    //                         'status' => 1,
    //                         'jam_kerja' => 1,
    //                         'tanggal_periksa' => $this->now,
    //                         'nip_pemeriksa' => $data['users_ekin']->nip,
    //                     );
    //                     $this->kegiatan_model->edit($idkegiatancektop[$i],$data_updated);

    //                     $this->session->set_flashdata('message', array('message' => 'Pekerjaan berhasil disetujui', 'class' => 'alert-success'));
    //                 }
    //             } else {
    //                 //tolak
    //                 if($status == 3){
    //                     $data_updated = array(
    //                         'status' => 4,
    //                         'norma_waktu' => 0,
    //                         'waktu_akhir' => $data['kegiatan']->waktu_mulai,
    //                         'jam_kerja' => 1,
    //                         'tanggal_periksa' => $this->now,
    //                         'nip_pemeriksa' => $data['users_ekin']->nip,
    //                     );
    //                     $this->kegiatan_model->edit($idkegiatancektop[$i],$data_updated);

    //                     $this->session->set_flashdata('message', array('message' => 'Pekerjaan berhasil ditolak', 'class' => 'alert-success'));
    //                 }
    //             }

    //         } else {
    //             show_404();
    //         }
    //     }

    //     $id_cry = encode_crypt($id_temp);
    //     redirect("/dashboard/kinerja_bawahan_koreksi/index?id_encrypt=$id_cry");
    // }

}
