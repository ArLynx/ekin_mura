<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_agama_model');
        $this->load->model('pengaturan_shift_model');
        $this->load->model('pns_model');
        $this->load->model('tipe_pegawai_model');
        $this->load->model('tkd_detail_model');
        $this->load->model('unor_model');
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor = $this->input->get('unor', true);
        } else {
            $unor = get_session('unor');
        }

        $tipe_pegawai = $this->input->get('tipe_pegawai', true);

        $data = [];

        if (!is_null($tipe_pegawai) && $tipe_pegawai != 'null') {
            if ($tipe_pegawai != 0) { //Record untuk non pns
                $requireOption = [
                    'method'      => 'GET',
                    'url'         => $this->svc . "api/get_pegawai_non_tpp?unor={$unor}&tipe_pegawai={$tipe_pegawai}",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [],
                    'returnArray' => true,
                ];
                $data = $this->makeRequest($requireOption)->data ?? [];
            }
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index($pegawai_type = null)
    {
        if (!is_null($pegawai_type)) {
            $data['breadcrumb'] = [
                ['link' => '', 'title' => 'data pegawai', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu']       = 'data pegawai';
            $data['data_agama']        = $this->master_agama_model->all();
            $data['data_tipe_pegawai'] = $this->tipe_pegawai_model->all();

            $data['id_groups'] = get_session('id_groups');
            $data['unor']      = get_session('unor');

            if ($pegawai_type == 'non-pns') {
                $data['page_title'] = 'Data Pegawai Non TPP';
                $this->render('pegawai/index_non_pns', $data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function add($pegawai_type = null)
    {
        if (!is_null($pegawai_type)) {
            if ($pegawai_type == 'non-pns') {
                $nip            = $this->input->post('nip', true);
                $sopd           = $this->input->post('sopd', true);
                $tipe_pegawai   = $this->input->post('tipe_pegawai', true);
                $gelar_depan    = $this->input->post('gelar_depan', true);
                $nama           = $this->input->post('nama', true);
                $gelar_belakang = $this->input->post('gelar_belakang', true);
                $agama          = $this->input->post('agama', true);
                $tempat_lahir   = $this->input->post('tempat_lahir', true);
                $tanggal_lahir  = $this->input->post('tanggal_lahir', true);
                $hari_kerja     = $this->input->post('hari_kerja', true);
                $alamat         = $this->input->post('alamat', true);

                if (!empty($sopd) && !empty($tipe_pegawai) && !empty($nama) && !empty($agama) && !empty($tempat_lahir) && !empty($tanggal_lahir) && !empty($hari_kerja) && !empty($alamat)) {
                    $name         = 'file';
                    $check_upload = !empty($_FILES[$name]['name']);
                    if ($check_upload) {
                        $this->load->library('upload_file');
                        create_folder(path_image('user_path'));
                        $type = 'image';
                        $foto = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());
                    } else {
                        $foto = null;
                    }

                    $save_data_to_pns = array(
                        'PNS_PNSNIP'      => ($tipe_pegawai == '3' ? $nip : ''),
                        'PNS_GLRDPN'      => (!is_null($gelar_depan) && !is_null($gelar_depan) && !empty($gelar_depan)) ? $gelar_depan : null,
                        'PNS_PNSNAM'      => $nama,
                        'PNS_GLRBLK'      => (!is_null($gelar_belakang) && !is_null($gelar_belakang) && !empty($gelar_belakang)) ? $gelar_belakang : null,
                        'PNS_UNOR'        => $sopd,
                        'IS_TKD'          => '1',
                        'ID_TIPE_PEGAWAI' => $tipe_pegawai,
                    );
                    $get_id_pns = $this->pns_model->save($save_data_to_pns);

                    if ($tipe_pegawai != '3') {
                        $update_data_to_pns = array(
                            'PNS_PNSNIP' => 'TKD' . $get_id_pns,
                        );
                        $this->pns_model->edit($get_id_pns, $update_data_to_pns);
                    }

                    $save_data_to_tkd_detail = array(
                        'id_tkd'          => $get_id_pns,
                        'nama'            => $nama,
                        'id_tipe_pegawai' => $tipe_pegawai,
                        'id_master_agama' => $agama,
                        'tempat_lahir'    => $tempat_lahir,
                        'tanggal_lahir'   => $tanggal_lahir,
                        'alamat'          => $alamat,
                        'foto'            => $foto,
                        'hari_kerja'      => $hari_kerja,
                        'created_at'      => $this->now,
                    );
                    $this->tkd_detail_model->save($save_data_to_tkd_detail);

                    $message = array(
                        'type' => 'success',
                        'msg'  => 'Tambah data sukses',
                    );
                } else {
                    $message = array(
                        'type' => 'danger',
                        'msg'  => 'Inputan tidak boleh kosong',
                    );
                }
            } elseif ($pegawai_type == 'pns') {
                $sopd           = $this->input->post('sopd', true);
                $gelar_depan    = $this->input->post('gelar_depan', true);
                $nama           = $this->input->post('nama', true);
                $gelar_belakang = $this->input->post('gelar_belakang', true);

                if (!empty($sopd) && !empty($nama)) {
                    $name         = 'file';
                    $check_upload = !empty($_FILES[$name]['name']);
                    if ($check_upload) {
                        $this->load->library('upload_file');
                        create_folder(path_image('user_path'));
                        $type = 'image';
                        $foto = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());
                    } else {
                        $foto = null;
                    }

                    $save_data_to_pns = array(
                        'PNS_PNSNIP' => '',
                        'PNS_GLRDPN' => $gelar_depan,
                        'PNS_PNSNAM' => $nama,
                        'PNS_GLRBLK' => $gelar_belakang,
                        'PNS_UNOR'   => $sopd,
                    );
                    $get_id_pns = $this->pns_model->save($save_data_to_pns);

                    $message = array(
                        'type' => 'success',
                        'msg'  => 'Tambah data sukses',
                    );
                } else {
                    $message = array(
                        'type' => 'danger',
                        'msg'  => 'Inputan tidak boleh kosong',
                    );
                }
            } else {
                $message = array(
                    'type' => 'danger',
                    'msg'  => 'Something went wrong',
                );
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    // public function upload_foto($name = null)
    // {
    //     if (!is_null($name)) {
    //         $check_upload = !empty($_FILES[$name]['name']);
    //         if ($check_upload) {
    //             $this->load->library('upload_file');
    //             create_folder(path_image('user_path'));
    //             $type = 'image';
    //             $photo = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());

    //             $message = array(
    //                 'type' => 'success',
    //                 'msg' => 'Upload foto sukses',
    //                 'filename' => $photo
    //             );
    //         } else {
    //             $message = array(
    //                 'type' => 'danger',
    //                 'msg' => 'Gagal upload foto'
    //             );
    //         }
    //         return $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode($message));
    //     }
    // }

    public function edit()
    {
        $id_tkd         = $this->input->post('id_tkd', true);
        $nip            = $this->input->post('nip', true);
        $sopd           = $this->input->post('sopd', true);
        $tipe_pegawai   = $this->input->post('tipe_pegawai', true);
        $gelar_depan    = $this->input->post('gelar_depan', true);
        $nama           = $this->input->post('nama', true);
        $gelar_belakang = $this->input->post('gelar_belakang', true);
        $agama          = $this->input->post('agama', true);
        $tempat_lahir   = $this->input->post('tempat_lahir', true);
        $tanggal_lahir  = $this->input->post('tanggal_lahir', true);
        $hari_kerja     = $this->input->post('hari_kerja', true);
        $alamat         = $this->input->post('alamat', true);

        if (!empty($id_tkd) && !empty($sopd) && !empty($tipe_pegawai) && !empty($nama) && !empty($agama) && !empty($tempat_lahir) && !empty($tanggal_lahir) && !empty($hari_kerja) && !empty($alamat)) {
            $get_pegawai = $this->tkd_detail_model->first(
                array(
                    'id_tkd' => $id_tkd,
                )
            );
            if ($get_pegawai) {
                $name         = 'file';
                $check_upload = !empty($_FILES[$name]['name']);
                if ($check_upload) {
                    $this->load->library('upload_file');
                    create_folder(path_image('user_path'));
                    $type = 'image';
                    $foto = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());
                    unlink_file(path_image('user_path') . $get_pegawai->foto);
                } else {
                    $foto = $get_pegawai->foto;
                }
            }

            $update_data = array(
                'PNS_PNSNIP'      => ($tipe_pegawai == '3' ? $nip : ''),
                'PNS_GLRDPN'      => (!is_null($gelar_depan) && $gelar_depan != 'null' && !empty($gelar_depan)) ? $gelar_depan : null,
                'PNS_PNSNAM'      => $nama,
                'PNS_GLRBLK'      => (!is_null($gelar_belakang) && $gelar_belakang != 'null' && !empty($gelar_belakang)) ? $gelar_belakang : null,
                'PNS_UNOR'        => $sopd,
                'IS_TKD'          => '1',
                'ID_TIPE_PEGAWAI' => $tipe_pegawai,
            );
            $this->pns_model->edit($id_tkd, $update_data);

            if ($tipe_pegawai != '3') {
                $update_data_to_pns = array(
                    'PNS_PNSNIP' => 'TKD' . $id_tkd,
                );
                $this->pns_model->edit($id_tkd, $update_data_to_pns);
            }

            $update_data_to_tkd_detail = array(
                'id_tkd'          => $id_tkd,
                'nama'            => $nama,
                'id_tipe_pegawai' => $tipe_pegawai,
                'id_master_agama' => $agama,
                'tempat_lahir'    => $tempat_lahir,
                'tanggal_lahir'   => $tanggal_lahir,
                'alamat'          => $alamat,
                'foto'            => $foto,
                'hari_kerja'      => $hari_kerja,
                'updated_at'      => $this->now,
            );
            $this->tkd_detail_model->edit_where(array('id_tkd' => $id_tkd), $update_data_to_tkd_detail);

            $message = array(
                'type' => 'success',
                'msg'  => 'Ubah data sukses',
            );
        } else {
            $message = array(
                'type' => 'danger',
                'msg'  => 'Inputan tidak boleh kosong',
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($message));
    }
}
