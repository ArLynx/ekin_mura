<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_koreksi extends MY_Controller
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
        $this->page_title = 'Kegiatan Koreksi';
        $this->auth       = false;
    }

    public function get_data()
    {
        $id_users         = get_session('id_users');
        $dbkinerja        = get_config_item('dbkinerja');
        $selected_year    = $this->input->get('selected_year', true);
        $selected_month   = $this->input->get('selected_month', true);
        $selected_penilai = $this->input->get('selected_penilai', true);

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $year  = $selected_year;
        $month = ($selected_month == 0 ? date("m") : ($selected_month < 10 ? '0' . $selected_month : $selected_month));

        if ($selected_penilai == 'Atasan Langsung') {
            $data = $this->kegiatan_model->all(
                array(
                    'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian',
                    'left_join' => array(
                        "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                        "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                    ),
                    'where'     => array(
                        "{$dbkinerja}.kegiatan.pns_pnsnip" => $data['users_ekin']->nip,
                        'YEAR(waktu_mulai)'                => $year,
                        'MONTH(waktu_mulai)'               => $month,
                        '(status = 2 or status = 3)'       => null,
                    ),
                    'order_by'  => 'waktu_mulai ASC',
                )
            );
        } else if ($selected_penilai == 'Tim Penilai') {
            $data = $this->kegiatan_model->all(
                array(
                    'fields'    => 'kegiatan.*, pekerjaan.nama_pekerjaan as nm_pekerjaan, rincian_pekerjaan.nama_rincian as nm_rincian',
                    'left_join' => array(
                        "{$dbkinerja}.pekerjaan"         => 'pekerjaan.id = kegiatan.pekerjaan_id',
                        "{$dbkinerja}.rincian_pekerjaan" => 'rincian_pekerjaan.id = kegiatan.rincian_pekerjaan_id',
                    ),
                    'where'     => array(
                        "{$dbkinerja}.kegiatan.pns_pnsnip" => $data['users_ekin']->nip,
                        'YEAR(waktu_mulai)'                => $year,
                        'MONTH(waktu_mulai)'               => $month,
                        '(status = 7 or status = 8)'       => null,
                    ),
                    'order_by'  => 'waktu_mulai ASC',
                )
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
            ['link' => '', 'title' => 'Kegiatan Koreksi', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'kegiatan';

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);
        $data['month']      = date("m");

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $data['all_penilai'] = array('Atasan Langsung', 'Tim Penilai');

        $this->render('kegiatan_koreksi/list', $data);
    }

    public function edit($id_encrypt = null)
    {
        if (is_null($id_encrypt)) {
            show_404();
        }

        $id = decode_crypt($id_encrypt);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('uraian_tugas', 'uraian tugas', 'required');
        $this->form_validation->set_rules('analisis_tugas', 'analisis tugas', 'required');
        $this->form_validation->set_rules('tgl_input', 'tgl_input', 'required');
        // $this->form_validation->set_rules('jam_input', 'jam_input', "required|callback_jam_check[{$id}]");
        $this->form_validation->set_rules('jam_input', 'jam_input', "required|callback_jam_check_24jam[{$id}]");
        $this->form_validation->set_rules('durasi_input', 'durasi_input', 'required|callback_durasi_check');
        $this->form_validation->set_rules('nama_kegiatan', 'nama kegiatan', 'required');
        $this->form_validation->set_rules('hasil_pekerjaan', 'hasil pekerjaan', 'required');

        $id_users   = get_session('id_users');
        $dbkinerja  = get_config_item('dbkinerja');
        $dbpresensi = get_config_item('dbpresensi');

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        if (!$data['users_ekin']) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/kegiatan_koreksi'), 'title' => 'Kegiatan Koreksi', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Kegiatan Koreksi', 'icon' => '', 'active' => '1'],
            ];

            $data['pns'] = $this->pns_model->all(
                array(
                    'where' => array(
                        "{$dbpresensi}.pns.PNS_PNSNIP" => $data['users_ekin']->nip,
                    ),
                ), false
            );

            // $data['maping'] = $this->pekerjaan_maping_model->all(
            //     array(
            //         'where'     => array(
            //             "{$dbkinerja}.pekerjaan_maping.id_master_kelas_jabatan" => $data['pns']->id_master_kelas_jabatan,
            //         ),
            //     ), false
            // );

            // if($data['maping'] != null){
            //     $genpos                     = $data['maping']->KD_GENPOS;
            //     $no_jabfus                  = $data['maping']->no_master_jabfus;
            //     $kd_fpos                    = $data['maping']->KD_FPOS;
            //     $id_master_kelas_jabatan    = $data['maping']->id_master_kelas_jabatan;
            // }

            // $unor = $data['pns']->PNS_UNOR;

            // if($data['maping'] != null){
            //     if($genpos != null){
            //         if($genpos == '9999'){
            //             $data['uraian_tugas'] = $this->pekerjaan_model->get_pekerjaan_per_jabatan_and_non_tupoksi_jfu($unor, $no_jabfus);
            //         } else if($genpos == 'FT'){
            //             $data['uraian_tugas'] = $this->pekerjaan_model->get_pekerjaan_per_jabatan_and_non_tupoksi_jft($unor, $kd_fpos);
            //         } else {
            //             $data['uraian_tugas'] = $this->pekerjaan_model->get_pekerjaan_per_jabatan_and_non_tupoksi($unor, $genpos);
            //         }
            //     } else { //jabatan baru sesuai kelas jabatan baru blm dipakai
            //         $data['uraian_tugas'] = $this->pekerjaan_model->all(
            //             array(
            //                 'where'     => array(
            //                     "{$dbkinerja}.pekerjaan.PNS_UNOR" => $data['pns']->PNS_UNOR,
            //                     "{$dbkinerja}.pekerjaan.id_jabatan" => $id_master_kelas_jabatan,
            //                 ),
            //                 'order_by'  => 'pekerjaan.prioritas ASC',
            //             )
            //         );
            //     }
            // } else { //jabatan baru sesuai kelas jabatan baru done
            //     $data['uraian_tugas'] = $this->pekerjaan_model->all(
            //         array(
            //             'where'     => array(
            //                 "{$dbkinerja}.pekerjaan.PNS_UNOR" => $data['pns']->PNS_UNOR,
            //                 "{$dbkinerja}.pekerjaan.id_master_kelas_jabatan" => $data['pns']->id_master_kelas_jabatan,
            //             ),
            //             'order_by'  => 'pekerjaan.prioritas ASC',
            //         )
            //     );
            // }

            $data['kegiatan'] = $this->kegiatan_model->first(
                array('id' => decode_crypt($id_encrypt))
            );

            // $data['analisis_tugas_kegiatan'] = $this->rincian_pekerjaan_model->all(
            //     array(
            //         'fields'    => "{$dbkinerja}.rincian_pekerjaan.*, {$dbkinerja}.satuan.nama AS nm_satuan",
            //         'left_join' => array(
            //             "{$dbkinerja}.satuan" => "{$dbkinerja}.rincian_pekerjaan.id_satuan = {$dbkinerja}.satuan.id",
            //         ),
            //         'where'     => array(
            //             "{$dbkinerja}.rincian_pekerjaan.id_pekerjaan"  => $data['kegiatan']->pekerjaan_id,
            //         ),
            //         'order_by'  => "{$dbkinerja}.rincian_pekerjaan.nama_rincian ASC",
            //     )
            // );

            $this->render('kegiatan_koreksi/edit', $data);
        } else {

            $data['kegiatan'] = $this->kegiatan_model->first(
                array('id' => decode_crypt($id_encrypt))
            );

            $name         = 'file_pendukung';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(get_config_item('image_path'));
                $type           = 'image';
                $file_pendukung = $this->upload_file->upload($name, get_config_item('image_path'), $type, null, null, false, false, current_url());
                unlink_file(get_config_item('image_path') . $data['kegiatan']->file_pendukung);
            } else {
                $file_pendukung = $data['kegiatan']->file_pendukung;
            }

            $name         = 'dokumen_lampiran';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(get_config_item('lampiran_path'));
                $type             = 'file';
                $dokumen_lampiran = $this->upload_file->upload($name, get_config_item('lampiran_path'), $type, null, null, false, false, current_url());
                unlink_file(get_config_item('lampiran_path') . $data['kegiatan']->dokumen_lampiran);
            } else {
                $dokumen_lampiran = $data['kegiatan']->dokumen_lampiran;
            }

            $waktu_mulai = date('Y-m-d H:i', strtotime($this->input->post('tgl_input', true) . ' ' . $this->input->post('jam_input', true)));
            $norma_waktu = $this->input->post('durasi_input', true);
            $status      = $this->input->post('status', true);

            $status_change = ($status == 2 ? 3 : ($status == 7 ? 8 : $status));

            $data_action = array(
                'pns_pnsnip'           => $data['users_ekin']->nip,
                'pekerjaan_id'         => decode_crypt($this->input->post('uraian_tugas', true)),
                'rincian_pekerjaan_id' => decode_crypt($this->input->post('analisis_tugas', true)),
                'status'               => $status_change,
                'norma_waktu'          => $norma_waktu,
                'waktu_mulai'          => $waktu_mulai,
                'waktu_akhir'          => date('Y-m-d H:i', strtotime($waktu_mulai) + ($norma_waktu * 60)),
                'file_pendukung'       => $file_pendukung,
                'dokumen_lampiran'     => $dokumen_lampiran,
                'nama_kegiatan'        => $this->input->post('nama_kegiatan', true),
                'output'               => $this->input->post('hasil_pekerjaan', true),
            );

            $action = $this->kegiatan_model->edit(decode_crypt($id_encrypt), $data_action);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/kegiatan_koreksi');
        }
    }

    public function jam_check($jam_input, $id)
    {
        $id_users  = get_session('id_users');
        $dbkinerja = get_config_item('dbkinerja');

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $tgl_input    = $this->input->post('tgl_input', true);
        $durasi_input = $this->input->post('durasi_input', true);

        // $nip = $this->kinerja->nip();
        $nip   = $data['users_ekin']->nip;
        $mulai = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $jam_input) + 60);
        $akhir = date('Y-m-d H:i', strtotime($mulai) + ($durasi_input * 60) - 120);

        $tgl_kerja_input   = date('Y-m-d', strtotime($tgl_input));
        $tahun_kerja_input = date('Y', strtotime($tgl_input));

        // param0 = tgl : param1 = durasi : param2 = id : param3 = jam

        if ($tgl_kerja_input >= '2019-09-17' && $tgl_kerja_input <= '2019-09-24') { //Penerapan jam karena kabut asap
            $allowed_range1 = "07:30";
            $allowed_range2 = "15:30";
        } else {
            $allowed_range1 = "07:00";
            $allowed_range2 = "15:30";
        }

        $akhir_check = date('H:i', strtotime($mulai) + ($durasi_input * 60) - 60);

        $mulai_jam = date('H:i', strtotime($tgl_input . ' ' . $jam_input));
        $akhir_jam = date('H:i', strtotime($akhir_check) + ($durasi_input * 60));

        $jam_istirahat1 = date('H:i', strtotime("12:00"));
        $jam_istirahat2 = "13:00";

        //istirahat jumat
        $what_date_waktu_mulai = date('l', strtotime($tgl_input));
        $jam_istirahat3        = date('H:i', strtotime("11:30"));
        $jam_istirahat4        = "13:00";

        ////Ramadhan
        $allowed_range1r      = "08:00";
        $allowed_range2r      = "15:00";
        $allowed_range2rjumat = "15:30";

        $jam_istirahat1r = date('H:i', strtotime("12:00"));
        $jam_istirahat2r = "12:30";

        //istirahat jumat
        $jam_istirahat3r = date('H:i', strtotime("11:30"));
        $jam_istirahat4r = "12:30";

        // $this->form_validation->set_message('jam_check', (strtotime($mulai_jam) < strtotime($allowed_range1) || strtotime($akhir_check) <= strtotime($allowed_range1) || strtotime($akhir_check) > strtotime($allowed_range2)));
        // return false;

        $tgl_pekerjaan = date('Y-m-d', strtotime($this->input->post('tgl')));

        // if(strtotime($mulai_jam) < strtotime($allowed_range1) || strtotime($akhir_check) <= strtotime($allowed_range1) || strtotime($akhir_check) > strtotime($allowed_range2)) {
        //     $this->form_validation->set_message('jam_check', "Maaf, kegiatan tidak dapat diinput diluar jam kerja, jam kerja antara {$allowed_range1} - {$allowed_range2}");
        //     return FALSE;
        // }

        if ($tgl_pekerjaan >= _get_awal_ramadhan($tahun_kerja_input)->bulan && $tgl_pekerjaan <= _get_akhir_ramadhan($tahun_kerja_input)->bulan) {
            if ($what_date_waktu_mulai == 'Friday') {
                if (strtotime($mulai_jam) < strtotime($allowed_range1r) || strtotime($akhir_check) <= strtotime($allowed_range1r) || strtotime($akhir_check) > strtotime($allowed_range2rjumat)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan tidak dapat diinput diluar jam kerja, jam kerja antara {$allowed_range1r} - {$allowed_range2rjumat}");
                    return false;
                }
            } else {
                if (strtotime($mulai_jam) < strtotime($allowed_range1r) || strtotime($akhir_check) <= strtotime($allowed_range1r) || strtotime($akhir_check) > strtotime($allowed_range2r)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan tidak dapat diinput diluar jam kerja, jam kerja antara {$allowed_range1r} - {$allowed_range2r}");
                    return false;
                }
            }

            if ($what_date_waktu_mulai == 'Friday') {
                if (strtotime($akhir_jam) > strtotime($jam_istirahat3r) && strtotime($mulai_jam) < strtotime($jam_istirahat4r)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan di jam istirahat tidak dapat diinputkan..");
                    return false;
                }
            } else {
                if (strtotime($akhir_jam) > strtotime($jam_istirahat1r) && strtotime($mulai_jam) < strtotime($jam_istirahat2r)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan di jam istirahat tidak dapat diinputkan..");
                    return false;
                }
            }
        } else {
            if (strtotime($mulai_jam) < strtotime($allowed_range1) || strtotime($akhir_check) <= strtotime($allowed_range1) || strtotime($akhir_check) > strtotime($allowed_range2)) {
                $this->form_validation->set_message('jam_check', "Maaf, kegiatan tidak dapat diinput diluar jam kerja, jam kerja antara {$allowed_range1} - {$allowed_range2}");
                return false;
            }

            if ($what_date_waktu_mulai == 'Friday') {
                if (strtotime($akhir_jam) > strtotime($jam_istirahat3) && strtotime($mulai_jam) < strtotime($jam_istirahat4)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan di jam istirahat tidak dapat diinputkan.");
                    return false;
                }
            } else {
                if (strtotime($akhir_jam) > strtotime($jam_istirahat1) && strtotime($mulai_jam) < strtotime($jam_istirahat2)) {
                    $this->form_validation->set_message('jam_check', "Maaf, kegiatan di jam istirahat tidak dapat diinputkan.");
                    return false;
                }
            }
        }

        // $this->form_validation->set_message('jam_check', 'Maaf sedang dalam perbaikan..');
        // return false;

        // $sekarang = date('Y-m-d H:i:s', now());
        $sekarang = date('Y-m-d H:i:s');

        // $hk = $this->kinerja->is_5_hari_kerja($nip) ? 5 : 6;
        $hk  = 5;
        $day = date('l', strtotime($tgl_input));
        // $is_jdwl_kerja = $this->kinerja_model->get_jam_kerja($hk, $day)->num_rows();
        $is_jdwl_kerja = $this->kegiatan_model->get_jam_kerja($hk, $day)->num_rows();
        $is_lbr_kerja  = $this->kegiatan_model->get_hari_libur(date('Y-m-d', strtotime($tgl_input)));
        //$a=$this->db->last_query();
        if ($is_jdwl_kerja) {
            $hari   = $this->kegiatan_model->get_jam_kerja($hk, $day)->row();
            $msk    = substr($hari->masuk, 0, -3);
            $plg    = substr($hari->pulang, 0, -3);
            $masuk  = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $hari->masuk));
            $pulang = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $hari->pulang));
        } else {
            $msk    = "07:00";
            $plg    = "15:30";
            $masuk  = date('Y-m-d H:i', strtotime($tgl_input . ' ' . '07:00'));
            $pulang = date('Y-m-d H:i', strtotime($tgl_input . ' ' . '15:30'));
        }

        if (date('m') == "01") {
            $m_now = 13;
            $y_now = date('Y') - 1;
        } else {
            $m_now = date('m');
            $y_now = date('Y');
        }

        $jml_row = $this->kegiatan_model->cek_jam_exist($nip, $mulai, $akhir, $id)->num_rows();
        if ($jml_row > 0) {
            $this->form_validation->set_message('jam_check', "Waktu yang Anda masukkan sudah dialokasikan untuk pekerjaan lain.");
            return false;
        } elseif ($sekarang < $akhir) {
            // $this->form_validation->set_message('jam_check', "Waktu yang Anda masukkan melebihi waktu sekarang.".$this->kegiatan_model->get_tgl_max_approve()->row()->bulan);
            $this->form_validation->set_message('jam_check', "Waktu yang Anda masukkan melebihi waktu sekarang.");
            return false;
        } elseif (((date('d') > $this->kegiatan_model->get_tgl_max_entry()->row()->bulan && date('m', strtotime($akhir)) <= ($m_now - 1) && date('Y', strtotime($akhir)) == $y_now) || (date('m', strtotime($akhir)) < ($m_now - 1) && date('Y', strtotime($akhir)) == $y_now)
            || date('Y', strtotime($akhir)) < $y_now) && (($this->kegiatan_model->get_open_input_bulanan($data['users_ekin']->nip, date('m', strtotime('-1 month'))) <= 0))) {
            $this->form_validation->set_message('jam_check', "Waktu meng-<i>input</i> Pekerjaan bulan lalu telah berakhir.");
            return false;
        } elseif ($is_jdwl_kerja == 0 || $is_lbr_kerja == 1) {
            $this->form_validation->set_message('jam_check', "Waktu yang Anda masukkan adalah <b>hari libur</b>.");
            return false;
        } else {
            return true;
        }
    }

    public function jam_check_24jam($jam_input, $id)
    {
        $id_users  = get_session('id_users');
        $dbkinerja = get_config_item('dbkinerja');

        $data['users_ekin'] = $this->users_ekin_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.users.id" => $id_users,
                ),
            ), false
        );

        $tgl_input    = $this->input->post('tgl_input', true);
        $durasi_input = $this->input->post('durasi_input', true);

        $nip   = $data['users_ekin']->nip;
        $mulai = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $jam_input) + 60);
        $akhir = date('Y-m-d H:i', strtotime($mulai) + ($durasi_input * 60) - 120);

        $sekarang      = date('Y-m-d H:i:s');
        $hk            = 5;
        $day           = date('l', strtotime($tgl_input));
        $is_jdwl_kerja = $this->kegiatan_model->get_jam_kerja($hk, $day)->num_rows();
        $is_lbr_kerja  = $this->kegiatan_model->get_hari_libur(date('Y-m-d', strtotime($tgl_input)));

        if ($is_jdwl_kerja) {
            $hari   = $this->kegiatan_model->get_jam_kerja($hk, $day)->row();
            $msk    = substr($hari->masuk, 0, -3);
            $plg    = substr($hari->pulang, 0, -3);
            $masuk  = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $hari->masuk));
            $pulang = date('Y-m-d H:i', strtotime($tgl_input . ' ' . $hari->pulang));
        } else {
            $msk    = "07:00";
            $plg    = "15:30";
            $masuk  = date('Y-m-d H:i', strtotime($tgl_input . ' ' . '07:00'));
            $pulang = date('Y-m-d H:i', strtotime($tgl_input . ' ' . '15:30'));
        }

        if (date('m') == "01") {
            $m_now = 13;
            $y_now = date('Y') - 1;
        } else {
            $m_now = date('m');
            $y_now = date('Y');
        }

        $jml_row = $this->kegiatan_model->cek_jam_exist($nip, $mulai, $akhir, $id)->num_rows();
        if ($jml_row > 0) {
            $this->form_validation->set_message('jam_check_24jam', "Waktu yang Anda masukkan sudah dialokasikan untuk pekerjaan lain.");
            return false;
        } elseif ($sekarang < $akhir) {
            $this->form_validation->set_message('jam_check_24jam', "Waktu yang Anda masukkan melebihi waktu sekarang.");
            return false;
        } elseif (((date('d') > $this->kegiatan_model->get_tgl_max_entry()->row()->bulan && date('m', strtotime($akhir)) <= ($m_now - 1) && date('Y', strtotime($akhir)) == $y_now) || (date('m', strtotime($akhir)) < ($m_now - 1) && date('Y', strtotime($akhir)) == $y_now)
            || date('Y', strtotime($akhir)) < $y_now) && (($this->kegiatan_model->get_open_input_bulanan($data['users_ekin']->nip, date('m', strtotime('-1 month'))) <= 0))) {
            $this->form_validation->set_message('jam_check_24jam', "Waktu meng-<i>input</i> Pekerjaan bulan lalu telah berakhir.");
            return false;
        }
        // elseif ($is_jdwl_kerja == 0 || $is_lbr_kerja == 1) {
        //     $this->form_validation->set_message('jam_check_24jam', "Waktu yang Anda masukkan adalah <b>hari libur</b>.");
        //     return false;
        // }
        else {
            return true;
        }
    }

    public function durasi_check()
    {
        $id_analisis_tugas = decode_crypt($this->input->post('analisis_tugas', true));
        $durasi_input      = $this->input->post('durasi_input', true);

        $data['rincian_pekerjaan'] = $this->rincian_pekerjaan_model->all(
            array(
                'where' => array(
                    'rincian_pekerjaan.id' => $id_analisis_tugas,
                ),
            ), false
        );
        if (is_null($data['rincian_pekerjaan'])) {
            show_404();
        }
        $durasi_max = $data['rincian_pekerjaan']->norma_waktu;
        if ($durasi_input >= 0 && $durasi_input <= $durasi_max) {
            return true;
        } else {
            $this->form_validation->set_message('durasi_check', "Durasi yang diperbolehkan antara 0 sampai $durasi_max menit.");
            return false;
        }
    }

}
