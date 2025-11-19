<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_jam_kerja extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_jam_kerja_model');
    }

    public function get_data()
    {
        $data = $this->master_jam_kerja_model->all(
            array(
                'fields'    => 'master_jam_kerja.*, master_group_jam_kerja.group_name, master_group_jam_kerja_shift.ket',
                'left_join' => array(
                    'master_group_jam_kerja_shift' => 'master_group_jam_kerja_shift.id_master_jam_kerja = master_jam_kerja.id',
                    'master_group_jam_kerja'       => 'master_group_jam_kerja.id = master_group_jam_kerja_shift.id_master_group_jam_kerja',
                ),
            )
        );
        $tmp = array();
        foreach ($data as $key => $row) {
            $tmp[$row->id]['id']         = $row->id;
            $tmp[$row->id]['jam_masuk']  = $row->jam_masuk;
            $tmp[$row->id]['jam_pulang'] = $row->jam_pulang;
            if (!is_null($row->group_name)) {
                $tmp[$row->id]['group_name'][$key]['group'] = $row->group_name;
                $tmp[$row->id]['group_name'][$key]['ket']   = $row->ket;
            }
        }
        $data = array_values($tmp);
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = 'Master Jam Kerja';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'master jam kerja', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';
        $this->render('master_jam_kerja/index', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $jam_masuk  = $this->input->post('jam_masuk', true);
            $jam_pulang = $this->input->post('jam_pulang', true);
            if (!empty($jam_masuk) && !empty($jam_pulang)) {
                $save_data = array(
                    'jam_masuk'  => $jam_masuk,
                    'jam_pulang' => $jam_pulang,
                );
                $this->master_jam_kerja_model->save($save_data);

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
            $id_master_jam_kerja = $this->input->post('id_master_jam_kerja', true);
            $jam_masuk           = $this->input->post('jam_masuk', true);
            $jam_pulang          = $this->input->post('jam_pulang', true);
            if (!empty($id_master_jam_kerja) && !empty($jam_masuk) && !empty($jam_pulang)) {
                $save_data = array(
                    'jam_masuk'  => $jam_masuk,
                    'jam_pulang' => $jam_pulang,
                );
                $this->master_jam_kerja_model->edit($id_master_jam_kerja, $save_data);

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
}
