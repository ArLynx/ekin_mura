<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function cek()
    {
        die;
        $month = 6;
        $year  = 2020;
        if (strtotime(to_date_format('2020-04-01', 'm-Y')) >= strtotime(to_date_format("{$month}-{$year}-01", 'm-Y')) && strtotime(to_date_format('2020-05-31', 'm-Y')) <= strtotime(to_date_format("{$month}-{$year}-01", 'm-Y'))) {
            $nominal_sanksi = 'OK';
        } else {
            $nominal_sanksi = '-';
        }
        echo $nominal_sanksi;
    }

    public function wew()
    {
        die;
        $a       = 13316870;
        $hitung1 = $a * 1 / 100;
        $hitung2 = floor($a * 1 / 100);
        echo "hitung-1: " . $hitung1;
        echo '<br>';
        echo "hitung-2: " . $hitung2;
    }

    public function aaa()
    {
        $this->load->model('kinerja/gaji_pegawai_model', 'gaji_pegawai_model');
        $nip              = '196908142006041000';
        $get_gaji_pegawai = $this->gaji_pegawai_model->first(
            array(
                'nip' => $nip,
            )
        );
        echo $this->db->last_query();
    }

    public function under_maintenance() {
        $this->load->view('under_maintenance');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url());
    }

}
