<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_bulanan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('absen_enroll_model');
        $this->load->model('absen_libur_model');
        $this->load->model('atasan_skpd_model');
        $this->load->model('tipe_pegawai_model');
        $this->load->model('unor_model');
        $this->load->model('users_model');
        $this->load->model('pns_model');
        $this->load->model('pns_plt_model');
        $this->load->model('master_device_model');
    }

    public function get_data()
    {
        $id_groups      = get_session('id_groups');
        $allowed_groups = [1, 4, 5];

        if (in_array($id_groups, $allowed_groups)) {
            $unor = decode_crypt($this->input->get('unor', true));
        } else {
            $unor = get_session('unor');
        }

        if (get_session('akses_login') == '1') {
            $nip = encode_crypt($this->_user_login->PNS_PNSNIP);
        } else {
            $nip = '';
        }

        $month = decode_crypt($this->input->get('month', true));
        $year  = $this->input->get('year', true);
        $type  = $this->input->get('type', true);

        $unor_encrypt  = encode_crypt($unor);
        $link_get_data = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}&nip={$nip}" : base_url("api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}&nip={$nip}");
        $get_all_data  = file_get_contents($link_get_data);

        return $this->output
            ->set_content_type('application/json')
            ->set_output($get_all_data);
    }

    public function index()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5 || $id_groups == 4) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } elseif ($id_groups == 99) { //awalnya id groups 4 buat reviewer masing2 skpd, tapi dinonaktifkan agar id groups 4 bisa akses semua skpd
            $id_users          = get_session('id_users');
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd_filter_for_reviewer?id_users=" . $id_users : base_url('api/get_all_sopd_filter_for_reviewer?id_users=' . $id_users);
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }

        $data['all_sopd'] = json_decode($get_all_sopd);

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $requireOption = [
            'method'      => 'GET',
            'url'         => ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_tipe_pegawai" : base_url("api/get_tipe_pegawai"),
            'headers'     => [],
            'body'        => [],
            'returnArray' => true,
        ];
        $data['all_tipe_pegawai'] = $this->makeRequest($requireOption)->data;

        $data['page_title'] = 'Rekap Bulanan';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'rekap bulanan', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'rekapitulasi';

        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');

        $this->render('rekap_bulanan/index', $data);
    }

    public function report($unor_encrypt = null, $month = null, $year = null, $type = null, $selected_ttd = null)
    {
        if (is_null($unor_encrypt) && is_null($month) && is_null($year) && is_null($type)) {
            show_404();
        }
        $id_groups      = get_session('id_groups');
        $allowed_groups = [1, 4, 5, 6];
        if (!in_array($id_groups, $allowed_groups)) {
            if (decode_crypt($unor_encrypt) != get_session('unor')) {
                show_404();
            }
        }
        $get_unor           = $this->unor_model->first(decode_crypt($unor_encrypt));
        $month              = decode_crypt($month);
        $data['unor_text']  = $get_unor ? strtoupper($get_unor->NM_UNOR) : '';
        $data['month_text'] = strtoupper(get_indo_month_name($month));
        $data['month']      = $month;
        $data['year']       = $year;
        
        //Dibawah ini pembaruan model penanda tangan
        $unor               = decode_crypt($unor_encrypt);
        $data['nip_ttd']    = $selected_ttd;
        $data['selected_penanda_tangan_plt'] = $this->pns_plt_model->get_detail_pns_plt($selected_ttd,$unor);
        $data['selected_penanda_tangan'] = $this->pns_model->get_detail_pns($selected_ttd);
        //sampai sini

        $get_type           = $this->tipe_pegawai_model->first(
            array(
                'id' => $type,
            )
        );
        $data['type_text']     = $get_type ? ($get_type->id == 0 ? 'Pegawai Negeri Sipil' : $get_type->type) : '';
        $data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $link_get_data         = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}" : base_url("api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}");
        $get_all_data          = file_get_contents($link_get_data);
        $data['rekap_bulanan'] = json_decode($get_all_data);

        $decode_unor = decode_crypt($unor_encrypt);

        $data['atasan_skpd'] = $this->atasan_skpd_model->all(
            array(
                'fields'      => "atasan_skpd.title, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA",
                'left_join'   => array(
                    'pns' => "pns.id_master_kelas_jabatan = atasan_skpd.id_master_kelas_jabatan",
                ),
                'where_false' => array(
                    'atasan_skpd.unor'                       => "'{$decode_unor}'",
                    'pns.PNS_PNSNIP NOT IN '                 => "(SELECT nip FROM pns_ex)",
                    'atasan_skpd.id_master_kelas_jabatan !=' => '0',
                ),
            ),
            false
        );

        // $print_page = $this->load->view('rekap_bulanan/report', $data);
        $print_page = $this->load->view('rekap_bulanan/report', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode'        => 'utf-8',
            'orientation' => 'L',
        ]);
        $mpdf->WriteHTML($print_page);
        $mpdf->Output();
    }

    //MODUL TARIK ABSEN 01
    public function uploadAbsen()
    {
        $id_users = get_session('id_users');

        $nip = $this->users_model->first(
            array(
                'id' => $id_users,
            )
        );

        $pns = $this->pns_model->first(
            array(
                'pns.PNS_PNSNIP' => $nip->nip,
            )
        );

        $data_absen = $this->absen_enroll_model->all(
            array(
                'where'    => array(
                    'absen_enroll.PNS_PNSNIP' => $pns->PNS_PNSNIP,
                    'uraian'                  => 'up_face',
                ),
                'order_by' => 'TIME DESC',
                'limit'    => '1',
            ),
            false
        );

        $link_get_data_absen = "http://199.0.0.107/fingerspot/api/absen/pnsscan?pin=" . $pns->id . "&tanggal=" . $data_absen->tanggal . "&waktu=" . $data_absen->waktu;
        $get_all_data_absen  = file_get_contents($link_get_data_absen);
        $data                = json_decode($get_all_data_absen);
        $data1               = $data->data;

        if ($data1) {
            $jml_data    = count($data1);
            $last_update = $this->now;
            for ($i = 0; $i < $jml_data; $i++) {
                $data_PNS = $this->pns_model->first(
                    array(
                        "pns.id" => $data1[$i]->pin,
                    )
                );

                if ($data_PNS) {
                    $data_save = array(
                        'code'         => $data1[$i]->pin,
                        'PNS_PNSNIP'   => $data_PNS->PNS_PNSNIP,
                        'PNS_PNSNAM'   => $data_PNS->PNS_PNSNAM,
                        'PNS_GLRDPN'   => $data_PNS->PNS_GLRDPN,
                        'PNS_GLRBLK'   => $data_PNS->PNS_GLRBLK,
                        'PNS_UNOR'     => $data_PNS->PNS_UNOR,
                        'ip'           => $data1[$i]->sn,
                        'time'         => $data1[$i]->scan_date,
                        'tanggal'      => date('Y-m-d', strtotime($data1[$i]->scan_date)),
                        'waktu'        => date('H:i:s', strtotime($data1[$i]->scan_date)),
                        'jenis'        => ((date('H:i:s', strtotime($data1[$i]->scan_date)) >= date('H:i:s', strtotime('12:00:00'))) ? 'out' : 'in'),
                        'keterangan'   => '0',
                        'uraian'       => 'up_face',
                        'user_created' => null,
                    );

                    $last_update = date('Y-m-d', strtotime($data1[$i]->scan_date)) . " " . date('H:i:s', strtotime($data1[$i]->scan_date));
                    $action      = $this->absen_enroll_model->save($data_save);
                }
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
        } else {
            $this->session->set_flashdata('message', array('message' => 'No Data..', 'class' => 'alert-success'));
        }
        redirect('dashboard/rekap_bulanan');
    }
}
