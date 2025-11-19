<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rekap_indikator_kehadiran extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kinerja/rekap_indikator_kehadiran_model', 'rekap_indikator_kehadiran_model');
        $this->load->model('unor_model');

        $this->dbkinerja = get_config_item('dbkinerja');

    }

    public function get_data()
    {
        $month = decode_crypt($this->input->get('selected_month', true));
        $year  = decode_crypt($this->input->get('selected_year', true));

        if ($month && $year) {
            $data = $this->unor_model->all(
                array(
                    'fields'       => 'unor.KD_UNOR, unor.NM_UNOR, rekap_indikator_kehadiran.month, rekap_indikator_kehadiran.year, rekap_indikator_kehadiran.created_at AS tanggal_rekap',
                    'join'         => array(
                        "{$this->dbkinerja}.rekap_indikator_kehadiran" => "rekap_indikator_kehadiran.unor = unor.KD_UNOR AND rekap_indikator_kehadiran.month = {$month} AND rekap_indikator_kehadiran.year = {$year}",
                    ),
                    'not_like'     => array(
                        'unor.NM_UNOR' => 'puskesmas',
                    ),
                    'and_not_like' => array(
                        'unor.NM_UNOR' => 'rsud',
                    ),
                    'order_by'     => 'unor.NM_UNOR ASC',
                    'group_by'     => 'rekap_indikator_kehadiran.unor',
                )
            );

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['unor_encrypt'] = encode_crypt($row->KD_UNOR);
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
        $data['page_title'] = 'Rekap Indikator Kehadiran';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'Rekap Indikator Kehadiran', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'hapus rekap';

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $this->render('rekap_indikator_kehadiran/list', $data);
    }

    public function delete()
    {
        $unor  = decode_crypt($this->input->get('unor_encrypt', true));
        $month = decode_crypt($this->input->get('month_encrypt', true));
        $year  = decode_crypt($this->input->get('year_encrypt', true));
        if ($unor && $month && $year) {
            $check_unor_exists = $this->unor_model->first(
                array(
                    'KD_UNOR' => $unor,
                )
            );
            if ($check_unor_exists) {
                $this->rekap_indikator_kehadiran_model->delete(
                    array(
                        'unor'  => $unor,
                        'month' => $month,
                        'year'  => $year,
                    )
                );
                $message = 'Action Successfully..';
                $class   = 'alert-success';
            } else {
                $message = 'Something went wrong..';
                $class   = 'alert-danger';
            }

            $this->session->set_flashdata('message', array('message' => $message, 'class' => $class));
            redirect('dashboard/rekap-indikator-kehadiran');
        }
    }
}
