<?php

defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

class Api extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('absen_enroll_model');
        $this->load->model('absen_libur_model');
        $this->load->model('genpos_model');
        $this->load->model('gol_model');
        $this->load->model('kinerja/gaji_pegawai_model', 'gaji_pegawai_model');
        $this->load->model('kinerja/kegiatan_total_waktu_model', 'kegiatan_total_waktu_model');
        $this->load->model('kinerja/rekap_absen_bulanan_model', 'rekap_absen_bulanan_model');
        $this->load->model('kinerja/rekap_indikator_kehadiran_model', 'rekap_indikator_kehadiran_model');
        $this->load->model('kinerja/rekap_tpp_gabungan_model', 'rekap_tpp_gabungan_model');
        $this->load->model('kinerja/tpp_gabungan_doc_model', 'tpp_gabungan_doc_model');
        $this->load->model('kinerja/tpp_max_before_2020_model', 'tpp_max_before_2020_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('kehadiran_model');
        $this->load->model('master_agama_model');
        $this->load->model('master_index_tpp_model');
        $this->load->model('master_jabatan_pns_model');
        $this->load->model('master_jabfus_model');
        $this->load->model('master_jenis_cuti_model');
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('master_koef_kelas_jabatan_model');
        $this->load->model('master_tukin_bpk_model');
        $this->load->model('master_pekerjaan_model');
        $this->load->model('nominal_sanksi_model');
        $this->load->model('nominal_rapel_model');
        $this->load->model('master_rincian_pekerjaan_model');
        $this->load->model('kinerja/satuan_model');
        $this->load->model('pengajuan_cuti_model');
        $this->load->model('perintah_tugas_model');
        $this->load->model('perjalanan_dinas_model');
        $this->load->model('pns_model');
        $this->load->model('pns_hukuman_model');
        $this->load->model('pns_jam_extra_model');
        $this->load->model('sudah_verifikasi_skpd_model');
        $this->load->model('tipe_pegawai_model');
        $this->load->model('unor_model');
        $this->load->model('stafpos_model');
        $this->load->model('fpos_model');
        $this->load->model('rincian_pekerjaan_model');
        $this->load->model('pekerjaan_maping_model');
        $this->load->model('pekerjaan_model');
        $this->load->model('mutasi_model');
        $this->load->model('mutasi_detail_model');
        $this->load->model('kegiatan_model');
        $this->load->model('bank_model');
        $this->load->model('master_device_model');
        $this->load->model('master_pengurangan_tpp_model');
        $this->load->model('cpns_model');
        $this->load->model('pns_plt_model');
        $this->now  = date('Y-m-d H:i:s');
        $this->auth = false;
    }

    public function generate_token()
    {
        $response = [];
        if (!is_null(raw_input())) {
            $requireOptionToken = [
                'method'      => 'POST',
                'url'         => $this->svc . "api/generate_token",
                'headers'     => [],
                'body'        => [
                    'username' => raw_input()->username,
                    'password' => raw_input()->password,
                ],
                'returnArray' => true,
            ];
            $response = $this->makeRequest($requireOptionToken);
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_sopd()
    {
        $unor     = $this->input->get('unor', true);
        $is_dinas = $this->input->get('is_dinas', true);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_sopd?unor={$unor}&is_dinas={$is_dinas}",
            'headers'     => [],
            'body'        => [],
            'returnArray' => true,
        ];
        $response = $this->makeRequest($requireOption);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_pegawai_tpp()
    {
        $unor         = $this->input->get('unor', true);
        $is_plt       = $this->input->get('is_plt', true);
        $tipe_pegawai = $this->input->get('tipe_pegawai', true);

        $header = apache_request_headers();

        if (isset($header['Authorization'])) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$unor}&is_plt={$is_plt}&tipe_pegawai={$tipe_pegawai}",
                'headers'     => [
                    'Authorization' => $header['Authorization'],
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $response = $this->makeRequest($requireOption);
        } else {
            $response = [
                'data'    => [],
                'message' => "Please include your API key as an Authorization header",
                'status'  => "failed",
            ];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_sopd_detail()
    {
        $unor = $this->input->get('unor', true);

        $header = apache_request_headers();

        if (isset($header['Authorization'])) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_sopd_detail?unor={$unor}",
                'headers'     => [
                    'Authorization' => $header['Authorization'],
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $response = $this->makeRequest($requireOption);
        } else {
            $response = [
                'data'    => [],
                'message' => "Please include your API key as an Authorization header",
                'status'  => "failed",
            ];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function update_sopd_detail()
    {
        $header = apache_request_headers();

        if (isset($header['Authorization'])) {
            $requireOption = [
                'method'      => 'PUT',
                'url'         => $this->svc . "api/update_sopd_detail",
                'headers'     => [
                    'Authorization' => $header['Authorization'],
                ],
                'body'        => [
                    'telp'       => raw_input()->telp,
                    'alamat'     => raw_input()->alamat,
                    'kd_wilayah' => raw_input()->kd_wilayah,
                    'unor'       => raw_input()->unor,
                ],
                'returnArray' => true,
            ];
            $response = $this->makeRequest($requireOption);
        } else {
            $response = [
                'data'    => [],
                'message' => "Please include your API key as an Authorization header",
                'status'  => "failed",
            ];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // public function login()
    // {
    //     $header = apache_request_headers();
    //     if (isset($header['Authorization'])) {
    //         $requireOptionLogin = [
    //             'method'      => 'POST',
    //             'url'         => $this->svc . "api/login",
    //             'headers'     => [
    //                 'Authorization' => $header['Authorization'],
    //             ],
    //             'body'        => [
    //                 'akses_login' => $this->input->post('akses_login', true),
    //                 'username'    => $this->input->post('username', true),
    //                 'password'    => $this->input->post('password', true),
    //             ],
    //             'returnArray' => false,
    //         ];
    //         $data = $this->makeRequest($requireOptionLogin);
    //         echo $data;
    //     } else {
    //         $data = [
    //             'data'    => [],
    //             'message' => "Please include your API key as an Authorization header",
    //             'status'  => "failed",
    //         ];
    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode($data));
    //     }
    // }

    public function detail_pns($nip = '')
    {
        $header = apache_request_headers();
        if (isset($header['Authorization'])) {
            if (!empty($nip)) {
                $dbkinerja  = get_config_item('dbkinerja');
                $dbpresensi = get_config_item('dbpresensi');

                $data = $this->users_ekin_model->all([
                    'fields'      => "users.*, pns.id as id_pns, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, ' . '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, ' . '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                    pns.PNS_UNOR AS unor, pns.PNS_PHOTO AS photo, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi, gol.NM_GOL, gol.NM_PKT,
                    IF(pnsa.PNS_GLRDPN IS NOT NULL AND pnsa.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pnsa.PNS_GLRDPN, ' . '), CONCAT(pnsa.PNS_PNSNAM, CONCAT(', ', pnsa.PNS_GLRBLK))), IF(pnsa.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pnsa.PNS_GLRDPN, ' . '), pnsa.PNS_PNSNAM), IF(pnsa.PNS_GLRBLK IS NOT NULL, CONCAT(pnsa.PNS_PNSNAM, CONCAT(', ', pnsa.PNS_GLRBLK)), pnsa.PNS_PNSNAM))) as PNS_NAMA_ATASAN",
                    'left_join'   => [
                        "{$dbpresensi}.pns"                    => 'pns.PNS_PNSNIP = users.nip',
                        "{$dbpresensi}.master_kelas_jabatan"   => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                        "{$dbpresensi}.master_unit_organisasi" => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        "{$dbpresensi}.gol"                    => 'gol.KD_GOL = pns.PNS_GOLRU',
                        "{$dbkinerja}.pns_atasan"              => 'pns_atasan.PNS_PNSNIP = pns.PNS_PNSNIP',
                        "{$dbpresensi}.pns pnsa"               => 'pnsa.PNS_PNSNIP = pns_atasan.pns_atasan',
                    ],
                    'where_false' => [
                        'users.nip' => $nip,
                    ],
                ]);

                $check_mutasi_pending = $this->mutasi_detail_model->all(
                    array(
                        'fields'    => 'mutasi_detail.*, mutasi.tanggal, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi',
                        'left_join' => array(
                            'mutasi'                 => 'mutasi.id = mutasi_detail.mutasi_id',
                            'master_kelas_jabatan'   => 'master_kelas_jabatan.id = mutasi_detail.id_master_kelas_jabatan_baru',
                            'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        ),
                        'where'     => array(
                            'mutasi_detail.pns_pnsnip' => $nip,
                            'mutasi_detail.status'     => '0', //pending
                        ),
                        'order_by'  => 'mutasi_detail.id DESC',
                        'limit'     => '1',
                    ), false
                );

                $tmp = array();
                foreach ($data as $key => $row) {
                    foreach ($row as $childkey => $childrow) {
                        if ($childkey != 'nama_jabatan' && $childkey != 'unit_organisasi') {
                            $tmp[$key][$childkey] = $childrow;
                        }
                    }
                    if (date('Y-m-d') >= $check_mutasi_pending->tanggal) {
                        $tmp[$key]['nama_jabatan']    = $check_mutasi_pending->nama_jabatan;
                        $tmp[$key]['unit_organisasi'] = $check_mutasi_pending->unit_organisasi;
                    } else {
                        $tmp[$key]['nama_jabatan']    = $row->nama_jabatan;
                        $tmp[$key]['unit_organisasi'] = $row->unit_organisasi;
                    }
                }
                $result = $tmp;

                $message = 'Detail PNS';
                $status  = 'success';
            } else {
                $result  = [];
                $message = 'Please input nip';
                $status  = 'failed';
            }
        } else {
            $result  = [];
            $message = 'Please include your API key as an Authorization header';
            $status  = 'failed';
        }
        $data = [
            'data'    => $result,
            'message' => $message,
            'status'  => $status,
        ];
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_bank()
    {
        $data = $this->bank_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_keterangan_absen()
    { //from table presensi.kehadiran
        $data = $this->kehadiran_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_sopd()
    {
        $unor     = $this->input->get('unor', true);
        $is_dinas = $this->input->get('is_dinas', true);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_sopd?unor={$unor}&is_dinas={$is_dinas}",
            'headers'     => [],
            'body'        => [],
            'returnArray' => true,
        ];
        $response = $this->makeRequest($requireOption)->data;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_all_absen_libur()
    {
        $fields = $this->input->get('fields', true);
        $month  = $this->input->get('month', true);
        $year   = $this->input->get('year', true);
        if ($fields && $month && $year) {
            $data = $this->absen_libur_model->all(
                array(
                    'fields' => $fields,
                    'where'  => array(
                        'MONTH(tanggal)' => $month,
                        'YEAR(tanggal)'  => $year,
                    ),
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_year()
    {
        $data = array();
        for ($year = 2020; $year <= date('Y'); $year++) {
            array_push($data, array('year' => $year));
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_month()
    {
        $data = array(
            array('month' => '1', 'month_text' => 'Januari'),
            array('month' => '2', 'month_text' => 'Februari'),
            array('month' => '3', 'month_text' => 'Maret'),
            array('month' => '4', 'month_text' => 'April'),
            array('month' => '5', 'month_text' => 'Mei'),
            array('month' => '6', 'month_text' => 'Juni'),
            array('month' => '7', 'month_text' => 'Juli'),
            array('month' => '8', 'month_text' => 'Agustus'),
            array('month' => '9', 'month_text' => 'September'),
            array('month' => '10', 'month_text' => 'Oktober'),
            array('month' => '11', 'month_text' => 'November'),
            array('month' => '12', 'month_text' => 'Desember'),
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_day()
    {
        $data = array(
            array('day' => '1', 'day_text' => '1'),
            array('day' => '2', 'day_text' => '2'),
            array('day' => '3', 'day_text' => '3'),
            array('day' => '4', 'day_text' => '4'),
            array('day' => '5', 'day_text' => '5'),
            array('day' => '6', 'day_text' => '6'),
            array('day' => '7', 'day_text' => '7'),
            array('day' => '8', 'day_text' => '8'),
            array('day' => '9', 'day_text' => '9'),
            array('day' => '10', 'day_text' => '10'),
            array('day' => '11', 'day_text' => '11'),
            array('day' => '12', 'day_text' => '12'),
            array('day' => '13', 'day_text' => '13'),
            array('day' => '14', 'day_text' => '14'),
            array('day' => '15', 'day_text' => '15'),
            array('day' => '16', 'day_text' => '16'),
            array('day' => '17', 'day_text' => '17'),
            array('day' => '18', 'day_text' => '18'),
            array('day' => '19', 'day_text' => '19'),
            array('day' => '20', 'day_text' => '20'),
            array('day' => '21', 'day_text' => '21'),
            array('day' => '22', 'day_text' => '22'),
            array('day' => '23', 'day_text' => '23'),
            array('day' => '24', 'day_text' => '24'),
            array('day' => '25', 'day_text' => '25'),
            array('day' => '26', 'day_text' => '26'),
            array('day' => '27', 'day_text' => '27'),
            array('day' => '28', 'day_text' => '28'),
            array('day' => '29', 'day_text' => '29'),
            array('day' => '30', 'day_text' => '30'),
            array('day' => '31', 'day_text' => '31'),
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_sopd_filter_for_reviewer()
    {
        $dbkinerja  = get_config_item('dbkinerja');
        $dbpresensi = get_config_item('dbpresensi');

        $id_users = $this->input->get('id_users', true);

        if ($id_users) {
            $data = $this->unor_model->all(
                array(
                    'fields'      => "unor.KD_UNOR, unor.NM_UNOR",
                    'left_join'   => [
                        "{$dbkinerja}.pembagian_skpd_penilai" => "pembagian_skpd_penilai.unor = unor.KD_UNOR",
                    ],
                    'where'       => [
                        'pembagian_skpd_penilai.id_users' => $id_users,
                    ],
                    'where_false' => [
                        'unor.NM_UNOR NOT LIKE ' => "'%PUSKESMAS%' AND unor.NM_UNOR NOT LIKE '%UKK%' AND unor.NM_UNOR NOT LIKE '%RSUD%' ESCAPE '!'",
                    ],
                    'group_by'    => 'KD_UNOR',
                    'order_by'    => "{$dbpresensi}.unor.NM_UNOR ASC",
                )
            );
        } else {
            $data = [];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_sopd_tarik()
    {
        $dbpresensi = get_config_item('dbpresensi');
        $unor       = $this->input->get('unor', true);

        if (!empty($unor)) {
            $data = $this->master_device_model->all(
                array(
                    'fields'    => 'master_device.*, unor.NM_UNOR as nama_unor',
                    'left_join' => array(
                        "{$dbpresensi}.unor" => "{$dbpresensi}.unor.KD_UNOR = master_device.kd_unor",
                    ),
                    'where'     => array(
                        'master_device.kd_unor' => $unor,
                    ),
                    'order_by'  => 'master_device.kd_unor ASC',
                )
            );
        } else {
            $data = $this->master_device_model->all(
                array(
                    'fields'    => 'master_device.*, unor.NM_UNOR as nama_unor',
                    'left_join' => array(
                        "{$dbpresensi}.unor" => "{$dbpresensi}.unor.KD_UNOR = master_device.kd_unor",
                    ),
                    'order_by'  => 'master_device.kd_unor ASC',
                )
            );
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_pembagian_sopd()
    {
        $id_users  = $this->input->get('id_users', true);
        $dbkinerja = get_config_item('dbkinerja');

        $data = $this->unor_model->all(
            array(
                'fields'    => 'KD_UNOR, NM_UNOR',
                'left_join' => array(
                    "{$dbkinerja}.pembagian_skpd_penilai" => "{$dbkinerja}.pembagian_skpd_penilai.unor = unor.KD_UNOR",
                ),
                'where'     => array(
                    "{$dbkinerja}.pembagian_skpd_penilai.id_users" => $id_users,
                ),
                'order_by'  => 'NM_UNOR ASC',
            )
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_tipe_pegawai()
    {
        $notpns = $this->input->get('notpns', true);
        if (isset($notpns) && $notpns == true) {
            $data = $this->tipe_pegawai_model->all(
                array(
                    'where' => array(
                        'id != ' => 0,
                    ),
                )
            );
        } else {
            $data = $this->tipe_pegawai_model->all();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_agama()
    {
        $data = $this->master_agama_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_pegawai_sopd()
    {
        $unor   = $this->input->get('unor', true);
        $is_tkd = $this->input->get('is_tkd', true);
        if (!empty($unor)) {

            if (!empty($is_tkd) && $is_tkd == 'yes') {
                $not_like = array(
                    'pns.PNS_PNSNIP' => 'TKD',
                );
            } else {
                $not_like = array();
            }

            $month = date('m');
            $year  = date('Y');
            $data  = $this->pns_model->all(
                array(
                    'fields'      => "pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                                      pns.PNS_JABSTR, pns.STAF_JABFUS, gol.NM_PKT, gol.NM_GOL, master_kelas_jabatan.nama_jabatan as NM_GENPOS, master_kelas_jabatan.nama_jabatan",
                    'left_join'   => array(
                        'gol'                  => 'gol.KD_GOL = pns.PNS_GOLRU',
                        'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                    ),
                    'where_false' => array(
                        'pns.PNS_UNOR'          => $unor,
                        'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                    ),
                    'not_like'    => $not_like,
                    'order_by'    => 'pns.PNS_GOLRU DESC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_pegawai_sopd_2()
    {
        $unor   = decode_crypt($this->input->get('unor', true));
        $is_tkd = $this->input->get('is_tkd', true);
        if (!empty($unor)) {

            if (!empty($is_tkd) && $is_tkd == 'yes') {
                $not_like = array(
                    'pns.PNS_PNSNIP' => 'TKD',
                );
            } else {
                $not_like = array();
            }

            $month = date('m');
            $year  = date('Y');
            $data  = $this->pns_model->all(
                array(
                    'fields'      => "pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                                      pns.PNS_JABSTR, pns.STAF_JABFUS, gol.NM_PKT, gol.NM_GOL, master_kelas_jabatan.nama_jabatan as NM_GENPOS, master_kelas_jabatan.nama_jabatan",
                    'left_join'   => array(
                        'gol'                  => 'gol.KD_GOL = pns.PNS_GOLRU',
                        'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                    ),
                    'where_false' => array(
                        'pns.PNS_UNOR'          => $unor,
                        'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                    ),
                    'not_like'    => $not_like,
                    'order_by'    => 'pns.PNS_GOLRU DESC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_perintah_tugas()
    {
        $id = $this->input->get('id', true);
        if (!empty($id)) {
            $data = $this->perintah_tugas_model->first($id);
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_perjalanan_dinas()
    {
        $id = $this->input->get('id', true);
        if (!empty($id)) {
            $data = $this->perjalanan_dinas_model->first($id);
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_pengajuan_cuti()
    {
        $id = $this->input->get('id', true);
        if (!empty($id)) {
            $data = $this->pengajuan_cuti_model->all(
                array(
                    'fields'      => 'pengajuan_cuti.*, unor.NM_UNOR, master_jenis_cuti.jenis_cuti',
                    'left_join'   => array(
                        'unor'              => 'unor.KD_UNOR = pengajuan_cuti.unor',
                        'master_jenis_cuti' => 'master_jenis_cuti.id = pengajuan_cuti.id_master_jenis_cuti',
                    ),
                    'where_false' => array(
                        'pengajuan_cuti.id'             => $id,
                        'pengajuan_cuti.deleted_at IS ' => 'NULL',
                    ),
                ),
                false
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_master_jenis_cuti()
    {
        $data = $this->master_jenis_cuti_model->query(
            "SELECT * FROM master_jenis_cuti where id = 1 OR id = 3 OR id = 5"
        )->result();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_master_index_tpp()
    {
        $get_year = $this->input->get('year', true);
        if ($get_year) {
            $data = $this->master_index_tpp_model->first(
                array(
                    'tahun' => $get_year,
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_pangkat_golru()
    {
        $data = $this->gol_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_master_jabatan_pns()
    {
        $data = $this->master_jabatan_pns_model->all();
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_master_kelas_jabatan()
    {
        $unor = $this->input->get('unor', true);
        if ($unor) {
            $data = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => $unor,
                    ),
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_master_kelas_jabatan_2()
    {
        $unor = decode_crypt($this->input->get('unor', true));
        if ($unor) {
            $data = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => $unor,
                    ),
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

     public function get_penanda_tangan()
    {
        $unor   = decode_crypt($this->input->get('unor', true));
        $is_tkd = $this->input->get('is_tkd', true);
       
        
          $arr_kel = [
           ' 30201753',
            '30201754',
            '30201755',
            '30201750',
            '30201751',
            '30201752',
            '30201756',
            '30201757',
            '30201758',
        ];

         $check = in_array($unor, $arr_kel);
        empty($check) ?  $kelas_jabatan = 11 :  $kelas_jabatan = 8;

        if (!empty($unor)) {

            if (!empty($is_tkd) && $is_tkd == 'yes') {
                $not_like = array(
                    'pns.PNS_PNSNIP' => 'TKD',
                );
            } else {
                $not_like = array();
            }

            $month = date('m');
            $year  = date('Y');
            $data  = $this->pns_model->all(
                array(
                    'fields'      => "pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                                      pns.PNS_JABSTR, pns.STAF_JABFUS, gol.NM_PKT, gol.NM_GOL, master_kelas_jabatan.nama_jabatan as NM_GENPOS, master_kelas_jabatan.nama_jabatan",
                    'left_join'   => array(
                        'gol'                  => 'gol.KD_GOL = pns.PNS_GOLRU',
                        'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                        // 'unor'                 => 'unor.KD_UNOR = pns.PNS_UNOR',
                        // 'pns_plt'              => 'unor.KD_UNOR = pns_plt.pns_unor_plt', 
                    ),
              
                    'where_false' => array(
                        'pns.PNS_UNOR'          => $unor,
                        'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                        'pns.IS_TKD' => 0,
                        'master_kelas_jabatan.kelas_jabatan >' => $kelas_jabatan,
                     
                    ),
                    'not_like'    => $not_like,
                    'order_by'    => 'pns.PNS_GOLRU DESC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

      public function get_penanda_tangan_plt()
    {
        $unor   = decode_crypt($this->input->get('unor', true));
 
            $where = array(
                'pns_plt.pns_unor_plt'           => $unor,
                'pns.PNS_PNSNIP NOT IN ' => "(SELECT nip FROM pns_ex)",
                'pns_plt.akhir_plt '           => NULL,
            );
    

        $data = $this->pns_plt_model->all(
            array(
                'fields'      => "pns_plt.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                master_kelas_jabatan.nama_jabatan, (SELECT nama_jabatan FROM master_kelas_jabatan WHERE id = pns_plt.id_master_kelas_jabatan_plt) as nama_jabatan_plt, pns_plt.pns_unor_plt,
                unor.NM_UNOR, (SELECT NM_UNOR FROM unor WHERE KD_UNOR = pns_plt.pns_unor_plt) AS NM_UNOR_PLT, pns_plt.awal_plt, pns_plt.akhir_plt, pns_plt.sk_plt",
                'left_join'   => array(
                    'pns'                  => 'pns.PNS_PNSNIP = pns_plt.pns_pnsnip',
                    'master_kelas_jabatan' => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                    'unor'                 => 'unor.KD_UNOR = pns.PNS_UNOR',
                ),
                'where_false' => $where,
            )
        );
      
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_master_tukin_bpk()
    {
        $kelas_jabatan = $this->input->get('kelas_jabatan', true);
        if ($kelas_jabatan) {
            $data = $this->master_tukin_bpk_model->first(
                array(
                    'kelas_jabatan' => $kelas_jabatan,
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // public function encrypt_unor()
    // {
    //     $unor = $this->input->get('unor', true);
    //     if ($unor) {
    //         echo encode_crypt($unor);
    //     }
    // }

    // public function call_collect_absen_enroll_total_waktu()
    // {
    //     $nip   = $this->input->get('nip', true);
    //     $month = $this->input->get('month', true);
    //     $year  = $this->input->get('year', true);
    //     $a     = $this->collect_absen_enroll_total_waktu($nip, $month, $year, true);
    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($a));
    // }

    private function collect_absen_enroll_total_waktu($nip, $month, $year, $debug = false)
    {
        $limit_aktivitas_kerja = in_array($month, array('1', '2', '3')) && $year == '2020' ? 6600 : get_config_item('limit_aktivitas_kerja');

        if ($nip && $month && $year) {
            $getdata = $this->absen_enroll_model->all(
                array(
                    'fields'      => 'PNS_PNSNIP, MAX(time) AS time, MAX(tanggal) AS tanggal, MAX(waktu) AS waktu, jenis, keterangan, uraian',
                    'where_false' => array(
                        'PNS_PNSNIP'     => "'{$nip}'",
                        'keterangan IN ' => "(0, 1, 2, 12, 14)", //normal, DD, DL, MD, CT
                        'MONTH(tanggal)' => "{$month}",
                        'YEAR(tanggal)'  => "{$year}",
                    ),
                    'group_by'    => 'tanggal, jenis',
                    'order_by'    => 'tanggal, jenis',
                )
            );
            if ($getdata) {
                $tmp = array();
                foreach ($getdata as $key => $row) {
                    $tmp[$row->tanggal]['date'] = $row->tanggal;
                    if ($row->jenis == 'in') {
                        $tmp[$row->tanggal]['jenis']   = 'inout';
                        $tmp[$row->tanggal]['jenisin'] = $row->waktu;
                    } else if ($row->jenis == 'out') {
                        $tmp[$row->tanggal]['jenis']    = 'inout';
                        $tmp[$row->tanggal]['jenisout'] = $row->waktu;
                    } else {
                        $tmp[$row->tanggal]['jenis']    = $row->jenis;
                        $tmp[$row->tanggal]['jenisin']  = '07:00:00';
                        $tmp[$row->tanggal]['jenisout'] = '15:30:00';
                    }
                }
                $tmp2             = array();
                $key2i            = 0;
                $minutes_on_month = 0;
                foreach ($tmp as $key => $row) {
                    if ($debug == true) {
                        $tmp2[$key2i]['date']     = $row['date'];
                        $tmp2[$key2i]['jenis']    = $row['jenis'];
                        $tmp2[$key2i]['jenisin']  = $row['jenisin'];
                        $tmp2[$key2i]['jenisout'] = $row['jenisout'];
                    }

                    if (isset($row['jenisin']) && isset($row['jenisout'])) {
                        if ((strtotime($row['jenisin']) >= strtotime('06:30:00') && strtotime($row['jenisin']) <= strtotime('07:00:00')) && (strtotime($row['jenisout']) >= strtotime('15:30:00') && strtotime($row['jenisout']) <= strtotime('16:30:00'))) {
                            $tmp2[$key2i]['minutes'] = 7.5 * 60;
                        } else {
                            if (strtotime($row['jenisin']) > strtotime('07:00:00')) {
                                $timediff = (strtotime($row['jenisin']) - strtotime('07:00:00')) / 60;
                            } else if (strtotime($row['jenisout']) < strtotime('15:30:00')) {
                                $timediff = (strtotime('15:30:00') - strtotime($row['jenisout'])) / 60;
                            } else {
                                $timediff = 0;
                            }
                            // $tmp2[$key2i]['minutes']  = number_format((7.5 * 60) - $timediff, 3, '.', '');
                            $tmp2[$key2i]['minutes']  = (7.5 * 60) - $timediff;
                            $tmp2[$key2i]['minutess'] = $timediff;
                        }
                    } else {
                        $tmp2[$key2i]['minutes'] = 0;
                    }
                    $minutes_on_month += $tmp2[$key2i]['minutes'];

                    $key2i++;
                }
                $data = ($debug == false) ? $minutes_on_month : $tmp2;
                return $data;

            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function collect_kegiatan_total_waktu()
    {
        $encrpt_unor = $this->input->get('encrpt_unor', true);
        $month       = $this->input->get('month', true);
        $year        = $this->input->get('year', true);
        if ($encrpt_unor && $month && $year) {
            $decode_crypt_unor = decode_crypt($encrpt_unor);
            $check_unor_exists = $this->unor_model->first($decode_crypt_unor);
            if ($check_unor_exists) {
                $get_config_dbkinerja  = get_config_item('dbkinerja');
                $limit_aktivitas_kerja = in_array($month, array('1', '2', '3')) && $year == '2020' ? 6600 : get_config_item('limit_aktivitas_kerja');

                $getdata = $this->pns_model->all(
                    array(
                        'fields'      => "pns.PNS_PNSNIP, pns.PNS_PNSNAM, IF(SUM(kegiatan.norma_waktu) >= {$limit_aktivitas_kerja}, {$limit_aktivitas_kerja}, SUM(kegiatan.norma_waktu)) AS total_norma_waktu",
                        'left_join'   => array(
                            "{$get_config_dbkinerja}.kegiatan kegiatan" => "kegiatan.pns_pnsnip = pns.PNS_PNSNIP AND MONTH(kegiatan.waktu_mulai) = '{$month}' AND YEAR(kegiatan.waktu_mulai) = '{$year}' AND kegiatan.status = 6 AND kegiatan.jam_kerja = 1",
                        ),
                        'where_false' => array(
                            'pns.PNS_UNOR'          => "'{$decode_crypt_unor}'",
                            'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex WHERE unor = '{$decode_crypt_unor}' AND date <= STR_TO_DATE('1,{$month},{$year}','%d,%m,%Y'))",
                        ),
                        'not_like'    => array(
                            'pns.PNS_PNSNIP' => 'TKD',
                        ),
                        'group_by'    => 'pns.PNS_PNSNIP',
                        'order_by'    => 'pns.PNS_GOLRU DESC',
                    )
                );

                if ($getdata) {
                    $data_kegiatan_total_waktu = [];
                    foreach ($getdata as $key => $row) {
                        $this->kegiatan_total_waktu_model->delete([
                            'pns_pnsnip' => $row->PNS_PNSNIP,
                            'month'      => $month,
                            'year'       => $year,
                        ]);
                        // $total_capaian_waktu_kerja = $this->collect_absen_enroll_total_waktu($row->PNS_PNSNIP, $month, $year);
                        $data_kegiatan_total_waktu[] = array(
                            'pns_pnsnip'                => $row->PNS_PNSNIP,
                            'month'                     => $month,
                            'year'                      => $year,
                            'status'                    => '6', //disetujui penilai
                            'total_norma_waktu'         => $row->total_norma_waktu ?? 0, //waktu berdasar menit pekerjaan
                            'total_capaian_waktu_kerja' => 0, //waktu berdasar absen_enroll
                            'jam_kerja'                 => '1', //jam kerja
                            'created_at'                => $this->now,
                        );
                        // $check_exists_on_kegiatan_total_waktu = $this->kegiatan_total_waktu_model->first(
                        //     array(
                        //         'pns_pnsnip' => $row->PNS_PNSNIP,
                        //         'month'      => $month,
                        //         'year'       => $year,
                        //     )
                        // );
                        // if ($check_exists_on_kegiatan_total_waktu) {
                        //     $this->kegiatan_total_waktu_model->edit($check_exists_on_kegiatan_total_waktu->id, $data_save_update);
                        // } else {
                        //     $this->kegiatan_total_waktu_model->save($data_save_update);
                        // }
                    }
                    if (!empty($data_kegiatan_total_waktu)) {
                        $this->kegiatan_total_waktu_model->save_batch($data_kegiatan_total_waktu);
                    }
                    echo 'DONE :)';
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get_absen_bulanan()
    {
        $unor  = decode_crypt($this->input->get('unor', true));
        $month = $this->input->get('month', true);
        $year  = $this->input->get('year', true);
        $type  = $this->input->get('type', true);
        $nip   = $this->input->get('nip', true);

        if (!empty($unor) && !empty($month) && !empty($year) && isset($type)) {
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $nip = decode_crypt($nip);

            if (!empty($nip)) {
                $where_check = array(
                    'unor'            => $unor,
                    'month'           => $month,
                    'year'            => $year,
                    'id_tipe_pegawai' => $type,
                    'PNS_PNSNIP'      => $nip,
                );
            } else {
                $where_check = array(
                    'unor'            => $unor,
                    'month'           => $month,
                    'year'            => $year,
                    'id_tipe_pegawai' => $type,
                );
            }

            $check_rekap_absen_bulanan = $this->rekap_absen_bulanan_model->all(
                array(
                    'where' => $where_check,
                )
            );

            if (!$check_rekap_absen_bulanan) {
                $data = $this->absen_enroll_model->get_all_absen_enroll($unor, $month, $year, $type, $nip);
                // echo $this->db->last_query();die;

                $arr_absen_libur_5 = [];
                $arr_absen_libur_6 = [];
                $absen_libur_model = $this->absen_libur_model->get_absen_libur($month, $year);
                foreach ($absen_libur_model as $row) {
                    array_push($arr_absen_libur_5, date('d', strtotime($row->tanggal)));
                }

                $begin = new DateTime(date('Y-m-d', strtotime("{$year}-{$month}-01")));
                $end   = new DateTime(date('Y-m-t', strtotime("{$year}-{$month}")));
                while ($begin <= $end) {
                    if ($begin->format("D") == "Sat" || $begin->format("D") == "Sun") {
                        array_push($arr_absen_libur_5, $begin->format("d"));
                    }
                    if ($begin->format("D") == "Sun") {
                        array_push($arr_absen_libur_6, $begin->format("d"));
                    }
                    $begin->modify('+1 day');
                }
                asort($arr_absen_libur_5);
                asort($arr_absen_libur_6);
                $arr_absen_libur_5 = array_unique($arr_absen_libur_5);
                $arr_absen_libur_6 = array_unique($arr_absen_libur_6);

                $tmp = array();
                $no  = 1;
                foreach ($data as $key => $row) {
                    if ($row->hari_kerja == '7') {
                        $arr_absen_libur = [];
                    } else if ($row->hari_kerja == '6') {
                        $arr_absen_libur = $arr_absen_libur_6;
                    } else {
                        $arr_absen_libur = $arr_absen_libur_5;
                    }
                    $tmp['data'][$key]['no']         = $no;
                    $tmp['data'][$key]['PNS_PNSNIP'] = $row->PNS_PNSNIP;
                    $tmp['data'][$key]['PNS_NAMA']   = $type == 0 ? "<strong>{$row->PNS_NAMA}</strong><br>{$row->PNS_PNSNIP}" : "<strong>{$row->PNS_NAMA}</strong>";
                    for ($i = 1; $i <= $days_in_month; $i++) {
                        $i          = date('d', strtotime("{$year}-{$month}-{$i}"));
                        $in         = "in{$i}";
                        $out        = "out{$i}";
                        $ket        = "ket{$i}";
                        $uraian     = "uraian{$i}";
                        $uraian_in  = "uraian_in_{$i}";
                        $uraian_out = "uraian_out_{$i}";

                        $style_text_in  = ($row->$uraian_in == '[MANUAL]') ? ($row->$in != '' ? 'color:#FF9900;font-style:italic;' : '') : '';
                        $style_text_out = ($row->$uraian_out == '[MANUAL]') ? ($row->$out != '' ? 'color:#FF9900;font-style:italic;' : '') : '';
                        $class_holiday  = array_search($i, $arr_absen_libur) !== false ? 'brown' : '';

                        $row_in  = ($row->$in != '' ? $row->$in : ($class_holiday != '' ? '' : '-'));
                        $row_out = ($row->$out != '' ? $row->$out : ($class_holiday != '' ? '' : '-'));
                        $row_ket = ($row->$ket != '') ? $row->$ket : ($class_holiday != '' ? '' : ($row_in == '-' && $row_out == '-' ? 'TKS' : '-'));

                        $absen = "<div class='{$class_holiday}'><span style='{$style_text_in}'>{$row_in}</span><br><span style='{$style_text_out}'>{$row_out}</span><br><span>{$row_ket}</span></div>";

                        $tmp['data'][$key]['absen' . $i] = $absen;
                    }
                    $no++;
                }
                $data = !empty($tmp) ? $tmp : array("data" => array());
            } else {
                $data['message'] = 'Arsip Absen Bulanan';
                $data['data']    = $check_rekap_absen_bulanan;
            }
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_rekap_indikator_kehadiran()
    {
        $unor_encrypt = $this->input->get('unor_encrypt', true);
        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);

        if (!empty($unor_encrypt) && !empty($month) && !empty($year)) {
            $unor              = decode_crypt($unor_encrypt);
            $check_unor_exists = $this->unor_model->first($unor);
            if ($check_unor_exists) {
                $check_rekap = $this->rekap_indikator_kehadiran_model->first(
                    array(
                        'unor'  => $unor,
                        'month' => $month,
                        'year'  => $year,
                    )
                );
                if (!$check_rekap) {
                    $requireOptionToken = [
                        'method'      => 'POST',
                        'url'         => $this->svc . "api/generate_token",
                        'headers'     => [],
                        'body'        => [
                            'username' => get_config_item('USER_CREDENTIAL'),
                            'password' => get_config_item('PASS_CREDENTIAL'),
                        ],
                        'returnArray' => true,
                    ];
                    $token = $this->makeRequest($requireOptionToken)->data->token;

                    $requireOption = [
                        'method'      => 'GET',
                        'url'         => $this->svc . "api/get_indikator_kehadiran?unor={$unor}&month={$month}&year={$year}",
                        'headers'     => [
                            'Authorization' => $token,
                        ],
                        'body'        => [],
                        'returnArray' => true,
                    ];
                    $data_indikator_kehadiran = $this->makeRequest($requireOption);

                    if ($data_indikator_kehadiran) {
                        $data_rekap = array();
                        foreach ($data_indikator_kehadiran->data as $row) {
                            $data_rekap[] = array(
                                'unor'       => decode_crypt($unor_encrypt),
                                'month'      => $month,
                                'year'       => $year,
                                'PNS_PNSNIP' => $row->PNS_PNSNIP,
                                'PNS_NAMA'   => $row->PNS_NAMA,
                                'skor1'      => $row->skor1,
                                'skor2'      => $row->skor2,
                                'skor3'      => $row->skor3,
                                'skor4'      => $row->skor4,
                                'skor5'      => $row->skor5,
                                'skor6'      => $row->skor6,
                                'skor7'      => $row->skor7,
                                'skor8'      => $row->skor8,
                                'skor9'      => $row->skor9,
                                'skor10'     => $row->skor10,
                                'skor11'     => $row->skor11,
                                'skor1skor'  => $row->skor1skor,
                                'skor2skor'  => $row->skor2skor,
                                'skor3skor'  => $row->skor3skor,
                                'skor4skor'  => $row->skor4skor,
                                'skor5skor'  => $row->skor5skor,
                                'skor6skor'  => $row->skor6skor,
                                'skor7skor'  => $row->skor7skor,
                                'skor8skor'  => $row->skor8skor,
                                'skor9skor'  => $row->skor9skor,
                                'skor10skor' => $row->skor10skor,
                                'skor11skor' => $row->skor11skor,
                                'totalskor'  => $row->totalskor,
                                'persentase' => $row->persentase,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                        }
                        if (!empty($data_rekap)) {
                            $this->rekap_indikator_kehadiran_model->save_batch($data_rekap);
                        }
                    }
                    echo 'OK, rekap tersimpan';
                } else {
                    echo 'Data rekap sudah ada';
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function save_rekap_absen_bulanan()
    {
        $unor_encrypt = $this->input->get('unor_encrypt', true);
        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);
        $type         = $this->input->get('type', true) ? $this->input->get('type', true) : 0;

        if (!empty($unor_encrypt) && !empty($month) && !empty($year)) {
            $check_unor_exists = $this->unor_model->first(decode_crypt($unor_encrypt));
            if ($check_unor_exists) {
                $check_rekap = $this->rekap_absen_bulanan_model->first(
                    array(
                        'unor'  => decode_crypt($unor_encrypt),
                        'month' => $month,
                        'year'  => $year,
                    )
                );
                if (!$check_rekap) {
                    $link_get_data      = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}" : base_url("api/get_absen_bulanan?unor={$unor_encrypt}&month={$month}&year={$year}&type={$type}");
                    $get_all_data       = file_get_contents($link_get_data);
                    $data_absen_bulanan = json_decode($get_all_data);
                    if ($data_absen_bulanan) {
                        $data_rekap = array();
                        foreach ($data_absen_bulanan->data as $row) {
                            $data_rekap[] = array(
                                'no'         => $row->no,
                                'unor'       => decode_crypt($unor_encrypt),
                                'month'      => $month,
                                'year'       => $year,
                                'PNS_PNSNIP' => $row->PNS_PNSNIP,
                                'PNS_NAMA'   => $row->PNS_NAMA,
                                'absen01'    => $row->absen01,
                                'absen02'    => $row->absen02,
                                'absen03'    => $row->absen03,
                                'absen04'    => $row->absen04,
                                'absen05'    => $row->absen05,
                                'absen06'    => $row->absen06,
                                'absen07'    => $row->absen07,
                                'absen08'    => $row->absen08,
                                'absen09'    => $row->absen09,
                                'absen10'    => $row->absen10,
                                'absen11'    => $row->absen11,
                                'absen12'    => $row->absen12,
                                'absen13'    => $row->absen13,
                                'absen14'    => $row->absen14,
                                'absen15'    => $row->absen15,
                                'absen16'    => $row->absen16,
                                'absen17'    => $row->absen17,
                                'absen18'    => $row->absen18,
                                'absen19'    => $row->absen19,
                                'absen20'    => $row->absen20,
                                'absen21'    => $row->absen21,
                                'absen22'    => $row->absen22,
                                'absen23'    => $row->absen23,
                                'absen24'    => $row->absen24,
                                'absen25'    => $row->absen25,
                                'absen26'    => $row->absen26,
                                'absen27'    => $row->absen27,
                                'absen28'    => $row->absen28,
                                'absen29'    => isset($row->absen29) ? $row->absen29 : null,
                                'absen30'    => isset($row->absen30) ? $row->absen30 : null,
                                'absen31'    => isset($row->absen31) ? $row->absen31 : null,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                        }
                        if (!empty($data_rekap)) {
                            $this->rekap_absen_bulanan_model->save_batch($data_rekap);
                        }
                    }
                    echo 'OK, rekap tersimpan';
                } else {
                    echo 'Data rekap sudah ada';
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //NYIMPAN TPP GABUNGAN
    public function save_rekap_tpp_gabungan()
    {
        $unor_encrypt = $this->input->get('unor_encrypt', true);
        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);

        if (!empty($unor_encrypt) && !empty($month) && !empty($year)) {

            $unor = decode_crypt($unor_encrypt);

            $check_unor_exists = $this->unor_model->first($unor);

            if ($check_unor_exists) {
                $check_rekap = $this->rekap_tpp_gabungan_model->first(
                    array(
                        'unor'  => $unor,
                        'month' => $month,
                        'year'  => $year,
                    )
                );

                if (!$check_rekap) {
                    $requireOptionToken = [
                        'method'      => 'POST',
                        'url'         => $this->svc . "api/generate_token",
                        'headers'     => [],
                        'body'        => [
                            'username' => get_config_item('USER_CREDENTIAL'),
                            'password' => get_config_item('PASS_CREDENTIAL'),
                        ],
                        'returnArray' => true,
                    ];
                    $token = $this->makeRequest($requireOptionToken)->data->token;

                    $requireOption = [
                        'method'      => 'GET',
                        'url'         => $this->svc . "api/get_tpp_gabungan?unor={$unor}&month={$month}&year={$year}",
                        'headers'     => [
                            'Authorization' => $token,
                        ],
                        'body'        => [],
                        'returnArray' => true,
                    ];
                    $data_tpp_gabungan = $this->makeRequest($requireOption);

                    if ($data_tpp_gabungan) {
                        $data_rekap = array();
                        foreach ($data_tpp_gabungan->data as $row) {
                            $data_rekap[] = array(
                                'no'                       => $row->no,
                                'unor'                     => $unor,
                                'month'                    => $month,
                                'year'                     => $year,
                                'PNS_NAMA'                 => $row->PNS_NAMA,
                                'PNS_PNSNIP'               => $row->PNS_PNSNIP,
                                'pangkat'                  => $row->pangkat,
                                'kelas_jabatan'            => $row->kelas_jabatan,
                                'nama_jabatan'             => $row->nama_jabatan,
                                'unit_organisasi'          => $row->unit_organisasi,
                                'bank'                     => $row->bank,
                                'PNS_NO_REK'               => $row->PNS_NO_REK,
                                'NM_GOL'                   => $row->NM_GOL,
                                'tpp_basic'                => $row->tpp_basic,
                                'total_norma_waktu'        => $row->total_norma_waktu,
                                'CODE_GOL'                 => $row->CODE_GOL,
                                'ket_pns'                  => $row->ket_pns,
                                'eselon_jabatan_pns'       => $row->eselon_jabatan_pns,
                                'tpp_prestasi_kerja'       => $row->tpp_prestasi_kerja,
                                'tpp_beban_kerja'          => $row->tpp_beban_kerja,
                                'besaran_hukuman_tks'      => $row->besaran_hukuman_tks,
                                'tpp_gabungan'             => $row->tpp_gabungan,
                                'tunjangan_plt'            => $row->tunjangan_plt,
                                'cost_bpjs'                => $row->cost_bpjs,
                                'pph'                      => $row->pph,
                                'tpp_gabungan_setelah_pph' => $row->tpp_gabungan_setelah_pph,
                                'nominal_sanksi'           => $row->nominal_sanksi,
                                'nominal_rapel'            => $row->nominal_rapel,
                                'keterangan_rapel'         => $row->keterangan_rapel,
                                'faktor_penyeimbang'       => $row->faktor_penyeimbang,
                                'pengurangan'              => $row->pengurangan,
                                'pengurangan_cpns'         => $row->pengurangan_cpns,
                                'created_at'               => date('Y-m-d H:i:s'),
                            );
                        }
                        if (!empty($data_rekap)) {
                            $this->rekap_tpp_gabungan_model->save_batch($data_rekap);
                        }
                    }
                    echo 'OK, rekap tersimpan';
                } else {
                    echo 'Data rekap sudah ada';
                }

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function call_save_rekap_indikator_kehadiran()
    {
        // $data_all_skpd = $this->unor_model->all(
        //     array(
        //         'fields'       => 'KD_UNOR, NM_UNOR',
        //         'not_like'     => array(
        //             'NM_UNOR' => 'puskesmas',
        //         ),
        //         'and_not_like' => array(
        //             'NM_UNOR' => 'rsud',
        //         ),
        //         'order_by'     => 'NM_UNOR ASC',
        //     )
        // );

        $dbkinerja = get_config_item('dbkinerja');

        // DICOMMENT DLU
        $where = [
            // 'sudah_verifikasi_skpd.unor'         => "'{$row->KD_UNOR}'",
            'sudah_verifikasi_skpd.tahun >= '    => '2020',
            'rekap_indikator_kehadiran.unor IS ' => 'null',
        ];

        // $where = [
        //     'sudah_verifikasi_skpd.bulan'    => '2',
        //     'sudah_verifikasi_skpd.tahun'    => '2022',
        //     'rekap_indikator_kehadiran.unor IS ' => 'null',
        // ];

        // foreach ($data_all_skpd as $row) {
        $check_verifikasi_bkpp = $this->sudah_verifikasi_skpd_model->all(
            array(
                'fields'      => 'sudah_verifikasi_skpd.*',
                'left_join'   => array(
                    "{$dbkinerja}.rekap_indikator_kehadiran" => 'rekap_indikator_kehadiran.unor = sudah_verifikasi_skpd.unor AND rekap_indikator_kehadiran.month = sudah_verifikasi_skpd.bulan AND rekap_indikator_kehadiran.year = sudah_verifikasi_skpd.tahun',
                ),
                'where_false' => $where,
                // 'group_by'    => 'sudah_verifikasi_skpd.bulan, sudah_verifikasi_skpd.tahun',
            )
        );

        foreach ($check_verifikasi_bkpp as $row2) {
            $month         = $row2->bulan;
            $year          = $row2->tahun;
            $unor_encrypt  = encode_crypt($row2->unor);
            $link_get_data = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/save_rekap_indikator_kehadiran?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}" : base_url("api/save_rekap_indikator_kehadiran?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}");
            file_get_contents($link_get_data);
        }
        // }
        echo 'SELESAI';
        return true;
    }

    public function call_save_rekap_absen_bulanan()
    {
        // $data_all_skpd = $this->unor_model->all(
        //     array(
        //         'fields'       => 'KD_UNOR, NM_UNOR',
        //         'not_like'     => array(
        //             'NM_UNOR' => 'puskesmas',
        //         ),
        //         'and_not_like' => array(
        //             'NM_UNOR' => 'rsud',
        //         ),
        //         'order_by'     => 'NM_UNOR ASC',
        //     )
        // );

        $dbkinerja = get_config_item('dbkinerja');

        // DICOMMENT DLU
        $where = [
            // 'sudah_verifikasi_skpd.unor'      => "'{$row->KD_UNOR}'",
            'sudah_verifikasi_skpd.tahun >= ' => '2020',
            'rekap_absen_bulanan.unor IS '    => 'null',
        ];

        // $where = [
        //     'sudah_verifikasi_skpd.bulan'    => '2',
        //     'sudah_verifikasi_skpd.tahun'    => '2022',
        //     'rekap_absen_bulanan.unor IS '   => 'null',
        // ];

        // foreach ($data_all_skpd as $row) {
        $check_verifikasi_bkpp = $this->sudah_verifikasi_skpd_model->all(
            array(
                'fields'      => 'sudah_verifikasi_skpd.*',
                'left_join'   => array(
                    "{$dbkinerja}.rekap_absen_bulanan" => 'rekap_absen_bulanan.unor = sudah_verifikasi_skpd.unor AND rekap_absen_bulanan.month = sudah_verifikasi_skpd.bulan AND rekap_absen_bulanan.year = sudah_verifikasi_skpd.tahun',
                ),
                'where_false' => $where,
                // 'group_by'    => 'sudah_verifikasi_skpd.bulan, sudah_verifikasi_skpd.tahun',
            )
        );

        foreach ($check_verifikasi_bkpp as $row2) {
            $month         = $row2->bulan;
            $year          = $row2->tahun;
            $type          = 0; //PNS
            $unor_encrypt  = encode_crypt($row2->unor);
            $link_get_data = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/save_rekap_absen_bulanan?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}&type={$type}" : base_url("api/save_rekap_absen_bulanan?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}&type={$type}");
            file_get_contents($link_get_data);
        }
        // }
        echo 'SELESAI';
        return true;
    }

    public function call_save_rekap_tpp_gabungan()
    {
        // $data_all_skpd = $this->unor_model->all(
        //     array(
        //         'fields'       => 'KD_UNOR, NM_UNOR',
        //         'not_like'     => array(
        //             'NM_UNOR' => 'puskesmas',
        //         ),
        //         'and_not_like' => array(
        //             'NM_UNOR' => 'rsud',
        //         ),
        //         'order_by'     => 'NM_UNOR ASC',
        //     )
        // );

        $dbkinerja = get_config_item('dbkinerja');

        // DICOMMENT DLU
        $where = [
            // 'sudah_verifikasi_skpd.unor'      => "'{$row->KD_UNOR}'",
            'sudah_verifikasi_skpd.tahun >= ' => '2020',
            'rekap_tpp_gabungan.unor IS '     => 'null',
        ];

        // $where = [
        //     'sudah_verifikasi_skpd.bulan'    => '2',
        //     'sudah_verifikasi_skpd.tahun'    => '2022',
        //     'rekap_tpp_gabungan.unor IS '    => 'null',
        // ];

        // foreach ($data_all_skpd as $row) {
        $check_verifikasi_bkpp = $this->sudah_verifikasi_skpd_model->all(
            array(
                'fields'      => 'sudah_verifikasi_skpd.*',
                'left_join'   => array(
                    "{$dbkinerja}.rekap_tpp_gabungan" => 'rekap_tpp_gabungan.unor = sudah_verifikasi_skpd.unor AND rekap_tpp_gabungan.month = sudah_verifikasi_skpd.bulan AND rekap_tpp_gabungan.year = sudah_verifikasi_skpd.tahun',
                ),
                'where_false' => $where,
                // 'group_by'    => 'sudah_verifikasi_skpd.bulan, sudah_verifikasi_skpd.tahun',
            )
        );
        // echo $this->db->last_query();die;

        foreach ($check_verifikasi_bkpp as $row2) {
            $month         = $row2->bulan;
            $year          = $row2->tahun;
            $unor_encrypt  = encode_crypt($row2->unor);
            $link_get_data = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/save_rekap_tpp_gabungan?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}" : base_url("api/save_rekap_tpp_gabungan?unor_encrypt={$unor_encrypt}&month={$month}&year={$year}");
            file_get_contents($link_get_data);
        }
        // }
        echo 'SELESAI';
        return true;
    }

    public function check_rekap_tpp_gabungan_exists()
    {
        $unor_encrypt = $this->input->get('unor_encrypt', true);
        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);

        if ($unor_encrypt && $month && $year) {
            $check = $this->rekap_tpp_gabungan_model->first(
                array(
                    'unor'  => decode_crypt($unor_encrypt),
                    'month' => decode_crypt($month),
                    'year'  => $year,
                )
            );
            return $this->output->set_output($check ? true : false);
        } else {
            $message = false;
            return $this->output->set_output(false);
        }
    }

    public function check_rekap_absen_bulanan_exists()
    {
        $unor_encrypt = $this->input->get('unor_encrypt', true);
        $month        = $this->input->get('month', true);
        $year         = $this->input->get('year', true);

        if ($unor_encrypt && $month && $year) {
            $check = $this->rekap_absen_bulanan_model->first(
                array(
                    'unor'  => decode_crypt($unor_encrypt),
                    'month' => decode_crypt($month),
                    'year'  => $year,
                )
            );
            return $this->output->set_output($check ? true : false);
        } else {
            return $this->output->set_output(false);
        }
    }

    public function get_genpos_skpd()
    {
        $unor = $this->input->get('unor', true);
        if ($unor) {

            $arr_unor_kecamatan = array(
                '8836000000',
                '8837000000',
                '8838000000',
                '8839000000',
                '8840000000',
                '8849000000',
            );

            $arr_unor_kelurahan = array(
                '883600000', '883700000', '883800000', '883900000',
            );

            $unor_rujukan_camat = '0000000001';
            $unor_rujukan_lurah = '0000000002';

            if (array_search($unor, $arr_unor_kecamatan)) {
                $where = array(
                    'KD_UNOR' => $unor_rujukan_camat,
                );
            } else if (array_search(substr($unor, 0, 9), $arr_unor_kelurahan)) {
                $where = array(
                    'KD_UNOR' => $unor_rujukan_lurah,
                );
            } else {
                $where = array(
                    'KD_UNOR' => $unor,
                );
            }

            $data = $this->genpos_model->all(
                array(
                    'where'    => $where,
                    'or_where' => array(
                        'KD_UNOR' => '0000000000',
                    ),
                    'order_by' => 'KD_GENPOS ASC',
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_jabatan_bysopd()
    {
        $unor = decode_crypt($this->input->get('selected_sopd', true));
        if (!empty($unor)) {

            $data = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi',
                    'left_join' => [
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ],
                    'where'     => [
                        'master_kelas_jabatan.unor' => $unor,
                    ],
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_master_kelas_jabatan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_pekerjaan_byjabatan()
    {
        $unor       = decode_crypt($this->input->get('selected_sopd', true));
        $id_jabatan = decode_crypt($this->input->get('selected_jabatan', true));
        if (!empty($unor) && !empty($id_jabatan)) {

            $data['maping'] = $this->pekerjaan_maping_model->all(
                array(
                    'where' => array(
                        'pekerjaan_maping.id_master_kelas_jabatan' => $id_jabatan,
                    ),
                ), false
            );

            if ($data['maping'] != null) {
                $genpos = $data['maping']->KD_GENPOS;
            }

            if ($data['maping'] != null) {
                $data = $this->pekerjaan_model->all(
                    array(
                        'where'    => array(
                            'PNS_UNOR'   => $unor,
                            'id_jabatan' => $genpos,
                        ),
                        'order_by' => 'prioritas ASC',
                    )
                );
            } else {
                $data = $this->pekerjaan_model->all(
                    array(
                        'where'    => array(
                            'PNS_UNOR'                => $unor,
                            'id_master_kelas_jabatan' => $id_jabatan,
                        ),
                        'order_by' => 'prioritas ASC',
                    )
                );
            }

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_master_pekerjaan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_add_rincian()
    {
        $kelasjabatan      = decode_crypt($this->input->get('kelasjabatan', true));
        $klsjabatanasli    = decode_crypt($this->input->get('klsjabatanasli', true));
        $pekerjaan         = decode_crypt($this->input->get('pekerjaan', true));
        $rincian_pekerjaan = $this->input->get('rincian_pekerjaan', true);
        $norma_waktu       = $this->input->get('norma_waktu', true);
        $id_satuan         = $this->input->get('id_satuan', true);

        //genpos buat yg js
        $data['maping'] = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    'pekerjaan_maping.KD_GENPOS' => $klsjabatanasli,
                ),
            ), false
        );

        $data['master_kelas_jabatan_selected'] = $this->master_kelas_jabatan_model->first(
            array('id' => $klsjabatanasli)
        );

        if ($data['maping'] != null) {
            $id_master_kls_jabatan = $data['maping']->id_master_kelas_jabatan;
        } else {
            $id_master_kls_jabatan = $data['master_kelas_jabatan_selected']->id;
        }

        if ($id_master_kls_jabatan == $kelasjabatan) {
            if ($pekerjaan && $rincian_pekerjaan && $norma_waktu > 0 && $norma_waktu <= 60 && $id_satuan) {
                $data_save = array(
                    'id_pekerjaan' => $pekerjaan,
                    'nama_rincian' => $rincian_pekerjaan,
                    'norma_waktu'  => $norma_waktu,
                    'id_satuan'    => $id_satuan,
                );
                $this->rincian_pekerjaan_model->save($data_save);
                return true;
            }
            return false;
        } else if ($klsjabatanasli == $kelasjabatan) {
            if ($pekerjaan && $rincian_pekerjaan && $norma_waktu > 0 && $norma_waktu <= 60 && $id_satuan) {
                $data_save = array(
                    'id_pekerjaan' => $pekerjaan,
                    'nama_rincian' => $rincian_pekerjaan,
                    'norma_waktu'  => $norma_waktu,
                    'id_satuan'    => $id_satuan,
                );
                $this->rincian_pekerjaan_model->save($data_save);
                return true;
            }
            return false;
        }
        return false;
    }

    public function get_rincian_pekerjaan_byid()
    {
        $id_rincian_pekerjaan = decode_crypt($this->input->get('id_encrypt', true));
        if (!empty($id_rincian_pekerjaan)) {

            $data = $this->rincian_pekerjaan_model->all(
                array(
                    'where' => array(
                        'id' => $id_rincian_pekerjaan,
                    ),
                    // 'order_by' => 'id ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_master_rincian_pekerjaan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_edit_rincian()
    {
        $kelasjabatanedit     = decode_crypt($this->input->get('kelasjabatanedit', true));
        $klsjabatanasliedit   = decode_crypt($this->input->get('klsjabatanasliedit', true));
        $id_pekerjaan         = $this->input->get('id_pekerjaan', true);
        $id_rincian_pekerjaan = $this->input->get('id_rincian_pekerjaan', true);
        $rincian_pekerjaan    = $this->input->get('rincian_pekerjaan', true);
        $norma_waktu          = $this->input->get('norma_waktu', true);
        $id_satuan            = $this->input->get('id_satuan', true);

        $data['maping'] = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    'pekerjaan_maping.KD_GENPOS' => $klsjabatanasliedit,
                ),
            ), false
        );

        $data['master_kelas_jabatan_selected'] = $this->master_kelas_jabatan_model->first(
            array('id' => $klsjabatanasliedit)
        );

        if ($data['maping'] != null) {
            $id_master_kls_jabatan = $data['maping']->id_master_kelas_jabatan;
        } else {
            $id_master_kls_jabatan = $data['master_kelas_jabatan_selected']->id;
        }

        if ($id_master_kls_jabatan == $kelasjabatanedit) {
            if ($id_pekerjaan && $id_rincian_pekerjaan && $rincian_pekerjaan && $norma_waktu > 0 && $norma_waktu <= 60 && $id_satuan) {
                $data_edit = array(
                    'id_pekerjaan' => decode_crypt($id_pekerjaan),
                    'nama_rincian' => $rincian_pekerjaan,
                    'norma_waktu'  => $norma_waktu,
                    'id_satuan'    => $id_satuan,
                );
                $this->rincian_pekerjaan_model->edit($id_rincian_pekerjaan, $data_edit);
                return true;
            }
            return false;
        }
        return false;
    }

    public function get_all_jabatan_lama_sopd()
    {
        $unor = $this->input->get('unor', true);

        //untuk filter kecamatan dan kelurahan
        $arr_unor_kecamatan = array(
            '0',
            '8836000000',
            '8837000000',
            '8838000000',
            '8839000000',
            '8840000000',
            '8849000000',
        );

        $arr_unor_kelurahan = array(
            '0', '883600000', '883700000', '883800000', '883900000',
        );

        $unor_rujukan_camat = '0000000001';
        $unor_rujukan_lurah = '0000000002';

        if (!empty($unor)) {

            if (array_search($unor, $arr_unor_kecamatan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_camat,
                );
            } else if (array_search(substr($unor, 0, 9), $arr_unor_kelurahan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_lurah,
                );
            } else {
                $where = array(
                    'genpos.KD_UNOR' => $unor,
                );
            }

            $data = $this->genpos_model->all(
                array(
                    'where'    => $where,
                    'or_where' => array(
                        'genpos.KD_UNOR' => '0000000000',
                    ),
                )
            );

            // $data = $this->genpos_model->all(
            //     array(
            //         'where'    => array(
            //             'genpos.KD_UNOR' => $unor,
            //         ),
            //         'or_where' => array(
            //             'genpos.KD_UNOR' => '0000000000',
            //         ),
            //     )
            // );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_jp_by_genpos()
    {
        $selected_kd_genpos = $this->input->get('selected_kd_genpos', true);
        $selected_unor      = $this->input->get('selected_unor', true);
        if (!empty($selected_kd_genpos) && !empty($selected_unor)) {
            $data = $this->master_jabfus_model->all(
                array(
                    'where' => array(
                        'unor' => $selected_unor,
                    ),
                )
            );
            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['no_encrypt'] = encode_crypt($row->no);
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_all_jft_by_genpos()
    {
        $selected_kd_genpos = $this->input->get('selected_kd_genpos', true);
        $selected_unor      = $this->input->get('selected_unor', true);
        if (!empty($selected_kd_genpos) && !empty($selected_unor)) {
            $data = $this->fpos_model->all(
                array(
                    'where' => array(
                        'unor' => $selected_unor,
                    ),
                )
            );
            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['KD_FPOS_encrypt'] = encode_crypt($row->KD_FPOS);
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_cek_uraian_bytglmutasi()
    {
        $selected_tgl_input = $this->input->get('selected_tgl_input', true);

        if (!empty($selected_tgl_input)) {
            $id_users           = get_session('id_users');
            $dbkinerja          = get_config_item('dbkinerja');
            $dbpresensi         = get_config_item('dbpresensi');
            $data['users_ekin'] = $this->users_ekin_model->all(
                array(
                    'where' => array(
                        "{$dbkinerja}.users.id" => $id_users,
                    ),
                ), false
            );

            //untuk pekerjaan temporary mutasi
            $data['mutasi'] = $this->mutasi_detail_model->all(
                array(
                    'fields'    => 'mutasi_detail.*, mutasi.tanggal AS tgl_mutasi',
                    'left_join' => array(
                        'mutasi' => 'mutasi_detail.mutasi_id = mutasi.id',
                    ),
                    'where'     => array(
                        'mutasi_detail.pns_pnsnip' => $data['users_ekin']->nip,
                        'mutasi_detail.status'     => 0,
                    ),
                ), false
            );

            if ($data['mutasi'] != null) {
                $unor_mutasi_tmp   = $data['mutasi']->pns_unor_baru;
                $id_mkj_mutasi_tmp = $data['mutasi']->id_master_kelas_jabatan_baru;
            }

            //untuk pekerjaan normal
            $data['pns'] = $this->pns_model->all(
                array(
                    'where' => array(
                        "{$dbpresensi}.pns.PNS_PNSNIP" => $data['users_ekin']->nip,
                    ),
                ), false
            );

            $unor   = $data['pns']->PNS_UNOR;
            $id_mkj = $data['pns']->id_master_kelas_jabatan;

            if ($data['mutasi'] != null && ($selected_tgl_input >= $data['mutasi']->tgl_mutasi)) { //untuk tupoksi temporary mutasi / pelantikan
                $data = $this->pekerjaan_model->get_pekerjaan_per_jabatan_and_non_tupoksi_jabatan_baru($unor_mutasi_tmp, $id_mkj_mutasi_tmp);
            } else { //jabatan baru sesuai kelas jabatan baru done //untuk tupoksi normal
                $data = $this->pekerjaan_model->get_pekerjaan_per_jabatan_and_non_tupoksi_jabatan_baru($unor, $id_mkj);
            }

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_pekerjaan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;

        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_analisis_tugas_byuraian()
    {
        $selected_uraian_tugas = decode_crypt($this->input->get('selected_uraian_tugas_add', true)) != null ? decode_crypt($this->input->get('selected_uraian_tugas_add', true)) : $this->input->get('selected_uraian_tugas_edit', true);
        $dbkinerja             = get_config_item('dbkinerja');

        if (!empty($selected_uraian_tugas)) {
            $data = $this->rincian_pekerjaan_model->all(
                array(
                    'fields'    => "{$dbkinerja}.rincian_pekerjaan.*, {$dbkinerja}.satuan.nama AS nm_satuan",
                    'left_join' => array(
                        "{$dbkinerja}.satuan" => "{$dbkinerja}.rincian_pekerjaan.id_satuan = {$dbkinerja}.satuan.id",
                    ),
                    'where'     => array(
                        "{$dbkinerja}.rincian_pekerjaan.id_pekerjaan" => $selected_uraian_tugas,
                    ),
                    'order_by'  => "CASE {$dbkinerja}.rincian_pekerjaan.id_pekerjaan WHEN '28' THEN norma_waktu END ASC,
                                    CASE {$dbkinerja}.rincian_pekerjaan.id_pekerjaan WHEN '25374' THEN norma_waktu END ASC,
                                    CASE WHEN {$dbkinerja}.rincian_pekerjaan.id_pekerjaan != 0 OR {$dbkinerja}.rincian_pekerjaan.id_pekerjaan = '25374' THEN {$dbkinerja}.rincian_pekerjaan.nama_rincian END",
                )
            );
            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_rincian_pekerjaan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_jam_byanalisis_tugas()
    {
        $selected_analisis_tugas = decode_crypt($this->input->get('selected_analisis_tugas', true));
        $dbkinerja               = get_config_item('dbkinerja');

        $data = $this->rincian_pekerjaan_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.rincian_pekerjaan.id" => $selected_analisis_tugas,
                ),
            )
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_kinerja_bawahan_setujui_byid()
    {
        $dbkinerja   = get_config_item('dbkinerja');
        $id_kegiatan = decode_crypt($this->input->get('id_encrypt', true));
        if (!empty($id_kegiatan)) {

            $data = $this->kegiatan_model->all(
                array(
                    'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                    'left_join' => array(
                        "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                        "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                    ),
                    'where'     => array(
                        'kegiatan.id' => $id_kegiatan,
                    ),
                    // 'order_by' => 'id ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_kegiatan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_edit_setujui()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $norma_waktu     = $this->input->get('norma_waktu', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $norma_waktu_max = $data['kegiatan']->norma_wkt;
        $waktu_mulai     = $data['kegiatan']->waktu_mulai;

        if (($norma_waktu > 0) && ($norma_waktu <= $norma_waktu_max)) {
            $waktu_akhir = date('Y-m-d H:i', strtotime($waktu_mulai) + ($norma_waktu * 60));
            $data_edit   = array(
                'status'          => 1,
                'norma_waktu'     => $norma_waktu,
                'waktu_akhir'     => $waktu_akhir,
                'tanggal_periksa' => $this->now,
                'nip_pemeriksa'   => $data['users_ekin']->nip,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function get_kinerja_bawahan_koreksi_byid()
    {
        $dbkinerja   = get_config_item('dbkinerja');
        $id_kegiatan = decode_crypt($this->input->get('id_encrypt', true));
        if (!empty($id_kegiatan)) {

            $data = $this->kegiatan_model->all(
                array(
                    'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                    'left_join' => array(
                        "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                        "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                    ),
                    'where'     => array(
                        'kegiatan.id' => $id_kegiatan,
                    ),
                    // 'order_by' => 'id ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_kegiatan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_edit_koreksi()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $komentar_atasan = $this->input->get('komentar_atasan', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        if ($komentar_atasan != "") {
            $data_edit = array(
                'status'            => 2,
                'nip_pemeriksa'     => $data['users_ekin']->nip,
                'komentar_atasan'   => $komentar_atasan,
                'tanggal_tanggapan' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function get_kinerja_bawahan_tolak_byid()
    {
        $dbkinerja   = get_config_item('dbkinerja');
        $id_kegiatan = decode_crypt($this->input->get('id_encrypt', true));
        if (!empty($id_kegiatan)) {

            $data = $this->kegiatan_model->all(
                array(
                    'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                    'left_join' => array(
                        "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                        "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                    ),
                    'where'     => array(
                        'kegiatan.id' => $id_kegiatan,
                    ),
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_kegiatan_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function save_edit_tolak()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        // $komentar_atasan     = $this->input->get('komentar_atasan', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        if ($id_kegiatanedit != "") {
            $data_edit = array(
                'status'          => 4,
                'norma_waktu'     => 0,
                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                'nip_pemeriksa'   => $data['users_ekin']->nip,
                'tanggal_periksa' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    //koreksi
    public function save_edit_setujui_koreksi()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $norma_waktu     = $this->input->get('norma_waktu', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $norma_waktu_max = $data['kegiatan']->norma_wkt;
        $waktu_mulai     = $data['kegiatan']->waktu_mulai;
        $status          = $data['kegiatan']->status;

        if (($norma_waktu > 0) && ($norma_waktu <= $norma_waktu_max) && ($status == 3)) {
            $waktu_akhir = date('Y-m-d H:i', strtotime($waktu_mulai) + ($norma_waktu * 60));
            $data_edit   = array(
                'status'          => 1,
                'norma_waktu'     => $norma_waktu,
                'waktu_akhir'     => $waktu_akhir,
                'tanggal_periksa' => $this->now,
                'nip_pemeriksa'   => $data['users_ekin']->nip,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_koreksi_koreksi()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $komentar_atasan = $this->input->get('komentar_atasan', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );
        $status = $data['kegiatan']->status;

        if ($komentar_atasan != "" && $status == 3) {
            $data_edit = array(
                'status'            => 2,
                'nip_pemeriksa'     => $data['users_ekin']->nip,
                'komentar_atasan'   => $komentar_atasan,
                'tanggal_tanggapan' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_tolak_koreksi()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );
        $status = $data['kegiatan']->status;

        if ($id_kegiatanedit != "" && $status == 3) {
            $data_edit = array(
                'status'          => 4,
                'norma_waktu'     => 0,
                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                'nip_pemeriksa'   => $data['users_ekin']->nip,
                'tanggal_periksa' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function get_atasan_langsung()
    {
        $unor          = $this->input->get('unor', true);
        $kelas_jabatan = $this->input->get('kelas_jabatan', true);
        if (!empty($unor) && !empty($kelas_jabatan)) {
            $data = $this->pns_model->get_atasan_langsung($unor, $kelas_jabatan);
            // echo $this->db->last_query();die;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    //penilai BKPP
    public function save_edit_setujui_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $norma_waktu     = $this->input->get('norma_waktu', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $norma_waktu_max = $data['kegiatan']->norma_wkt;
        $waktu_mulai     = $data['kegiatan']->waktu_mulai;
        $status          = $data['kegiatan']->status;

        if (($norma_waktu > 0) && ($norma_waktu <= $norma_waktu_max) && ($status == 8)) {
            $waktu_akhir = date('Y-m-d H:i', strtotime($waktu_mulai) + ($norma_waktu * 60));
            $data_edit   = array(
                'status'          => 6,
                'norma_waktu'     => $norma_waktu,
                'waktu_akhir'     => $waktu_akhir,
                'id_penilai'      => $data['users_ekin']->nip,
                'tanggal_penilai' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    

    public function save_edit_koreksi_penilai()
    {
        $id_users         = get_session('id_users');
        $dbkinerja        = get_config_item('dbkinerja');
        $id_kegiatanedit  = decode_crypt($this->input->get('id_kegiatan', true));
        $komentar_penilai = $this->input->get('komentar_penilai', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $status = $data['kegiatan']->status;

        if ($komentar_penilai != "" && $status == 8) {
            $data_edit = array(
                'status'           => 7,
                'id_penilai'       => $data['users_ekin']->nip,
                'komentar_penilai' => $komentar_penilai,
                'tanggal_penilai'  => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_tolak_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $status = $data['kegiatan']->status;

        if ($id_kegiatanedit != "" && $status == 8) {
            $data_edit = array(
                'status'          => 9,
                'norma_waktu'     => 0,
                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                'id_penilai'      => $data['users_ekin']->nip,
                'tanggal_penilai' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_setujui_koreksi_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $norma_waktu     = $this->input->get('norma_waktu', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );

        $norma_waktu_max = $data['kegiatan']->norma_wkt;
        $waktu_mulai     = $data['kegiatan']->waktu_mulai;
        $status          = $data['kegiatan']->status;

        if (($norma_waktu > 0) && ($norma_waktu <= $norma_waktu_max) && ($status == 8)) {
            $waktu_akhir = date('Y-m-d H:i', strtotime($waktu_mulai) + ($norma_waktu * 60));
            $data_edit   = array(
                'status'          => 6,
                'norma_waktu'     => $norma_waktu,
                'waktu_akhir'     => $waktu_akhir,
                'id_penilai'      => $data['users_ekin']->nip,
                'tanggal_penilai' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_koreksi_koreksi_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));
        $komentar_atasan = $this->input->get('komentar_atasan', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );
        $status = $data['kegiatan']->status;

        if ($komentar_atasan != "" && $status == 8) {
            $data_edit = array(
                'status'           => 7,
                'id_penilai'       => $data['users_ekin']->nip,
                'komentar_penilai' => $komentar_atasan,
                'tanggal_penilai'  => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_tolak_koreksi_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );
        $status = $data['kegiatan']->status;

        if ($id_kegiatanedit != "" && $status == 8) {
            $data_edit = array(
                'status'          => 9,
                'norma_waktu'     => 0,
                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                'id_penilai'      => $data['users_ekin']->nip,
                'tanggal_penilai' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function save_edit_tolak_setujui_penilai()
    {
        $id_users        = get_session('id_users');
        $dbkinerja       = get_config_item('dbkinerja');
        $id_kegiatanedit = decode_crypt($this->input->get('id_kegiatan', true));

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $data['kegiatan'] = $this->kegiatan_model->all(
            array(
                'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                'left_join' => array(
                    "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                    "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                ),
                'where'     => array(
                    'kegiatan.id' => $id_kegiatanedit,
                ),
            ), false
        );
        $status = $data['kegiatan']->status;

        if ($id_kegiatanedit != "") {
            $data_edit = array(
                'status'          => 9,
                'norma_waktu'     => 0,
                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                'id_penilai'      => $data['users_ekin']->nip,
                'tanggal_penilai' => $this->now,
            );
            $this->kegiatan_model->edit($id_kegiatanedit, $data_edit);
            return true;
        }
        return false;
    }

    public function get_tpp_gabungan_doc()
    {
        $unor_encrypt  = $this->input->get('unor_encrypt', true);
        $month_encrypt = $this->input->get('month_encrypt', true);
        $year          = $this->input->get('year', true);

        if ($unor_encrypt && $month_encrypt && $year) {
            $data = $this->tpp_gabungan_doc_model->first(
                array(
                    'unor'  => decode_crypt($unor_encrypt),
                    'month' => decode_crypt($month_encrypt),
                    'year'  => $year,
                )
            );
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_pns_by_sopd()
    {
        $selected_sopd  = decode_crypt($this->input->get('selected_sopd', true));
        $selected_month = $this->input->get('selected_month', true);
        $selected_year  = $this->input->get('selected_year', true);
        $dbkinerja      = get_config_item('dbkinerja');
        $dbpresensi     = get_config_item('dbpresensi');

        if (!empty($selected_sopd)) {
            $query1 = $this->pns_model->all(
                array(
                    'fields'      => "pns.id, pns.PNS_PNSNIP, pns.PNS_PNSNAM, pns.PNS_UNOR, pns.PNS_GOLRU AS gol_ruang",
                    'where'       => array(
                        "{$dbpresensi}.pns.PNS_UNOR" => $selected_sopd,
                    ),
                    'where_false' => array(
                        'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex WHERE unor = '{$selected_sopd}')",
                    ),
                    'not_like'    => array(
                        'pns.PNS_PNSNIP' => 'TKD',
                    ),
                ), true, true
            );
            $this->db->reset_query();

            $query2 = $this->mutasi_detail_model->all(
                array(
                    'fields'    => "pns.id, mutasi_detail.pns_pnsnip, pns.PNS_PNSNAM, mutasi_detail.pns_unor_baru, pns.PNS_GOLRU",
                    'left_join' => array(
                        'mutasi' => 'mutasi_detail.mutasi_id = mutasi.id',
                        'pns'    => 'mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP',
                    ),
                    'where'     => array(
                        "{$dbpresensi}.mutasi_detail.pns_unor_baru"                                                                                  => $selected_sopd,
                        'YEAR(mutasi.tanggal)'                                                                                                       => $selected_year,
                        "IF ('{$selected_month}' >= MONTH(mutasi.tanggal),MONTH(mutasi.tanggal) >= '8',MONTH(mutasi.tanggal) = '{$selected_month}')" => null,
                        'mutasi_detail.pns_unor_lama != mutasi_detail.pns_unor_baru'                                                                 => null,
                        'mutasi_detail.status'                                                                                                       => 0,
                    ),
                    'order_by'  => 'gol_ruang DESC',
                ), true, true
            );
            $this->db->reset_query();

            $data = $this->db->query("$query1 UNION $query2")->result();

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_pns'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function tarik_absen()
    {
        $sn         = $this->input->get('sn', true);
        $tanggal    = $this->input->get('tanggal', true);
        $waktu      = $this->input->get('waktu', true);

        if (!empty($sn) && !empty($tanggal) && !empty($waktu)) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => "http://103.123.24.235/fingerspot/api/absen/devicescan?sn=$sn&tanggal=$tanggal&waktu=$waktu",
                'headers'     => [],
                'body'        => [],
                'returnArray' => true,
            ];
            $response = $this->makeRequest($requireOption);

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }


      public function get_kondisi_kerja()
    {
        
        $selected_year =  $this->input->get('selected_year', true);
         if ($selected_year) {
            $data = $this->master_kelas_jabatan_model->all(
                  array(
                    'fields'    => 'master_kelas_jabatan.*,kondisi_kerja.id_kelas_jabatan AS kj,kondisi_kerja.tahun as tahun, kondisi_kerja.id as kondisi_id, unor.nm_unor as NM_UNOR,kondisi_kerja.besaran_tpp, master_unit_organisasi.unit_organisasi, master_jabatan_pns.jabatan_pns, pns.PNS_PNSNIP as nip , pns.PNS_PNSNAM as nama_pns,pns.PNS_GLRDPN as gelar_depan, pns.PNS_GLRBLK as gelar_belakang, master_unit_organisasi.index_jabatan, master_unit_organisasi.status',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        'master_jabatan_pns'     => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        'pns'     => 'pns.id_master_kelas_jabatan = master_kelas_jabatan.id AND pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex)',
                        'kondisi_kerja' => 'kondisi_kerja.id_kelas_jabatan = master_kelas_jabatan.id',
                        'unor' => 'master_kelas_jabatan.unor = unor.kd_unor',
                    ),
                    'where' => array(
                        'master_unit_organisasi.status' => 'aktif',
                        'Status_Jabatan' => 'aktif',
                        'kondisi_kerja.tahun' => $selected_year
                    ),
                    'where_false' => array(
                        'kondisi_kerja.id_kelas_jabatan IS NOT' => 'NULL',
                    ),
                    'order_by'  => 'kelas_jabatan DESC',
                )
            );
               } else {
            $data = array();
        }   

           return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

     public function get_tempat_bertugas()
    {
        
            $url = $this->svc . "api/tempat_bertugas";
            $authToken = get_session('auth_token');
           
            $requireOption = [
                'method'      => 'GET',
                'url'         => $url,
                'headers'     => [
                    'Authorization' => $authToken,
                ],
                'body'        => [],
                'returnArray' => true,
            ];

            $data = $this->makeRequest($requireOption);

         
           return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }


      public function get_kelangkaan_profesi()
    {
        $selected_year =  $this->input->get('selected_year', true);
         if ($selected_year) {
            $data = $this->master_kelas_jabatan_model->all(
                  array(
                    'fields'    => 'master_kelas_jabatan.*,kelangkaan_profesi.id_kelas_jabatan as kj ,kelangkaan_profesi.tahun as tahun, kelangkaan_profesi.id as kelangkaan_id, unor.nm_unor as NM_UNOR,kelangkaan_profesi.besaran_tpp, master_unit_organisasi.unit_organisasi, master_jabatan_pns.jabatan_pns, pns.PNS_PNSNIP as nip , pns.PNS_PNSNAM as nama_pns,pns.PNS_GLRDPN as gelar_depan, pns.PNS_GLRBLK as gelar_belakang, master_unit_organisasi.index_jabatan, master_unit_organisasi.status',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        'master_jabatan_pns'     => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        'pns'     => 'pns.id_master_kelas_jabatan = master_kelas_jabatan.id AND pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex)',
                        'kelangkaan_profesi' => 'kelangkaan_profesi.id_kelas_jabatan = master_kelas_jabatan.id',
                        'unor' => 'master_kelas_jabatan.unor = unor.kd_unor',
                    ),
                    'where' => array(
                        'master_unit_organisasi.status' => 'aktif',
                        'Status_Jabatan' => 'aktif',
                        'kelangkaan_profesi.tahun' => $selected_year
                    ),
                    'where_false' => array(
                        'kelangkaan_profesi.id_kelas_jabatan IS NOT' => 'NULL',
                    ),
                    'order_by'  => 'kelas_jabatan DESC',
                )
            );
               } else {
            $data = array();
        }   
           return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

   

    public function get_master_pengurangan_tpp()
    {
        $tahun = $this->input->get('year', true);
        if ($tahun) {
            $data = $this->master_pengurangan_tpp_model->first([
                'tahun' => $tahun,
            ]);
        } else {
            $data = [];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_tipe_pegawai()
    {
        $is_tpp = $this->input->get('is_tpp', true);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_tipe_pegawai?is_tpp={$is_tpp}",
            'headers'     => [],
            'body'        => [],
            'returnArray' => true,
        ];
        $data = $this->makeRequest($requireOption);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function remove_session()
    {
        $dbpresensi = get_config_item('dbpresensi');
        $this->db->truncate("{$dbpresensi}.ci_sessions");
        return true;
    }

}
