<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_pekerjaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('pekerjaan_model');
        $this->load->model('pekerjaan_maping_model');
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('unor_model');
        $this->page_title = 'Tupoksi';
    }

    public function get_data()
    {
        $selected_sopd    = $this->input->get('selected_sopd', true);
        $selected_jabatan = $this->input->get('selected_jabatan', true);
        $dbkinerja        = get_config_item('dbkinerja');

        if ($selected_sopd && $selected_jabatan) {

            set_session([
                'selected_sopd_tupoksi'            => decode_crypt($selected_sopd),
                'selected_jabatan_tupoksi'         => decode_crypt($selected_jabatan),
                'selected_jabatan_tupoksi_encrypt' => $selected_jabatan,
            ]);

            $check_maping = $this->pekerjaan_maping_model->all(
                array(
                    'where' => array(
                        "{$dbkinerja}.pekerjaan_maping.id_master_kelas_jabatan" => decode_crypt($selected_jabatan),
                    ),
                )
            );

            if ($check_maping) {
                $tmp = array();
                $i = 0;
                foreach ($check_maping as $rowCM) {
                    $genpos                  = $rowCM->KD_GENPOS;
                    $no_jabfus               = $rowCM->no_master_jabfus;
                    $kd_fpos                 = $rowCM->KD_FPOS;
                    $id_master_kelas_jabatan = $rowCM->id_master_kelas_jabatan; //belum dipakai kalo baru ga usah pakai maping

                    if ($genpos == '9999') {
                        // if(decode_crypt($selected_sopd) == '8818000000' && ($no_jabfus == '1' || $no_jabfus == '155'))
                        //untuk dinkes pengadministrasian umum dua orang tupoksi beda
                        if ($id_master_kelas_jabatan == '209' || $id_master_kelas_jabatan == '1188') {
                            $data = $this->pekerjaan_model->all(
                                array(
                                    'where'    => array(
                                        "{$dbkinerja}.pekerjaan.PNS_UNOR"                => decode_crypt($selected_sopd),
                                        "{$dbkinerja}.pekerjaan.id_master_kelas_jabatan" => $id_master_kelas_jabatan,
                                    ),
                                    'order_by' => 'pekerjaan.prioritas ASC',
                                )
                            );
                        } else {
                            $data = $this->pekerjaan_model->all(
                                array(
                                    'where'    => array(
                                        "{$dbkinerja}.pekerjaan.PNS_UNOR"   => decode_crypt($selected_sopd),
                                        "{$dbkinerja}.pekerjaan.id_jabatan" => $no_jabfus,
                                    ),
                                    'order_by' => 'pekerjaan.prioritas ASC',
                                )
                            );
                        }
                    } else if ($genpos == 'FT') {
                        $data = $this->pekerjaan_model->all(
                            array(
                                'where'    => array(
                                    "{$dbkinerja}.pekerjaan.PNS_UNOR"   => decode_crypt($selected_sopd),
                                    "{$dbkinerja}.pekerjaan.id_jabatan" => $kd_fpos,
                                ),
                                'order_by' => 'pekerjaan.prioritas ASC',
                            )
                        );
                    } else { //untuk jabatan JS
                        $data = $this->pekerjaan_model->all(
                            array(
                                'where'    => array(
                                    "{$dbkinerja}.pekerjaan.PNS_UNOR"   => decode_crypt($selected_sopd),
                                    "{$dbkinerja}.pekerjaan.id_jabatan" => $genpos,
                                ),
                                'order_by' => 'pekerjaan.prioritas ASC',
                            )
                        );
                    }

                    foreach ($data as $key => $row) {
                        foreach ($row as $childkey => $childrow) {
                            $tmp[$i][$childkey] = $childrow;
                        }
                        $tmp[$i]['id_encrypt']                      = encode_crypt($row->id);
                        $tmp[$i]['unor_encrypt']                    = $selected_sopd;
                        $tmp[$i]['id_jabatan_encrypt']              = encode_crypt($genpos);
                        $tmp[$i]['id_master_kelas_jabatan_encrypt'] = encode_crypt($id_master_kelas_jabatan);
                        $i++;
                    }
                }
                $data = $tmp;
            } else { //jabatan baru sesuai master_kelas_jabatan baru
                $data = $this->pekerjaan_model->all(
                    array(
                        'where'    => array(
                            "{$dbkinerja}.pekerjaan.PNS_UNOR"                => decode_crypt($selected_sopd),
                            "{$dbkinerja}.pekerjaan.id_master_kelas_jabatan" => decode_crypt($selected_jabatan),
                        ),
                        'order_by' => 'pekerjaan.prioritas ASC',
                    )
                );

                $tmp = array();
                foreach ($data as $key => $row) {
                    foreach ($row as $childkey => $childrow) {
                        $tmp[$key][$childkey] = $childrow;
                    }
                    $tmp[$key]['id_encrypt']                      = encode_crypt($row->id);
                    $tmp[$key]['unor_encrypt']                    = $selected_sopd;
                    $tmp[$key]['id_jabatan_encrypt']              = null;
                    $tmp[$key]['id_master_kelas_jabatan_encrypt'] = $selected_jabatan;
                }
                $data = $tmp;
            }

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
            ['link' => '', 'title' => 'Tupoksi', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'setup';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data['all_sopd'] = json_decode($get_all_sopd);

        $this->render('master_pekerjaan/list', $data);
    }

    public function add($unor_encrypt = null, $selected_jabatan = null)
    {
        if (is_null($unor_encrypt) && is_null($selected_jabatan)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('nama_pekerjaan', 'nama pekerjaan', 'required');
        $this->form_validation->set_rules('prioritas', 'prioritas', 'required|numeric');

        $dbkinerja      = get_config_item('dbkinerja');
        $data['maping'] = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.pekerjaan_maping.id_master_kelas_jabatan" => decode_crypt($selected_jabatan),
                ),
            ), false
        );

        if ($data['maping'] != null) {
            $genpos                  = $data['maping']->KD_GENPOS;
            $no_jabfus               = $data['maping']->no_master_jabfus;
            $kd_fpos                 = $data['maping']->KD_FPOS;
            $id_master_kelas_jabatan = $data['maping']->id_master_kelas_jabatan;
        }

        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => decode_crypt($unor_encrypt),
            )
        );
        $check_kelas_jabatan_exists = $this->master_kelas_jabatan_model->first(
            array(
                'id' => decode_crypt($selected_jabatan),
            )
        );

        if (!$check_unor_exists && !$check_kelas_jabatan_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-pekerjaan'), 'title' => 'Tupoksi', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Tupoksi', 'icon' => '', 'active' => '1'],
            ];
            $data['selected_unor']    = $check_unor_exists->NM_UNOR;
            $data['selected_jabatan'] = $check_kelas_jabatan_exists ? $check_kelas_jabatan_exists->nama_jabatan : null;

            $this->render('master_pekerjaan/edit', $data);
        } else {
            if ($data['maping'] != null) {
                if ($genpos == '9999') {
                    $data = array(
                        'PNS_UNOR'                => decode_crypt($unor_encrypt),
                        'id_jabatan'              => $no_jabfus,
                        'nama_pekerjaan'          => $this->input->post('nama_pekerjaan', true),
                        'prioritas'               => $this->input->post('prioritas', true),
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                    );
                } else if ($genpos == 'FT') {
                    $data = array(
                        'PNS_UNOR'                => decode_crypt($unor_encrypt),
                        'id_jabatan'              => $kd_fpos,
                        'nama_pekerjaan'          => $this->input->post('nama_pekerjaan', true),
                        'prioritas'               => $this->input->post('prioritas', true),
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                    );
                } else {
                    $data = array(
                        'PNS_UNOR'                => decode_crypt($unor_encrypt),
                        'id_jabatan'              => $genpos,
                        'nama_pekerjaan'          => $this->input->post('nama_pekerjaan', true),
                        'prioritas'               => $this->input->post('prioritas', true),
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                    );
                }
            } else { //jabatan baru sesuai master_kelas_jabatan baru
                $data = array(
                    'PNS_UNOR'                => decode_crypt($unor_encrypt),
                    // 'id_jabatan'                => $no_jabfus,
                    'nama_pekerjaan'          => $this->input->post('nama_pekerjaan', true),
                    'prioritas'               => $this->input->post('prioritas', true),
                    'id_master_kelas_jabatan' => decode_crypt($selected_jabatan),
                );
            }

            $action = $this->pekerjaan_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_pekerjaan');
        }
    }

    public function edit($id_encrypt = null, $unor_encrypt = null, $id_jabatan_encrypt = null)
    {
        if (is_null($id_encrypt) && is_null($unor_encrypt) && is_null($id_jabatan_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kelas_jabatan', 'kelas jabatan', 'required');
        $this->form_validation->set_rules('nama_pekerjaan', 'nama pekerjaan', 'required');
        $this->form_validation->set_rules('prioritas', 'prioritas', 'required|numeric');

        $dbkinerja = get_config_item('dbkinerja');

        $data['pekerjaan'] = $this->pekerjaan_model->first(
            array('id' => decode_crypt($id_encrypt))
        );

        $id_master_kls_jbt = $data['pekerjaan']->id_master_kelas_jabatan;

        $data['maping'] = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    "{$dbkinerja}.pekerjaan_maping.KD_GENPOS"               => decode_crypt($id_jabatan_encrypt),
                    "{$dbkinerja}.pekerjaan_maping.unor"                    => decode_crypt($unor_encrypt),
                    "{$dbkinerja}.pekerjaan_maping.id_master_kelas_jabatan" => $id_master_kls_jbt,
                ),
            ), false
        );

        if ($data['maping'] != null) {
            $genpos                  = $data['maping']->KD_GENPOS;
            $no_jabfus               = $data['maping']->no_master_jabfus;
            $kd_fpos                 = $data['maping']->KD_FPOS;
            $id_master_kelas_jabatan = $data['maping']->id_master_kelas_jabatan;
        }

        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => decode_crypt($unor_encrypt),
            )
        );
        if ($data['maping'] != null) {
            $check_kelas_jabatan_exists = $this->master_kelas_jabatan_model->first(
                array(
                    'id' => $data['maping']->id_master_kelas_jabatan,
                )
            );
        } else {
            $check_kelas_jabatan_exists = $this->master_kelas_jabatan_model->first(
                array(
                    'id' => $data['pekerjaan']->id_master_kelas_jabatan,
                )
            );
        }
        if (!$check_unor_exists && !$check_kelas_jabatan_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {

            $data['pekerjaan'] = $this->pekerjaan_model->first(
                array('id' => decode_crypt($id_encrypt))
            );
            if (!$data['pekerjaan']) {
                show_404();
            }

            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-pekerjaan'), 'title' => 'Tupoksi', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Tupoksi', 'icon' => '', 'active' => '1'],
            ];
            $data['selected_unor']    = $check_unor_exists->NM_UNOR;
            $data['selected_jabatan'] = $check_kelas_jabatan_exists->nama_jabatan;
            $this->render('master_pekerjaan/edit', $data);
        } else {
            if ($data['maping'] != null) {
                if ($genpos == '9999') {
                    $data = array(
                        'PNS_UNOR'       => decode_crypt($unor_encrypt),
                        'id_jabatan'     => $no_jabfus,
                        'nama_pekerjaan' => $this->input->post('nama_pekerjaan', true),
                        'prioritas'      => $this->input->post('prioritas', true),
                    );
                } else if ($genpos == 'FT') {
                    $data = array(
                        'PNS_UNOR'       => decode_crypt($unor_encrypt),
                        'id_jabatan'     => $kd_fpos,
                        'nama_pekerjaan' => $this->input->post('nama_pekerjaan', true),
                        'prioritas'      => $this->input->post('prioritas', true),
                    );
                } else {
                    $data = array(
                        'PNS_UNOR'       => decode_crypt($unor_encrypt),
                        'id_jabatan'     => $genpos,
                        'nama_pekerjaan' => $this->input->post('nama_pekerjaan', true),
                        'prioritas'      => $this->input->post('prioritas', true),
                        // 'updated_at'                => $this->now,
                    );
                }
            } else {
                $data = array(
                    'PNS_UNOR'                => decode_crypt($unor_encrypt),
                    // 'id_master_kelas_jabatan'   => $id_master_kelas_jabatan,
                    'id_master_kelas_jabatan' => $data['pekerjaan']->id_master_kelas_jabatan,
                    'nama_pekerjaan'          => $this->input->post('nama_pekerjaan', true),
                    'prioritas'               => $this->input->post('prioritas', true),
                );
            }

            $action = $this->pekerjaan_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_pekerjaan');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        if ($id_encrypt) {
            $action = $this->pekerjaan_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_pekerjaan');
        }
    }

}
