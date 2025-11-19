<?php
# @Author: Awan Tengah
# @Date:   2019-08-18T23:46:31+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-19T10:07:31+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan_cuti extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('pengajuan_cuti_model');
    }

    public function get_data()
    {
        $data = $this->pengajuan_cuti_model->all(
            array(
                'fields'      => 'pengajuan_cuti.*, unor.NM_UNOR, master_jenis_cuti.jenis_cuti',
                'left_join'   => array(
                    'unor'              => 'unor.KD_UNOR = pengajuan_cuti.unor',
                    'master_jenis_cuti' => 'master_jenis_cuti.id = pengajuan_cuti.id_master_jenis_cuti',
                ),
                'where_false' => array(
                    'pengajuan_cuti.unor'           => $this->_user_login->unor,
                    'pengajuan_cuti.deleted_at IS ' => 'NULL',
                ),
            )
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Pengajuan Cuti';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'administrasi kepegawaian', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'pengajuan cuti', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'adm kepegawaian';
        $this->render('pengajuan_cuti/index', $data);
    }

    public function add()
    {
        if (!$this->input->post()) {
            $data['page_title'] = 'Tambah Pengajuan Cuti';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/pengajuan-cuti'), 'title' => 'pengajuan cuti', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'tambah pengajuan cuti', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'administrasi_kepegawaian';
            $this->render('pengajuan_cuti/edit', $data);
        } else {
            $data_save = array(
                'unor'                 => $this->_user_login->unor,
                'no_surat'             => $this->input->post('no_surat', true),
                'pegawai'              => $this->input->post('pegawai', true),
                'id_master_jenis_cuti' => $this->input->post('id_master_jenis_cuti', true),
                'lama_cuti'            => $this->input->post('lama_cuti', true),
                'penandatangan'        => $this->input->post('penandatangan', true),
                'created_at'           => $this->now,
            );
            $this->pengajuan_cuti_model->save($data_save);

            $message = array(
                'type' => 'success',
                'msg'  => 'Tambah data sukses',
            );

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    public function edit($id = null)
    {
        if (!$this->input->post()) {
            if (!is_null($id)) {
                $data['page_title'] = 'Ubah Pengajuan Cuti';
                $data['breadcrumb'] = [
                    ['link' => site_url('dashboard/pengajuan-cuti'), 'title' => 'pengajuan cuti', 'icon' => '', 'active' => '0'],
                    ['link' => '', 'title' => 'ubah pengajuan cuti', 'icon' => '', 'active' => '1'],
                ];
                $data['active_menu'] = 'administrasi_kepegawaian';
                $check_exists        = $this->pengajuan_cuti_model->first($id);
                if (!$check_exists) {
                    show_404();
                }
                $data['id_pengajuan_cuti'] = $id;
                $this->render('pengajuan_cuti/edit', $data);
            }
        } else {
            $data_save = array(
                'unor'                 => $this->_user_login->unor,
                'no_surat'             => $this->input->post('no_surat', true),
                'pegawai'              => $this->input->post('pegawai', true),
                'id_master_jenis_cuti' => $this->input->post('id_master_jenis_cuti', true),
                'lama_cuti'            => $this->input->post('lama_cuti', true),
                'penandatangan'        => $this->input->post('penandatangan', true),
                'updated_at'           => $this->now,
            );
            $this->pengajuan_cuti_model->edit($id, $data_save);

            $message = array(
                'type' => 'success',
                'msg'  => 'Ubah data sukses',
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    public function delete($id = null)
    {
        if (!is_null($id)) {
            $check_exists = $this->pengajuan_cuti_model->first($id);
            if ($check_exists) {
                if ($check_exists->unor == get_session('unor')) {
                    $date_edit = array(
                        'deleted_at' => $this->now,
                    );
                    $this->pengajuan_cuti_model->edit($id, $date_edit);

                    $message = array(
                        'type' => 'success',
                        'msg'  => 'Hapus data sukses',
                    );
                } else {
                    $message = array(
                        'type' => 'danger',
                        'msg'  => 'You don\'t allowed to access..',
                    );
                }
            } else {
                $message = array(
                    'type' => 'danger',
                    'msg'  => 'Data tidak ada',
                );
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

}
