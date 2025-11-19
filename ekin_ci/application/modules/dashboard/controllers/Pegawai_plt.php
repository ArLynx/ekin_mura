<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_plt extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('pns_model');
        $this->load->model('pns_plt_model');
        $this->load->model('unor_model');

        $this->page_title = 'Pegawai Plt';
    }

    public function get_data()
    {
        $selected_sopd = $this->input->get('selected_sopd', true);

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor  = decode_crypt($selected_sopd);
            $where = array(
                'pns.PNS_PNSNIP NOT IN ' => "(SELECT nip FROM pns_ex)",
            );
        } else {
            $unor = get_session('unor');
        }

        if ($selected_sopd) {
            $where = array(
                'pns.PNS_UNOR'           => $unor,
                'pns.PNS_PNSNIP NOT IN ' => "(SELECT nip FROM pns_ex)",
            );
        }

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
        $tmp    = array();
        $except = ['awal_plt', 'akhir_plt'];
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                if (!in_array($childkey, $except)) {
                    $tmp[$key][$childkey] = $childrow;
                }
            }
            foreach ($except as $rowe) {
                $tmp[$key][$rowe] = !is_null($row->{$rowe}) ? to_date_format($row->{$rowe}, 'F Y') : null;
            }
            $tmp[$key]['id_encrypt'] = encode_crypt($row->id);
            $tmp[$key]['sk_plt_path'] = base_url() . path_image('sk_plt_path');
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
            ['link' => '', 'title' => 'Pegawai Plt', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'manajemen pegawai';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('pegawai_plt/list', $data);
    }

    public function action()
    {
        $id_encrypt   = $this->input->get('id_encrypt', true);
        $akhir_plt    = $this->input->get('akhir_plt', true);
        $check_exists = $this->pns_plt_model->first(decode_crypt($id_encrypt));
        if (!$check_exists) {
            show_404();
        }
        if ($id_encrypt && $akhir_plt) {
            $akhir_plt = $akhir_plt . '-' . date('t', strtotime($akhir_plt));

            $data = array(
                'akhir_plt'  => $akhir_plt,
                'updated_at' => $this->now,
            );

            $this->pns_plt_model->edit(decode_crypt($id_encrypt), $data);

            $response = [
                'title'  => 'PLT selesai',
                'text'   => 'Aksi berhasil',
                'status' => 'success',
            ];
        } else {
            $response = [
                'title'  => 'Gagal',
                'text'   => 'Proses gagal, bulan selesai wajib dipilih',
                'status' => 'error',
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function add($unor_encrypt = null)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unor', 'SOPD', 'required');
        $this->form_validation->set_rules('pns_pnsnip', 'PNS', 'required');
        $this->form_validation->set_rules('pns_unor_plt', 'Plt di SOPD', 'required');
        $this->form_validation->set_rules('id_master_kelas_jabatan_plt', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('awal_plt', 'mulai bulan', 'required');
        if (empty($_FILES['sk_plt']['name'])) {
            $this->form_validation->set_rules('sk_plt', 'SK Plt', 'required');
        }

        if (!is_null($unor_encrypt)) {
            $check_unor_exists = $this->unor_model->first(
                array(
                    'KD_UNOR' => decode_crypt($unor_encrypt),
                )
            );
            if (!$check_unor_exists) {
                show_404();
            }
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/pegawai-plt'), 'title' => 'Pegawai Plt', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Pegawai Plt', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'manajemen pegawai';

            $id_groups = get_session('id_groups');
            if ($id_groups == 1 || $id_groups == 5) {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            } else {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            }
            $data['all_sopd'] = json_decode($get_all_sopd);

            $link_get_all_sopd   = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd        = file_get_contents($link_get_all_sopd);
            $data['all_sopd_to'] = json_decode($get_all_sopd);

            $data['selected_unor'] = !is_null($unor_encrypt) ? $check_unor_exists->KD_UNOR : null;
            $data['is_add']        = true;

            $this->render('pegawai_plt/edit', $data);
        } else {
            $name         = 'sk_plt';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(path_image('sk_plt_path'));
                $type   = 'file';
                $sk_plt = $this->upload_file->upload($name, path_image('sk_plt_path'), $type, null, null, false, false, current_url());
            }

            $awal_plt  = $this->input->post('awal_plt', true) . '-01';

            $data = array(
                'pns_pnsnip'                  => $this->input->post('pns_pnsnip', true),
                'pns_unor_plt'                => $this->input->post('pns_unor_plt', true),
                'id_master_kelas_jabatan_plt' => $this->input->post('id_master_kelas_jabatan_plt', true),
                'awal_plt'                    => $awal_plt,
                'sk_plt'                      => $sk_plt,
                'created_at'                  => $this->now,
            );
            $this->pns_plt_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/pegawai-plt');
        }
    }

    public function edit($id_encrypt = null)
    {
        if (is_null($id_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unor', 'SOPD', 'required');
        $this->form_validation->set_rules('pns_pnsnip', 'PNS', 'required');
        $this->form_validation->set_rules('pns_unor_plt', 'Plt di SOPD', 'required');
        $this->form_validation->set_rules('id_master_kelas_jabatan_plt', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('awal_plt', 'mulai bulan', 'required');
        // if (empty($_FILES['sk_plt']['name'])) {
        //     $this->form_validation->set_rules('sk_plt', 'SK Plt', 'required');
        // }

        $check_exists            = $this->pns_plt_model->first(decode_crypt($id_encrypt));
        $check_exists->awal_plt  = date('Y-m', strtotime($check_exists->awal_plt));
        if (!$check_exists) {
            show_404();
        } else {
            $detail_pns = $this->pns_model->get_detail_pns($check_exists->pns_pnsnip);
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/pegawai-plt'), 'title' => 'Pegawai Plt', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Pegawai Plt', 'icon' => '', 'active' => '1'],
            ];
            $data['active_menu'] = 'manajemen pegawai';

            $id_groups = get_session('id_groups');
            if ($id_groups == 1 || $id_groups == 5) {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            } else {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            }
            $data['all_sopd'] = json_decode($get_all_sopd);

            $link_get_all_sopd   = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd        = file_get_contents($link_get_all_sopd);
            $data['all_sopd_to'] = json_decode($get_all_sopd);

            $data['selected_unor'] = isset($detail_pns) ? $detail_pns->PNS_UNOR : null;
            $data['detail_pns']    = $detail_pns;
            $data['pns_plt']       = $check_exists;

            $link_get_all_master_kelas_jabatan = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_master_kelas_jabatan?unor=" . $check_exists->pns_unor_plt : base_url('api/get_all_master_kelas_jabatan?unor=' . $check_exists->pns_unor_plt);
            $get_all_master_kelas_jabatan      = file_get_contents($link_get_all_master_kelas_jabatan);
            $data['all_master_kelas_jabatan']  = json_decode($get_all_master_kelas_jabatan);

            $data['is_add'] = false;

            $this->render('pegawai_plt/edit', $data);
        } else {
            $name         = 'sk_plt';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(path_image('sk_plt_path'));
                $type   = 'file';
                $sk_plt = $this->upload_file->upload($name, path_image('sk_plt_path'), $type, null, null, false, false, current_url());
                unlink_file(path_image('sk_plt_path') . $check_exists->sk_plt);
            } else {
                $sk_plt = $check_exists->sk_plt;
            }

            $awal_plt  = $this->input->post('awal_plt', true) . '-01';

            $data = array(
                'pns_pnsnip'                  => $this->input->post('pns_pnsnip', true),
                'pns_unor_plt'                => $this->input->post('pns_unor_plt', true),
                'id_master_kelas_jabatan_plt' => $this->input->post('id_master_kelas_jabatan_plt', true),
                'awal_plt'                    => $awal_plt,
                'sk_plt'                      => $sk_plt,
                'updated_at'                  => $this->now,
            );
            $this->pns_plt_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/pegawai-plt');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {
            $action = $this->pns_plt_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/pegawai-plt');
        }
    }

}
