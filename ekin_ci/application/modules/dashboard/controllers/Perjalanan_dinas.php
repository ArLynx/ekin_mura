<?php
# @Author: Awan Tengah
# @Date:   2019-08-10T22:53:26+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-16T09:37:12+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Perjalanan_dinas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('perjalanan_dinas_model');
    }

    public function get_data()
    {
        $data = $this->perjalanan_dinas_model->all(
            array(
                'fields'      => 'perjalanan_dinas.*, unor.NM_UNOR',
                'left_join'   => array(
                    'unor' => 'unor.KD_UNOR = perjalanan_dinas.unor',
                ),
                'where_false' => array(
                    'perjalanan_dinas.unor' => $this->_user_login->unor,
                    'deleted_at IS '        => 'NULL',
                ),
            )
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Perjalanan Dinas';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'administrasi kepegawaian', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'perjalanan dinas', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'adm kepegawaian';
        $this->render('perjalanan_dinas/index', $data);
    }

    public function add()
    {
        if (!$this->input->post()) {
            $data['page_title'] = 'Tambah Perjalanan Dinas';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/perjalanan-dinas'), 'title' => 'perjalanan dinas', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'tambah perjalanan dinas', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'administrasi_kepegawaian';
            $this->render('perjalanan_dinas/edit', $data);
        } else {
            $check_exists = $this->perjalanan_dinas_model->first(
                array(
                    'unor'     => $this->_user_login->unor,
                    'no_surat' => $this->input->post('no_surat', true),
                )
            );
            if (!$check_exists) {
                $data_save = array(
                    'unor'              => $this->_user_login->unor,
                    'no_surat'          => $this->input->post('no_surat', true),
                    'pejabat_perintah'  => $this->input->post('pejabat_perintah', true),
                    'memerintahkan'     => $this->input->post('memerintahkan', true),
                    'tingkat_biaya'     => $this->input->post('tingkat_biaya', true),
                    'maksud_perjalanan' => $this->input->post('maksud_perjalanan', true),
                    'alat_angkutan'     => $this->input->post('alat_angkutan', true),
                    'tempat_berangkat'  => $this->input->post('tempat_berangkat', true),
                    'tempat_tujuan'     => $this->input->post('tempat_tujuan', true),
                    'lama_perjalanan'   => $this->input->post('lama_perjalanan', true),
                    'tanggal_berangkat' => $this->input->post('tanggal_berangkat', true),
                    'tanggal_kembali'   => $this->input->post('tanggal_kembali', true),
                    'instansi_pa'       => $this->input->post('instansi_pa', true),
                    'no_mata_anggaran'  => $this->input->post('no_mata_anggaran', true),
                    'mata_anggaran'     => $this->input->post('mata_anggaran', true),
                    'keterangan_lain'   => $this->input->post('keterangan_lain', true),
                    'penandatangan'     => $this->input->post('penandatangan', true),
                    'created_at'        => $this->now,
                );
                $this->perjalanan_dinas_model->save($data_save);

                $message = array(
                    'type' => 'success',
                    'msg'  => 'Tambah data sukses',
                );
            } else {
                $message = array(
                    'type' => 'danger',
                    'msg'  => "Data dengan nomor surat {$this->input->post('no_surat', true)} sudah ada",
                );
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    public function edit($id = null)
    {
        if (!$this->input->post()) {
            if (!is_null($id)) {
                $data['page_title'] = 'Ubah Perjalanan Dinas';
                $data['breadcrumb'] = [
                    ['link' => site_url('dashboard/perjalanan-dinas'), 'title' => 'perjalanan dinas', 'icon' => '', 'active' => '0'],
                    ['link' => '', 'title' => 'ubah perjalanan dinas', 'icon' => '', 'active' => '1'],
                ];
                $data['active_menu'] = 'administrasi_kepegawaian';
                $check_exists        = $this->perjalanan_dinas_model->first($id);
                if (!$check_exists) {
                    show_404();
                }
                $data['id_perjalanan_dinas'] = $id;
                $this->render('perjalanan_dinas/edit', $data);
            }
        } else {
            $data_save = array(
                'unor'              => $this->_user_login->unor,
                'no_surat'          => $this->input->post('no_surat', true),
                'pejabat_perintah'  => $this->input->post('pejabat_perintah', true),
                'memerintahkan'     => $this->input->post('memerintahkan', true),
                'tingkat_biaya'     => $this->input->post('tingkat_biaya', true),
                'maksud_perjalanan' => $this->input->post('maksud_perjalanan', true),
                'alat_angkutan'     => $this->input->post('alat_angkutan', true),
                'tempat_berangkat'  => $this->input->post('tempat_berangkat', true),
                'tempat_tujuan'     => $this->input->post('tempat_tujuan', true),
                'lama_perjalanan'   => $this->input->post('lama_perjalanan', true),
                'tanggal_berangkat' => $this->input->post('tanggal_berangkat', true),
                'tanggal_kembali'   => $this->input->post('tanggal_kembali', true),
                'instansi_pa'       => $this->input->post('instansi_pa', true),
                'no_mata_anggaran'  => $this->input->post('no_mata_anggaran', true),
                'mata_anggaran'     => $this->input->post('mata_anggaran', true),
                'keterangan_lain'   => $this->input->post('keterangan_lain', true),
                'penandatangan'     => $this->input->post('penandatangan', true),
                'updated_at'        => $this->now,
            );
            $this->perjalanan_dinas_model->edit($id, $data_save);

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
            $check_exists = $this->perjalanan_dinas_model->first($id);
            if ($check_exists) {
                if ($check_exists->unor == get_session('unor')) {
                    $date_edit = array(
                        'deleted_at' => $this->now,
                    );
                    $this->perjalanan_dinas_model->edit($id, $date_edit);

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
