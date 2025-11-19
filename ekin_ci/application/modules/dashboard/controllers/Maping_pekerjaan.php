<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Maping_pekerjaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('pekerjaan_maping_model');
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('unor_model');
        $this->load->model('genpos_model');
        $this->load->model('master_jabfus_model');
        $this->load->model('fpos_model');
        $this->load->model('pekerjaan_model');
        $this->page_title = 'Maping Pekerjaan';
    }

    public function get_data()
    {
        $selected_sopd       = decode_crypt($this->input->get('selected_sopd', true));
        $selected_sopd_crypt = $this->input->get('selected_sopd', true);

        $unor       = get_session('unor');
        $unor_crypt = encode_crypt(get_session('unor'));
        // var_dump($unor);die;

        // $dbkinerja = get_config_item('dbkinerja');
        $dbpresensi = get_config_item('dbpresensi');

        if ($selected_sopd) {
            $data = $this->pekerjaan_maping_model->all(
                array(
                    'fields'    => 'pekerjaan_maping.*, genpos.*, master_jabfus.*, fpos.*, master_kelas_jabatan.id AS id_mkj, master_kelas_jabatan.unor AS unor_mkj, master_kelas_jabatan.id_master_unit_organisasi, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        "{$dbpresensi}.genpos"                 => 'genpos.KD_GENPOS = pekerjaan_maping.KD_GENPOS',
                        "{$dbpresensi}.master_jabfus"          => "master_jabfus.no = pekerjaan_maping.no_master_jabfus and master_jabfus.unor = {$selected_sopd}",
                        "{$dbpresensi}.fpos"                   => 'fpos.KD_FPOS = pekerjaan_maping.KD_FPOS',
                        "{$dbpresensi}.master_kelas_jabatan"   => 'master_kelas_jabatan.id = pekerjaan_maping.id_master_kelas_jabatan',
                        "{$dbpresensi}.master_unit_organisasi" => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'pekerjaan_maping.unor' => $selected_sopd,
                    ),
                    'order_by'  => 'pekerjaan_maping.KD_GENPOS ASC',
                    'pekerjaan_maping.no_master_jabfus ASC',
                    'pekerjaan_maping.KD_FPOS ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_encrypt']   = encode_crypt($row->id);
                $tmp[$key]['unor_encrypt'] = $selected_sopd_crypt;
            }
            $data = $tmp;
        } else if ($unor != null) { //user kepegawaian
            $data = $this->pekerjaan_maping_model->all(
                array(
                    'fields'    => 'pekerjaan_maping.*, genpos.*, master_jabfus.*, fpos.*, master_kelas_jabatan.id AS id_mkj, master_kelas_jabatan.unor AS unor_mkj, master_kelas_jabatan.id_master_unit_organisasi, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        "{$dbpresensi}.genpos"                 => 'genpos.KD_GENPOS = pekerjaan_maping.KD_GENPOS',
                        "{$dbpresensi}.master_jabfus"          => "master_jabfus.no = pekerjaan_maping.no_master_jabfus and master_jabfus.unor = {$selected_sopd}",
                        "{$dbpresensi}.fpos"                   => 'fpos.KD_FPOS = pekerjaan_maping.KD_FPOS',
                        "{$dbpresensi}.master_kelas_jabatan"   => 'master_kelas_jabatan.id = pekerjaan_maping.id_master_kelas_jabatan',
                        "{$dbpresensi}.master_unit_organisasi" => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'pekerjaan_maping.unor' => $unor,
                    ),
                    'order_by'  => 'pekerjaan_maping.KD_GENPOS ASC',
                    'pekerjaan_maping.no_master_jabfus ASC',
                    'pekerjaan_maping.KD_FPOS ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_encrypt']   = encode_crypt($row->id);
                $tmp[$key]['unor_encrypt'] = $unor_crypt;
            }
            $data = $tmp;
        } else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Maping Pekerjaan', 'icon' => '', 'active' => '1'],
        ];

        $id_groups = get_session('id_groups');
        if ($id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('maping_pekerjaan/list', $data);
    }

    public function add($unor_encrypt = null)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unor', 'unor', 'required');
        $this->form_validation->set_rules('kd_genpos', 'kd_genpos', 'required');
        $this->form_validation->set_rules('id_master_kelas_jabatan', 'id_master_kelas_jabatan', 'required');

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
                ['link' => site_url('dashboard/maping-pekerjaan'), 'title' => 'Maping Pekerjaan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Maping Pekerjaan', 'icon' => '', 'active' => '1'],
            ];

            // $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            // $get_all_sopd      = file_get_contents($link_get_all_sopd);
            // $data['all_sopd']  = json_decode($get_all_sopd);
            $id_groups = get_session('id_groups');
            if ($id_groups == 5) {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            } else {
                $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
                $get_all_sopd      = file_get_contents($link_get_all_sopd);
            }
            $data['all_sopd'] = json_decode($get_all_sopd);

            $data['selected_unor'] = !is_null($unor_encrypt) ? $check_unor_exists->KD_UNOR : null;

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

            if (array_search(decode_crypt($unor_encrypt), $arr_unor_kecamatan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_camat,
                );
            } else if (array_search(substr(decode_crypt($unor_encrypt), 0, 9), $arr_unor_kelurahan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_lurah,
                );
            } else {
                $where = array(
                    'genpos.KD_UNOR' => decode_crypt($unor_encrypt),
                );
            }

            $data['all_genpos'] = $this->genpos_model->all(
                array(
                    'where'    => $where,
                    'or_where' => array(
                        'genpos.KD_UNOR' => '0000000000',
                    ),
                )
            );

            $data['all_master_kelas_jabatan'] = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => decode_crypt($unor_encrypt),
                    ),
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
                )
            );

            $this->render('maping_pekerjaan/edit', $data);
        } else {
            $genpos                           = $this->input->post('kd_genpos', true);
            $no_master_jabfus                 = decode_crypt($this->input->post('jab_pelaksana', true));
            $kd_fpos                          = decode_crypt($this->input->post('jab_fungsional_tertentu', true));
            $id_master_kelas_jabatan_selected = $this->input->post('id_master_kelas_jabatan', true);

            if ($no_master_jabfus != null) {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    'no_master_jabfus'        => $no_master_jabfus,
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            } else if ($kd_fpos != null) {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    'KD_FPOS'                 => $kd_fpos,
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            } else {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    // 'no_master_jabfus'          => $this->input->post('jab_pelaksana', true),
                    // 'KD_FPOS'                   => $this->input->post('jab_fungsional_tertentu', true),
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            }

            //buat update id_master_kelas_jabatan di tabel pekerjaan
            if ($no_master_jabfus != null) {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $no_master_jabfus,
                        ),
                    )
                );
            } else if ($kd_fpos != null) {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $kd_fpos,
                        ),
                    )
                );
            } else {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $genpos,
                        ),
                    )
                );
            }

            $tmp = array();
            foreach ($data_all['all_pekerjaan'] as $key => $row) {
                // if($row->id_master_kelas_jabatan == 0){
                if ($row->id_master_kelas_jabatan != $id_master_kelas_jabatan_selected) {
                    $data_allpekerjaan = array(
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan_selected,
                    );
                    $action = $this->pekerjaan_model->edit($row->id, $data_allpekerjaan);
                }
            }
            $dataid = $tmp;

            $action = $this->pekerjaan_maping_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/maping_pekerjaan');
        }
    }

    public function edit($id_encrypt = null, $unor_encrypt = null)
    {
        if (is_null($id_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('unor', 'unor', 'required');
        $this->form_validation->set_rules('kd_genpos', 'kd_genpos', 'required');
        $this->form_validation->set_rules('id_master_kelas_jabatan', 'id_master_kelas_jabatan', 'required');

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

            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
            $data['all_sopd']  = json_decode($get_all_sopd);

            $data['selected_unor'] = !is_null($unor_encrypt) ? $check_unor_exists->KD_UNOR : null;

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

            if (array_search(decode_crypt($unor_encrypt), $arr_unor_kecamatan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_camat,
                );
            } else if (array_search(substr(decode_crypt($unor_encrypt), 0, 9), $arr_unor_kelurahan)) {
                $where = array(
                    'genpos.KD_UNOR' => $unor_rujukan_lurah,
                );
            } else {
                $where = array(
                    'genpos.KD_UNOR' => decode_crypt($unor_encrypt),
                );
            }

            $data['all_genpos'] = $this->genpos_model->all(
                array(
                    'where'    => $where,
                    'or_where' => array(
                        'genpos.KD_UNOR' => '0000000000',
                    ),
                )
            );

            // $data['all_genpos']  = $this->genpos_model->all(
            //     array(
            //         'where' => array(
            //             'genpos.KD_UNOR' => decode_crypt($unor_encrypt),
            //         ),
            //         'or_where' => array(
            //             'genpos.KD_UNOR' => '0000000000',
            //         ),
            //     )
            // );

            $data['all_master_kelas_jabatan'] = $this->master_kelas_jabatan_model->all(
                array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => decode_crypt($unor_encrypt),
                    ),
                    'order_by'  => 'master_kelas_jabatan.kelas_jabatan DESC',
                )
            );

            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/maping-pekerjaan'), 'title' => 'Maping Pekerjaan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Maping Pekerjaan', 'icon' => '', 'active' => '1'],
            ];

            $data['maping_pekerjaan'] = $this->pekerjaan_maping_model->first(
                array('id' => decode_crypt($id_encrypt))
            );

            $data['all_jabatan_fungsional'] = $this->master_jabfus_model->all(
                array(
                    'where' => array(
                        'unor' => decode_crypt($unor_encrypt),
                    ),
                )
            );

            $data['all_jabatan_fungsional_tertentu'] = $this->fpos_model->all(
                array(
                    'where' => array(
                        'unor' => decode_crypt($unor_encrypt),
                    ),
                )
            );

            $this->render('maping_pekerjaan/edit', $data);
        } else {
            $genpos                           = $this->input->post('kd_genpos', true);
            $no_master_jabfus                 = decode_crypt($this->input->post('jab_pelaksana', true));
            $kd_fpos                          = decode_crypt($this->input->post('jab_fungsional_tertentu', true));
            $id_master_kelas_jabatan_selected = $this->input->post('id_master_kelas_jabatan', true);

            if ($no_master_jabfus != null) {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    'no_master_jabfus'        => $no_master_jabfus,
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            } else if ($kd_fpos != null) {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    'KD_FPOS'                 => $kd_fpos,
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            } else {
                $data = array(
                    'unor'                    => decode_crypt($unor_encrypt),
                    'KD_GENPOS'               => $this->input->post('kd_genpos', true),
                    'id_master_kelas_jabatan' => $this->input->post('id_master_kelas_jabatan', true),
                );
            }

            //yang lama di nullkan dulu
            $data_id['id_pekerjaan'] = $this->pekerjaan_maping_model->all(
                array(
                    'where' => array(
                        'id' => decode_crypt($id_encrypt),
                    ),
                ), false
            );

            $id_master_kelas_jabatan_edit = $data_id['id_pekerjaan']->id_master_kelas_jabatan;

            $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                array(
                    'where' => array(
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan_edit,
                    ),
                )
            );

            $tmp = array();
            foreach ($data_all['all_pekerjaan'] as $key => $row) {
                $data_allpekerjaan = array(
                    'id_master_kelas_jabatan' => (null),
                );
                $action = $this->pekerjaan_model->edit($row->id, $data_allpekerjaan);
            }
            $dataid = $tmp;

            //buat update id_master_kelas_jabatan di tabel pekerjaan
            if ($no_master_jabfus != null) {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $no_master_jabfus,
                        ),
                    )
                );
            } else if ($kd_fpos != null) {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $kd_fpos,
                        ),
                    )
                );
            } else {
                $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                    array(
                        'where' => array(
                            'PNS_UNOR'   => decode_crypt($unor_encrypt),
                            'id_jabatan' => $genpos,
                        ),
                    )
                );
            }

            // var_dump($data_all['all_pekerjaan']);die;

            $tmp = array();
            foreach ($data_all['all_pekerjaan'] as $key => $row) {
                if ($row->id_master_kelas_jabatan != $id_master_kelas_jabatan_selected) {
                    $data_allpekerjaan = array(
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan_selected,
                    );
                    $action = $this->pekerjaan_model->edit($row->id, $data_allpekerjaan);
                }
            }
            $dataid = $tmp;

            $action = $this->pekerjaan_maping_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/maping_pekerjaan');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {

            //yang lama dinullkan dulu
            $data_id['id_pekerjaan'] = $this->pekerjaan_maping_model->all(
                array(
                    'where' => array(
                        'id' => decode_crypt($id_encrypt),
                    ),
                ), false
            );

            $id_master_kelas_jabatan_delete = $data_id['id_pekerjaan']->id_master_kelas_jabatan;

            $data_all['all_pekerjaan'] = $this->pekerjaan_model->all(
                array(
                    'where' => array(
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan_delete,
                    ),
                )
            );

            $tmp = array();
            foreach ($data_all['all_pekerjaan'] as $key => $row) {
                $data_allpekerjaan = array(
                    'id_master_kelas_jabatan' => (null),
                );
                $action = $this->pekerjaan_model->edit($row->id, $data_allpekerjaan);
            }
            $dataid = $tmp;

            //delete data
            $action = $this->pekerjaan_maping_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/maping_pekerjaan');
        }
    }

}
