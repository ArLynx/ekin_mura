<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_group_jam_kerja extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_group_jam_kerja_model');
        $this->load->model('master_group_jam_kerja_shift_model');
        $this->load->model('master_jam_kerja_model');
    }

    public function get_data()
    {
        $data = $this->master_group_jam_kerja_model->all(
            array(
                'fields'    => "master_group_jam_kerja.*, IFNULL(unor.NM_UNOR, 'Umum') AS NM_UNOR",
                'left_join' => array(
                    'unor' => 'unor.KD_UNOR = master_group_jam_kerja.unor',
                ),
                'order_by'  => 'order ASC',
            )
        );
        $tmp = array();
        foreach ($data as $key => $row) {
            foreach ($row as $childkey => $childrow) {
                $tmp[$key][$childkey] = $childrow;
            }
            if (!empty($row->shift1)) {
                $get_jam_shift1          = $this->master_jam_kerja_model->first($row->shift1);
                $tmp[$key]['jam_shift1'] = $get_jam_shift1 ? "Masuk: {$get_jam_shift1->jam_masuk} | Pulang: {$get_jam_shift1->jam_pulang}" : '-';
            } else {
                $tmp[$key]['jam_shift1'] = '';
            }
            if (!empty($row->shift2)) {
                $get_jam_shift2          = $this->master_jam_kerja_model->first($row->shift2);
                $tmp[$key]['jam_shift2'] = $get_jam_shift2 ? "Masuk: {$get_jam_shift2->jam_masuk} | Pulang: {$get_jam_shift2->jam_pulang}" : '-';
            } else {
                $tmp[$key]['jam_shift2'] = '';
            }
            if (!empty($row->shift3)) {
                $get_jam_shift3          = $this->master_jam_kerja_model->first($row->shift3);
                $tmp[$key]['jam_shift3'] = $get_jam_shift3 ? "Masuk: {$get_jam_shift3->jam_masuk} | Pulang: {$get_jam_shift3->jam_pulang}" : '-';
            } else {
                $tmp[$key]['jam_shift3'] = '';
            }
        }
        $data = $tmp;
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Master Group Jam Kerja';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'master group jam kerja', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';
        $this->render('master_group_jam_kerja/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $unor       = $this->input->post('unor', true);
            $group_name = $this->input->post('group_name', true);
            $order      = $this->input->post('order', true);
            $shift1     = $this->input->post('shift1', true);
            $shift2     = $this->input->post('shift2', true);
            $shift3     = $this->input->post('shift3', true);
            if (!empty($group_name) && !empty($shift1)) {
                $save_data = array(
                    'unor'       => $unor,
                    'group_name' => $group_name,
                    'order'      => $order,
                    'shift1'     => $shift1,
                    'shift2'     => $shift2,
                    'shift3'     => $shift3,
                );
                $id_master_group_jam_kerja = $this->master_group_jam_kerja_model->save($save_data);

                $get        = $this->master_group_jam_kerja_model->first($id_master_group_jam_kerja);
                $tmp_insert = array();
                if ($get->shift1 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift1,
                        'ket'                       => 'G1',
                    );
                }
                if ($get->shift2 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift2,
                        'ket'                       => 'G2',
                    );
                }
                if ($get->shift3 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift3,
                        'ket'                       => 'G3',
                    );
                }
                if (!empty($tmp_insert)) {
                    $this->master_group_jam_kerja_shift_model->save_batch($tmp_insert);
                }

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
            $id_master_group_jam_kerja = $this->input->post('id_master_group_jam_kerja', true);
            $unor                      = $this->input->post('unor', true);
            $group_name                = $this->input->post('group_name', true);
            $order                     = $this->input->post('order', true);
            $shift1                    = $this->input->post('shift1', true);
            $shift2                    = $this->input->post('shift2', true);
            $shift3                    = $this->input->post('shift3', true);

            if (!empty($id_master_group_jam_kerja) && !empty($group_name)) {
                $edit_data = array(
                    'unor'       => $unor,
                    'group_name' => $group_name,
                    'order'      => $order,
                    'shift1'     => $shift1,
                    'shift2'     => $shift2,
                    'shift3'     => $shift3,
                );
                $this->master_group_jam_kerja_model->edit($id_master_group_jam_kerja, $edit_data);

                $check_jam_shift_exists = $this->master_group_jam_kerja_shift_model->count(
                    array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                    )
                );

                if ($check_jam_shift_exists) {
                    $this->master_group_jam_kerja_shift_model->delete(
                        array(
                            'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        )
                    );
                }

                $get        = $this->master_group_jam_kerja_model->first($id_master_group_jam_kerja);
                $tmp_insert = array();
                if ($get->shift1 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift1,
                        'ket'                       => 'G1',
                    );
                }
                if ($get->shift2 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift2,
                        'ket'                       => 'G2',
                    );
                }
                if ($get->shift3 != '0') {
                    $tmp_insert[] = array(
                        'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        'id_master_jam_kerja'       => $get->shift3,
                        'ket'                       => 'G3',
                    );
                }
                if (!empty($tmp_insert)) {
                    $this->master_group_jam_kerja_shift_model->save_batch($tmp_insert);
                }

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

    public function delete($id_master_group_jam_kerja = null)
    {
        if (!is_null($id_master_group_jam_kerja)) {
            $check_exists = $this->master_group_jam_kerja_model->first($id_master_group_jam_kerja);
            if ($check_exists) {
                if ($check_exists->PNS_UNOR == get_session('unor') || get_session('id_groups') == 5) {
                    $check_jam_shift_exists = $this->master_group_jam_kerja_shift_model->count(
                        array(
                            'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                        )
                    );

                    if ($check_jam_shift_exists) {
                        $this->master_group_jam_kerja_shift_model->delete(
                            array(
                                'id_master_group_jam_kerja' => $id_master_group_jam_kerja,
                            )
                        );
                    }

                    $this->master_group_jam_kerja_model->delete($id_master_group_jam_kerja);

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
