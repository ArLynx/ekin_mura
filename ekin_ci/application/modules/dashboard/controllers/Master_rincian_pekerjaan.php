<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Master_rincian_pekerjaan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('rincian_pekerjaan_model');
        $this->load->model('pekerjaan_model');
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('unor_model');
        $this->load->model('kinerja/satuan_model');
        $this->load->model('pekerjaan_maping_model');
        $this->page_title = 'Master Rincian Pekerjaan';
        $this->auth       = false;
    }

    public function get_data()
    {
        $selected_sopd      = $this->input->get('selected_sopd', true);
        $selected_jabatan   = $this->input->get('selected_jabatan', true);
        $selected_pekerjaan = $this->input->get('selected_pekerjaan', true);
        $dbkinerja          = get_config_item('dbkinerja');

        if ($selected_sopd && $selected_jabatan && $selected_pekerjaan) {
            $data = $this->rincian_pekerjaan_model->all(
                array(
                    'fields'    => 'rincian_pekerjaan.*, satuan.nama as nm_satuan',
                    'left_join' => array(
                        "{$dbkinerja}.satuan" => 'satuan.id = rincian_pekerjaan.id_satuan',
                    ),
                    'where'     => array(
                        'rincian_pekerjaan.id_pekerjaan' => decode_crypt($selected_pekerjaan),
                    ),
                    'order_by'  => 'rincian_pekerjaan.id ASC',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_encrypt']                  = encode_crypt($row->id);
                $tmp[$key]['id_master_pekerjaan_encrypt'] = $selected_pekerjaan;
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
        $id_encrypt                      = $this->input->get('id_encrypt', true);
        $id_master_kelas_jabatan_encrypt = $this->input->get('id_master_kelas_jabatan_encrypt', true);
        // $dbkinerja = get_config_item('dbkinerja');

        if (is_null($id_encrypt) && is_null($id_master_kelas_jabatan_encrypt)) {
            show_404();
        }

        $data['pekerjaan'] = $this->pekerjaan_model->first(
            array('id' => decode_crypt($id_encrypt))
        );
        if (!$data['pekerjaan']) {
            show_404();
        }

        $data['master_kelas_jabatan_selected'] = $this->master_kelas_jabatan_model->first(
            array('id' => decode_crypt($id_master_kelas_jabatan_encrypt))
        );
        if (!$data['master_kelas_jabatan_selected']) {
            show_404();
        }

        $data['master_kelas_jabatan'] = $this->master_kelas_jabatan_model->all(
            array(
                'where'    => array(
                    'unor' => $data['pekerjaan']->PNS_UNOR,
                ),
                'order_by' => 'kelas_jabatan DESC',
            )
        );

        //genpos buat yg js
        // $get_maping = $this->pekerjaan_maping_model->all(
        //     array(
        //         'where'     => array(
        //             'pekerjaan_maping.KD_GENPOS' => decode_crypt($id_master_kelas_jabatan_encrypt),
        //         ),
        //     ), false
        // );

        $get_maping = $this->pekerjaan_maping_model->all(
            array(
                'where' => array(
                    'pekerjaan_maping.id_master_kelas_jabatan' => decode_crypt($id_master_kelas_jabatan_encrypt),
                ),
            )
        );

        // if($genpos != null){
        if ($get_maping != null) {
            $data['id_master_kelas_jabatan'] = $get_maping[0]->id_master_kelas_jabatan;
            $tmp = array();
            $i   = 0;
            foreach ($get_maping as $rowCM) {
                $genpos                  = $rowCM->KD_GENPOS;
                $no_jabfus               = $rowCM->no_master_jabfus;
                $kd_fpos                 = $rowCM->KD_FPOS;
                $id_master_kelas_jabatan = $rowCM->id_master_kelas_jabatan;

                if ($genpos == '9999') {
                    $get_master_pekerjaan_list = $this->pekerjaan_model->all(
                        array(
                            'where'    => array(
                                'PNS_UNOR'   => $data['pekerjaan']->PNS_UNOR,
                                'id_jabatan' => $no_jabfus,
                            ),
                            'order_by' => 'prioritas ASC',
                        )
                    );
                } else if ($genpos == 'FT') {
                    $get_master_pekerjaan_list = $this->pekerjaan_model->all(
                        array(
                            'where'    => array(
                                'PNS_UNOR'   => $data['pekerjaan']->PNS_UNOR,
                                'id_jabatan' => $kd_fpos,
                            ),
                            'order_by' => 'prioritas ASC',
                        )
                    );
                } else {
                    $get_master_pekerjaan_list = $this->pekerjaan_model->all(
                        array(
                            'where'    => array(
                                'PNS_UNOR'   => $data['pekerjaan']->PNS_UNOR,
                                'id_jabatan' => $genpos,
                            ),
                            'order_by' => 'prioritas ASC',
                        )
                    );
                }

                foreach ($get_master_pekerjaan_list as $key => $row) {
                    foreach ($row as $childkey => $childrow) {
                        $tmp[$i][$childkey] = $childrow;
                    }
                    $i++;
                }
            }
            $data['master_pekerjaan_list'] = json_decode(json_encode($tmp), FALSE);
        } else { //buat jabatan baru sesuai master_kelas_jabatan baru
            $data['master_pekerjaan_list'] = $this->pekerjaan_model->all(
                array(
                    'where'    => array(
                        'PNS_UNOR'                => $data['pekerjaan']->PNS_UNOR,
                        'id_master_kelas_jabatan' => decode_crypt($id_master_kelas_jabatan_encrypt),
                    ),
                    'order_by' => 'prioritas ASC',
                )
            );
        }

        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => site_url('dashboard/master-pekerjaan'), 'title' => 'Master Pekerjaan', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Rincian Pekerjaan', 'icon' => '', 'active' => '1'],
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

        $data['satuan'] = $this->satuan_model->all(
            array(
                "order_by" => "nama ASC",
            )
        );

        $this->render('master_rincian_pekerjaan/list', $data);

    }

    public function add($unor_encrypt = null, $selected_jabatan = null, $selected_pekerjaan = null)
    {
        if (is_null($unor_encrypt) && is_null($selected_jabatan) && is_null($selected_pekerjaan)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_rincian_pekerjaan', 'nama rincian pekerjaan', 'required');
        $this->form_validation->set_rules('norma_waktu', 'norma waktu', 'required');
        $this->form_validation->set_rules('id_satuan', 'satuan', 'required');

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
        $check_pekerjaan_exists = $this->master_pekerjaan_model->first(
            array(
                'id' => decode_crypt($selected_pekerjaan),
            )
        );
        if (!$check_unor_exists && !$check_kelas_jabatan_exists && !$check_pekerjaan_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-rincian-pekerjaan'), 'title' => 'Master Rincian Pekerjaan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Master Rincian Pekerjaan', 'icon' => '', 'active' => '1'],
            ];
            $data['selected_unor']    = $check_unor_exists->NM_UNOR;
            $data['selected_jabatan'] = $this->master_pekerjaan_model->all(
                array(
                    'fields'    => 'master_pekerjaan.*, master_kelas_jabatan.nama_jabatan as nm_jabatan',
                    'left_join' => array(
                        'master_kelas_jabatan' => 'master_kelas_jabatan.id = master_pekerjaan.id_master_kelas_jabatan',
                    ),
                    'where'     => array(
                        'master_pekerjaan.id' => decode_crypt($selected_pekerjaan),
                    ),
                    'order_by'  => 'master_pekerjaan.id ASC',
                ), false
            );
            $data['selected_pekerjaan'] = $check_pekerjaan_exists->nama_pekerjaan;
            $data['satuan']             = $this->satuan_model->all(
                array(
                    "order_by" => "nama ASC",
                )
            );
            $this->render('master_rincian_pekerjaan/edit', $data);
        } else {
            $data = array(
                'id_master_pekerjaan'    => decode_crypt($selected_pekerjaan),
                'nama_rincian_pekerjaan' => $this->input->post('nama_rincian_pekerjaan', true),
                'norma_waktu'            => $this->input->post('norma_waktu', true),
                'id_satuan'              => $this->input->post('id_satuan', true),
                'created_at'             => $this->now,
            );
            $action = $this->master_rincian_pekerjaan_model->save($data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_rincian_pekerjaan');
        }
    }

    public function edit($id_encrypt = null, $id_master_pekerjaan_encrypt = null)
    {
        if (is_null($id_encrypt) && is_null($unor_encrypt) && is_null($id_master_pekerjaan_encrypt)) {
            show_404();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_rincian_pekerjaan', 'nama rincian pekerjaan', 'required');
        $this->form_validation->set_rules('norma_waktu', 'norma waktu', 'required');
        $this->form_validation->set_rules('id_satuan', 'satuan', 'required');

        $check_pekerjaan_exists = $this->master_pekerjaan_model->first(
            array(
                'id' => decode_crypt($id_master_pekerjaan_encrypt),
            )
        );
        $check_unor_exists = $this->unor_model->first(
            array(
                'KD_UNOR' => $check_pekerjaan_exists->unor,
            )
        );
        if (!$check_unor_exists && !$check_pekerjaan_exists) {
            show_404();
        }

        if ($this->form_validation->run() == false) {
            $data['master_rincian_pekerjaan'] = $this->master_rincian_pekerjaan_model->first(
                array('id' => decode_crypt($id_encrypt))
            );
            if (!$data['master_rincian_pekerjaan']) {
                show_404();
            }
            $data['page_title'] = 'Ubah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/master-rincian-pekerjaan'), 'title' => 'Master Rincian Pekerjaan', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Ubah Master Rincian Pekerjaan', 'icon' => '', 'active' => '1'],
            ];
            $data['selected_unor']      = $check_unor_exists->NM_UNOR;
            $data['selected_pekerjaan'] = $check_pekerjaan_exists->nama_pekerjaan;
            $data['selected_jabatan']   = $this->master_pekerjaan_model->all(
                array(
                    'fields'    => 'master_pekerjaan.*, master_kelas_jabatan.nama_jabatan as nm_jabatan',
                    'left_join' => array(
                        'master_kelas_jabatan' => 'master_kelas_jabatan.id = master_pekerjaan.id_master_kelas_jabatan',
                    ),
                    'where'     => array(
                        'master_pekerjaan.id' => decode_crypt($id_master_pekerjaan_encrypt),
                    ),
                    'order_by'  => 'master_pekerjaan.id ASC',
                ), false
            );
            $data['satuan'] = $this->satuan_model->all(
                array(
                    "order_by" => "nama ASC",
                )
            );

            $this->render('master_rincian_pekerjaan/edit', $data);
        } else {
            $data = array(
                'id_master_pekerjaan'    => decode_crypt($id_master_pekerjaan_encrypt),
                'nama_rincian_pekerjaan' => $this->input->post('nama_rincian_pekerjaan', true),
                'norma_waktu'            => $this->input->post('norma_waktu', true),
                'id_satuan'              => $this->input->post('id_satuan', true),
                'updated_at'             => $this->now,
            );
            $action = $this->master_rincian_pekerjaan_model->edit(decode_crypt($id_encrypt), $data);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/master_rincian_pekerjaan');
        }
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);

        if ($id_encrypt) {

            $action = $this->rincian_pekerjaan_model->delete(decode_crypt($id_encrypt));

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            // redirect('dashboard/master_rincian_pekerjaan');
        }
    }

}
