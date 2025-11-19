<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pelantikan_pegawai extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('mutasi_model');
        $this->load->model('mutasi_detail_model');
        $this->load->model('pns_model');
    }

    public function get_data()
    {
        $id_mutasi     = $this->input->get('id_mutasi', true);
        $selected_sopd = $this->input->get('selected_sopd', true);

        $where = array();

        if ($id_mutasi) {
            $where['mutasi.id'] = decode_crypt($id_mutasi);
        }

        if ($selected_sopd) {
            $where['mutasi_detail.pns_unor_baru'] = decode_crypt($selected_sopd);
        }

        $data = $this->mutasi_detail_model->all(
            array(
                'fields'    => "IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                pns.PNS_PNSNIP,,mutasi_detail.id as id_mutasi_detail,mutasi_detail.pns_unor_baru, mutasi.tanggal as tanggal_mutasi,master_unit_organisasi.unit_organisasi as bidang_baru, b.NM_UNOR as asal_sopd, c.nama_jabatan as nama_jabatan_lama, j.NM_GOL, j.NM_PKT, l.nm_unor tujuan_sopd, m.nama_jabatan nama_jabatan_baru,
                d.NM_GENPOS as genpos_lama, e.NM_GENPOS as genpos_baru, IF(mutasi_detail.status = 0, 'Pending', 'Sukses') AS status_text",
                'left_join' => array(
                    'pns'                       => 'pns.PNS_PNSNIP = mutasi_detail.pns_pnsnip',
                    'mutasi'                    => 'mutasi.id = mutasi_detail.mutasi_id',
                    'unor as b'                 => 'mutasi_detail.pns_unor_lama = b.KD_UNOR',
                    'master_kelas_jabatan as c' => 'mutasi_detail.id_master_kelas_jabatan_lama = c.id',
                    'genpos as d'               => 'mutasi_detail.pns_jabstr_lama = d.KD_GENPOS',
                    'genpos as e'               => 'mutasi_detail.pns_jabstr_baru = e.KD_GENPOS',
                    'gol as j'                  => 'pns.PNS_GOLRU = j.KD_GOL',
                    'unor as l'                 => 'mutasi_detail.pns_unor_baru = l.KD_UNOR',
                    'master_kelas_jabatan as m' => 'mutasi_detail.id_master_kelas_jabatan_baru = m.id',
                     'master_unit_organisasi' => 'master_unit_organisasi.id = m.id_master_unit_organisasi',
                ),
                'where'     => $where,
                'order_by'  => 'mutasi_detail.id DESC',
            )
        );
        // echo $this->db->last_query();die;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Pelantikan Pegawai';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Pelantikan Pegawai', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'manajemen pegawai';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);
        $data['mutasi']   = $this->mutasi_model->all(
            array(
                'order_by' => 'tanggal DESC',
            )
        );

        $this->render('pelantikan_pegawai/list', $data);
    }

    public function add()
    {
        $asal_sopd_modal               = $this->input->post('asal_sopd_modal', true);
        $nip_pegawai_modal             = $this->input->post('nip_pegawai_modal', true);
        $id_mutasi_modal               = $this->input->post('id_mutasi_modal', true);
        $tujuan_sopd_modal             = $this->input->post('tujuan_sopd_modal', true);
        $id_master_kelas_jabatan_modal = $this->input->post('id_master_kelas_jabatan_modal', true);

        if (!empty($asal_sopd_modal) && !empty($nip_pegawai_modal) && !empty($id_mutasi_modal) && !empty($tujuan_sopd_modal) && !empty($id_master_kelas_jabatan_modal)) {

            $get_detail_pns = $this->pns_model->first(
                array(
                    'PNS_PNSNIP' => $nip_pegawai_modal,
                )
            );

            if ($get_detail_pns) {
                $data_insert = array(
                    'mutasi_id'                    => decode_crypt($id_mutasi_modal),
                    'pns_pnsnip'                   => $nip_pegawai_modal,
                    'pns_unor_lama'                => decode_crypt($asal_sopd_modal),
                    'id_master_kelas_jabatan_lama' => $get_detail_pns->id_master_kelas_jabatan,
                    'pns_unor_baru'                => decode_crypt($tujuan_sopd_modal),
                    'id_master_kelas_jabatan_baru' => $id_master_kelas_jabatan_modal,
                    'status'                       => '0', //Pending
                    'user_created'                 => $this->_user_login->username,
                    'created_at'                   => $this->now,
                );
                $this->mutasi_detail_model->save($data_insert);

                $message = array(
                    'class'   => 'alert-success',
                    'message' => 'Tambah data sukses',
                );
            } else {
                $message = array(
                    'class'   => 'alert-danger',
                    'message' => 'Data PNS tidak dapat ditemukan',
                );
            }

        } else {
            $message = array(
                'class'   => 'alert-danger',
                'message' => 'Inputan tidak boleh kosong',
            );
        }
        $this->session->set_flashdata('message', array('message' => $message['message'], 'class' => $message['class']));
        redirect('dashboard/pelantikan-pegawai');
    }

    public function add_tanggal_pelantikan()
    {
        $tanggal_pelantikan = $this->input->post('tanggal_pelantikan', true);

        if (!empty($tanggal_pelantikan)) {

            $check = $this->mutasi_model->first(
                array(
                    'tanggal' => $tanggal_pelantikan,
                )
            );

            if (!$check) {
                $data_insert = array(
                    'tanggal'   => $tanggal_pelantikan,
                    'is_active' => '1',
                );
                $this->mutasi_model->save($data_insert);

                $message = array(
                    'class'   => 'alert-success',
                    'message' => 'Tambah data sukses',
                );
            } else {
                $message = array(
                    'class'   => 'alert-danger',
                    'message' => 'Data sudah ada',
                );
            }

        } else {
            $message = array(
                'class'   => 'alert-danger',
                'message' => 'Inputan tidak boleh kosong',
            );
        }
        $this->session->set_flashdata('message', array('message' => $message['message'], 'class' => $message['class']));
        redirect('dashboard/pelantikan-pegawai');
    }

    public function process_pending()
    {
        $id_groups = get_session('id_groups');
        if ($this->input->post() && ($id_groups == '1' || $id_groups == '5')) {
            $id_mutasi_proses_encrypt = $this->input->post('id_mutasi_proses_encrypt', true);
            $id_kode_unor_tujuan_encrypt = $this->input->post('id_kode_unor_tujuan_encrypt',true);
            $get_pending_data = $this->mutasi_detail_model->all(
                array(
                    'where' => array(
                        'mutasi_id' => decode_crypt($id_mutasi_proses_encrypt),
                        'pns_unor_baru' => decode_crypt($id_kode_unor_tujuan_encrypt),
                        'status'    => '0', //Pending
                    ),
                )
            );

            if ($get_pending_data) {
                foreach ($get_pending_data as $row) {
                    $update_mutasi_detail = array(
                        'status'     => '1',
                        'updated_at' => $this->now,
                    );
                    $this->mutasi_detail_model->edit($row->id, $update_mutasi_detail);

                    $update_pns = array(
                        'PNS_UNOR'                => $row->pns_unor_baru,
                        'id_master_kelas_jabatan' => $row->id_master_kelas_jabatan_baru,
                    );
                    $this->pns_model->edit_where(
                        array(
                            'PNS_PNSNIP' => $row->pns_pnsnip,
                        ),
                        $update_pns
                    );
                }
                $message = array(
                    'class'   => 'alert-success',
                    'message' => 'Proses mutasi pending berhasil.',
                );
            } else {
                $message = array(
                    'class'   => 'alert-danger',
                    'message' => 'Tidak ada mutasi yang pending.',
                );
            }
        } else {
            $message = array(
                'class'   => 'alert-danger',
                'message' => 'Ada kesalahan, silakan hubungi superadmin.',
            );
        }
        $this->session->set_flashdata('message', array('message' => $message['message'], 'class' => $message['class']));
        redirect('dashboard/pelantikan-pegawai');
    }

    
    public function delete()
    {
        $id_mutasi_detail = $this->input->get('id_mutasi_detail', true);
        if ($id_mutasi_detail) {
            $action = $this->mutasi_detail_model->delete($id_mutasi_detail);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/pelantikan_pegawai');
        }
    }

}
