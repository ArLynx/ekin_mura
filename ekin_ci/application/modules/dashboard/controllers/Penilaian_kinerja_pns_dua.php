<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian_kinerja_pns_dua extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kegiatan_model');
        $this->load->model('kinerja/users_ekin_model', 'users_ekin_model');
        $this->load->model('pns_model');
        $this->load->model('unor_model');

        $this->page_title = 'Penilaian Kinerja PNS II';
        $this->auth       = false;
    }

    public function get_data()
    {
        // $id_temp = decode_crypt($this->input->get('id_temp', true));
        $dbkinerja        = get_config_item('dbkinerja');
        $selected_sopd    = decode_crypt($this->input->get('selected_sopd', true));
        $selected_pns     = decode_crypt($this->input->get('selected_pns', true));
        $selected_pns_tmp = decode_crypt($this->input->get('id_pns_temp', true));
        $selected_status  = $this->input->get('selected_status', true);
        $selected_year    = $this->input->get('selected_year', true);
        $selected_month   = $this->input->get('selected_month', true);

        set_session(
            array(
                'selected_sopd_nilai'         => $selected_sopd,
                'selected_sopd_nilai_encrypt' => encode_crypt($selected_sopd),
                'selected_pns_nilai'          => $selected_pns,
                'selected_pns_nilai_encrypt'  => encode_crypt($selected_pns),
            )
        );

        $selected_pnsx = $selected_pns != null ? $selected_pns : $selected_pns_tmp;

        $data['pns'] = $this->pns_model->all(
            array(
                'where' => array(
                    'pns.id' => $selected_pnsx,
                ),
            ), false
        );

        $year                = $selected_year;
        $month               = ($selected_month == 0 ? date("m") : ($selected_month < 10 ? '0' . $selected_month : $selected_month));
        $selected_status_val = ($selected_status == 'Pengajuan' ? 1 : ($selected_status == 'Disetujui' ? 6 : ($selected_status == 'Dikoreksi' ? 7 : 9)));

        if ($selected_status_val == 7) {
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
                        '(status = 7 or status = 8)'       => null,
                    ),
                    'order_by'  => 'waktu_mulai ASC',
                )
            );
        } else {
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
                        'status'                           => $selected_status_val,
                    ),
                    'order_by'  => 'waktu_mulai ASC',
                ) //, false
            );
        }

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
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Penilaian Kinerja PNS Dua', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'penilaian';

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);
        $data['month']      = date("m");

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5 || $id_groups == 4) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else if ($id_groups == 99) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_pembagian_sopd?id_users=" . $this->_user_login->id : base_url('api/get_pembagian_sopd?id_users=' . $this->_user_login->id);
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd']   = json_decode($get_all_sopd);
        $data['all_status'] = array('Pengajuan', 'Disetujui', 'Dikoreksi', 'Ditolak');

        $this->render('penilaian_kinerja_pns_dua/list', $data);
    }

    public function tanggapan_all_kegiatan_penilai()
    {
        $id_users         = get_session('id_users');
        $dbkinerja        = get_config_item('dbkinerja');
        $idkegiatancektop = $this->input->post('idkegiatancektop', true);
        $countmaxs        = $this->input->post('countmax', true);
        $countmax_ar      = explode(', ', $countmaxs);
        $jml_armax        = count($countmax_ar);
        $summax           = $this->input->post('summax', true);
        $stt              = $this->input->post('stt', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        if ($jml_armax == $summax) {
            for ($i = 0; $i < count($countmax_ar); $i++) {
                $data_tanggapan_keg = $this->kegiatan_model->first(
                    array(
                        "kegiatan.id" => $countmax_ar[$i],
                    )
                );

                $data['kegiatan'] = $this->kegiatan_model->all(
                    array(
                        'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                        'left_join' => array(
                            "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                            "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                        ),
                        'where'     => array(
                            'kegiatan.id' => $countmax_ar[$i],
                        ),
                    ), false
                );
                $status = $data['kegiatan']->status;

                if ($data_tanggapan_keg) {
                    if ($stt == '1') {
                        if ($status != 7) {
                            $data_updated = array(
                                'status'          => 6,
                                // 'jam_kerja' => 1,
                                'id_penilai'      => $data['users_ekin']->nip,
                                'tanggal_penilai' => $this->now,
                            );
                            $this->kegiatan_model->edit($countmax_ar[$i], $data_updated);

                            $this->session->set_flashdata('message', array('message' => 'Semua pekerjaan berhasil disetujui', 'class' => 'alert-success'));
                        }
                    } else {
                        if ($status != 7) {
                            $data_updated = array(
                                'status'          => 9,
                                'norma_waktu'     => 0,
                                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                                // 'jam_kerja' => 1,
                                'id_penilai'      => $data['users_ekin']->nip,
                                'tanggal_penilai' => $this->now,
                            );
                            $this->kegiatan_model->edit($countmax_ar[$i], $data_updated);

                            $this->session->set_flashdata('message', array('message' => 'Semua pekerjaan berhasil ditolak', 'class' => 'alert-success'));
                        }
                    }

                } else {
                    show_404();
                }
            }
        } else {
            for ($i = 0; $i < count($idkegiatancektop); $i++) {
                $data_tanggapan_keg = $this->kegiatan_model->first(
                    array(
                        "kegiatan.id" => $idkegiatancektop[$i],
                    )
                );

                $data['kegiatan'] = $this->kegiatan_model->all(
                    array(
                        'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian, rincian_pekerjaan.norma_waktu as norma_wkt',
                        'left_join' => array(
                            "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                            "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                        ),
                        'where'     => array(
                            'kegiatan.id' => $idkegiatancektop[$i],
                        ),
                    ), false
                );
                $status = $data['kegiatan']->status;

                if ($data_tanggapan_keg) {
                    if ($stt == '1') {
                        if ($status != 7) {
                            $data_updated = array(
                                'status'          => 6,
                                // 'jam_kerja' => 1,
                                'id_penilai'      => $data['users_ekin']->nip,
                                'tanggal_penilai' => $this->now,
                            );
                            $this->kegiatan_model->edit($idkegiatancektop[$i], $data_updated);

                            $this->session->set_flashdata('message', array('message' => 'Pekerjaan berhasil disetujui', 'class' => 'alert-success'));
                        }
                    } else {
                        //tolak
                        if ($status != 7) {
                            $data_updated = array(
                                'status'          => 9,
                                'norma_waktu'     => 0,
                                'waktu_akhir'     => $data['kegiatan']->waktu_mulai,
                                // 'jam_kerja' => 1,
                                'id_penilai'      => $data['users_ekin']->nip,
                                'tanggal_penilai' => $this->now,
                            );
                            $this->kegiatan_model->edit($idkegiatancektop[$i], $data_updated);

                            $this->session->set_flashdata('message', array('message' => 'Pekerjaan berhasil ditolak', 'class' => 'alert-success'));
                        }
                    }

                } else {
                    show_404();
                }
            }
        }

        redirect('dashboard/penilaian_kinerja_pns_dua');
    }

}
