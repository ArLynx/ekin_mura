<?php
# @Author: Awan Tengah
# @Date:   2019-08-06T08:43:46+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-02T14:44:43+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Perintah_tugas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('perintah_tugas_model');
    }

    public function get_data()
    {
        $data = $this->perintah_tugas_model->all(
            array(
                'fields'      => 'perintah_tugas.*, unor.NM_UNOR',
                'left_join'   => array(
                    'unor' => 'unor.KD_UNOR = perintah_tugas.unor',
                ),
                'where_false' => array(
                    'perintah_tugas.unor' => $this->_user_login->unor,
                    'deleted_at IS '      => 'NULL',
                ),
            )
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Perintah Tugas';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'administrasi kepegawaian', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'perintah tugas', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'adm kepegawaian';
        $this->render('perintah_tugas/index', $data);
    }

    public function add()
    {
        if (!$this->input->post()) {
            $data['page_title'] = 'Tambah Perintah Tugas';
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/perintah-tugas'), 'title' => 'perintah tugas', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'tambah perintah tugas', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'administrasi_kepegawaian';
            $this->render('perintah_tugas/edit', $data);
        } else {
            $data_save = array(
                'unor'            => $this->input->post('unor', true),
                'no_surat'        => $this->input->post('no_surat', true),
                'dasar_penugasan' => $this->input->post('dasar_penugasan', true),
                'memerintahkan'   => $this->input->post('memerintahkan', true),
                'keperluan'       => $this->input->post('keperluan', true),
                'lama_penugasan'  => $this->input->post('lama_penugasan', true),
                'penandatangan'   => $this->input->post('penandatangan', true),
                'created_at'      => $this->now,
            );
            $this->perintah_tugas_model->save($data_save);

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
                $data['page_title'] = 'Ubah Perintah Tugas';
                $data['breadcrumb'] = [
                    ['link' => site_url('dashboard/perintah-tugas'), 'title' => 'perintah tugas', 'icon' => '', 'active' => '0'],
                    ['link' => '', 'title' => 'ubah perintah tugas', 'icon' => '', 'active' => '1'],
                ];
                $data['active_menu'] = 'administrasi_kepegawaian';
                $check_exists        = $this->perintah_tugas_model->first($id);
                if (!$check_exists) {
                    show_404();
                }
                $data['id_perintah_tugas'] = $id;
                $this->render('perintah_tugas/edit', $data);
            }
        } else {
            if ($this->input->post('unor', true) == get_session('unor')) {
                $data_save = array(
                    'unor'            => $this->input->post('unor', true),
                    'no_surat'        => $this->input->post('no_surat', true),
                    'dasar_penugasan' => $this->input->post('dasar_penugasan', true),
                    'memerintahkan'   => $this->input->post('memerintahkan', true),
                    'keperluan'       => $this->input->post('keperluan', true),
                    'lama_penugasan'  => $this->input->post('lama_penugasan', true),
                    'penandatangan'   => $this->input->post('penandatangan', true),
                    'updated_at'      => $this->now,
                );
                $this->perintah_tugas_model->edit($id, $data_save);

                $message = array(
                    'type' => 'success',
                    'msg'  => 'Ubah data sukses',
                );
            } else {
                $message = array(
                    'type' => 'danger',
                    'msg'  => 'Ubah data gagal',
                );
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    public function delete($id = null)
    {
        if (!is_null($id)) {
            $check_exists = $this->perintah_tugas_model->first($id);
            if ($check_exists) {
                if ($check_exists->unor == get_session('unor')) {
                    $date_edit = array(
                        'deleted_at' => $this->now,
                    );
                    $this->perintah_tugas_model->edit($id, $date_edit);

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
