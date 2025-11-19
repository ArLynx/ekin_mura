<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entry_manual extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('absen_enroll_model');
        $this->load->model('kegiatan_model');
        $this->load->model('pns_model');
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor = $this->input->get('unor', true);
        } else {
            $unor = get_session('unor');
        }
        $day      = $this->input->get('day', true);
        $month    = $this->input->get('month', true);
        $year     = $this->input->get('year', true);
        $tipe_pegawai = $this->input->get('tipe_pegawai', true);
        if (!empty($unor) && !empty($day) && !empty($month) && !empty($year) && !is_null($tipe_pegawai) && $tipe_pegawai != 'null') {
            
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_absen_pegawai?unor={$unor}&day={$day}&month={$month}&year={$year}&tipe_pegawai={$tipe_pegawai}",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data = $this->makeRequest($requireOption)->data;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Entry Manual';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'entry manual', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'entry_manual';

        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/setting?nama=max_date_edit_presence",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data['max_date_edit_presence'] = $this->makeRequest($requireOption)->data->bulan ?? "1";

        $this->render('entry_manual/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $id_pns       = $this->input->post('id_pns', true);
            $PNS_PNSNIP   = $this->input->post('PNS_PNSNIP', true);
            $PNS_PNSNAM   = $this->input->post('PNS_PNSNAM', true);
            $PNS_GLRDPN   = $this->input->post('PNS_GLRDPN', true);
            $PNS_GLRBLK   = $this->input->post('PNS_GLRBLK', true);
            $PNS_UNOR     = $this->input->post('PNS_UNOR', true);
            $tanggal      = $this->input->post('tanggal', true);
            $waktu_manual = $this->input->post('waktu_manual', true);
            $type         = $this->input->post('type', true);
            $uraian       = $this->input->post('uraian', true);
            if (!empty($waktu_manual) && !empty($uraian)) {
                $code      = $PNS_PNSNIP;
                $save_data = array(
                    'code'       => $code,
                    'PNS_PNSNIP' => $PNS_PNSNIP,
                    'PNS_PNSNAM' => $PNS_PNSNAM,
                    'PNS_GLRDPN' => $PNS_GLRDPN,
                    'PNS_GLRBLK' => $PNS_GLRBLK,
                    'PNS_UNOR'   => $PNS_UNOR,
                    'tanggal'    => $tanggal,
                    'waktu'      => $waktu_manual,
                    'jenis'      => $type,
                    'keterangan' => 0,
                    'uraian'     => $uraian,
                );
                $this->absen_enroll_model->save($save_data);

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
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id_absen_enroll = $this->input->post('id_absen_enroll', true);
            $id_pns          = $this->input->post('id_pns', true);
            $PNS_PNSNIP      = $this->input->post('PNS_PNSNIP', true);
            $PNS_PNSNAM      = $this->input->post('PNS_PNSNAM', true);
            $PNS_GLRDPN      = $this->input->post('PNS_GLRDPN', true);
            $PNS_GLRBLK      = $this->input->post('PNS_GLRBLK', true);
            $PNS_UNOR        = $this->input->post('PNS_UNOR', true);
            $tanggal         = $this->input->post('tanggal', true);
            $waktu_manual    = $this->input->post('waktu_manual', true);
            $type            = $this->input->post('type', true);
            $uraian          = $this->input->post('uraian', true);
            if (!empty($id_absen_enroll) && !empty($waktu_manual) && !empty($uraian)) {
                $code      = $PNS_PNSNIP;
                $edit_data = array(
                    'code'       => $code,
                    'PNS_PNSNIP' => $PNS_PNSNIP,
                    'PNS_PNSNAM' => $PNS_PNSNAM,
                    'PNS_GLRDPN' => $PNS_GLRDPN,
                    'PNS_GLRBLK' => $PNS_GLRBLK,
                    'PNS_UNOR'   => $PNS_UNOR,
                    'tanggal'    => $tanggal,
                    'waktu'      => $waktu_manual,
                    'jenis'      => $type,
                    'keterangan' => 0,
                    'uraian'     => $uraian,
                );
                $this->absen_enroll_model->edit($id_absen_enroll, $edit_data);

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

    public function delete($id_absen_enroll = null)
    {
        if (!is_null($id_absen_enroll)) {
            $check_exists = $this->absen_enroll_model->first($id_absen_enroll);
            if ($check_exists) {
                $id_groups = get_session('id_groups');
                if ($check_exists->PNS_UNOR == get_session('unor') || $id_groups == 1 || $id_groups == 5) {
                    $this->absen_enroll_model->delete($id_absen_enroll);

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
                    'msg'  => 'Data absen tidak ada',
                );
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($message));
        }
    }

}
