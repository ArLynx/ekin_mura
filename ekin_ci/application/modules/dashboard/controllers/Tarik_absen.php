<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tarik_absen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->check_validation();
        $this->load->model('unor_model');
        $this->load->model('pns_model');
        $this->load->model('absen_enroll_model');
        $this->load->model('master_device_model');
        $this->page_title = 'Tarik Absen';
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd_tarik" : base_url('api/get_all_sopd_tarik');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd_tarik?unor=" . get_session('unor') : base_url('api/get_all_sopd_tarik?unor=' . get_session('unor'));
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }
        $data = json_decode($get_all_sopd);
        // $data = $data1->data;

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'Absen', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Tarik Absen', 'icon' => '', 'active' => '1'],
        ];

        $this->render('tarik_absen/list', $data);
    }

    public function add()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('sn', 'serial number', 'required|is_unique[master_device.sn]');
        $this->form_validation->set_rules('kd_unor', 'kode unit organisasi', 'required|is_unique[unor.KD_UNOR]');
        $this->form_validation->set_rules('nm_unor', 'nama unit organisasi', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = 'Tambah ' . $this->page_title;
            $data['breadcrumb'] = [
                ['link' => site_url('dashboard/tarik-absen'), 'title' => 'Tarik Absen', 'icon' => '', 'active' => '0'],
                ['link' => '', 'title' => 'Tambah Tarik Absen', 'icon' => '', 'active' => '1'],
            ];

            $this->render('tarik_absen/edit', $data);
        } else {

            $data = array(
                'sn'      => $this->input->post('sn', true),
                'kd_unor' => $this->input->post('kd_unor', true),
            );
            $action = $this->master_device_model->save($data);

            $data_unor = array(
                'kd_unor' => $this->input->post('kd_unor', true),
                'nm_unor' => $this->input->post('nm_unor', true),
                'NIP'     => $this->input->post('kd_unor', true),
            );
            $action = $this->unor_model->save($data_unor);

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/tarik_absen');
        }
    }
//MODUL TARIK ABSEN 02
    // public function upload_absen($sn = null, $id = null)
    public function upload_absen()
    {
        $data_SN = $this->master_device_model->all(
            array(
                //     'where'  => array(
                //         'master_device.sn' => $sn,
                //     ),
                'order_by' => 'master_device.kd_unor ASC',
            )
            // ), false
        );

        $jml_device = count($data_SN);

        for ($j = 0; $j < $jml_device; $j++) {

            $sn        = $data_SN[$j]->sn;
            $id        = $data_SN[$j]->id;
            $last_pull = $data_SN[$j]->updated_at;
            $tgl       = date('Y-m-d', strtotime($last_pull));
            $waktu     = date('H:i:s', strtotime($last_pull));

            $link_get_data_absen = "http://103.123.24.235/fingerspot/api/absen/devicescan?sn=$sn&tanggal=$tgl&waktu=$waktu";
            // $link_get_data_absen = "http://36.66.239.107/fingerspot/api/absen/devicescan?sn=$sn&tanggal=$tgl&waktu=$waktu";
            $get_all_data_absen = file_get_contents($link_get_data_absen);
            $data               = json_decode($get_all_data_absen);
            $data1              = $data->data;

            if ($data1) {
                $jml_data    = count($data1);
                $last_update = $this->now;
                for ($i = 0; $i < $jml_data; $i++) {
                    $data_PNS = $this->pns_model->first(
                        array(
                            "pns.id" => $data1[$i]->pin,
                        )
                    );

                    if ($data_PNS) {
                        $data_absen_enroll = $this->absen_enroll_model->first(
                            array(
                                'code'       => $data1[$i]->pin,
                                'PNS_PNSNIP' => $data_PNS->PNS_PNSNIP,
                                'time'       => $data1[$i]->scan_date,
                                'ip'         => $data1[$i]->sn,
                            )
                        );

                        if (!$data_absen_enroll) {
                            $data_save = array(
                                'code'         => $data1[$i]->pin,
                                'PNS_PNSNIP'   => $data_PNS->PNS_PNSNIP,
                                'PNS_PNSNAM'   => $data_PNS->PNS_PNSNAM,
                                'PNS_GLRDPN'   => $data_PNS->PNS_GLRDPN,
                                'PNS_GLRBLK'   => $data_PNS->PNS_GLRBLK,
                                'PNS_UNOR'     => $data_PNS->PNS_UNOR,
                                'ip'           => $data1[$i]->sn,
                                'time'         => $data1[$i]->scan_date,
                                'tanggal'      => date('Y-m-d', strtotime($data1[$i]->scan_date)),
                                'waktu'        => date('H:i:s', strtotime($data1[$i]->scan_date)),
                                'jenis'        => ((date('H:i:s', strtotime($data1[$i]->scan_date)) >= date('H:i:s', strtotime('12:00:00'))) ? 'out' : 'in'),
                                'keterangan'   => '0',
                                'uraian'       => 'up_face',
                                'user_created' => null,
                            );

                            $last_update = date('Y-m-d', strtotime($data1[$i]->scan_date)) . " " . date('H:i:s', strtotime($data1[$i]->scan_date));
                            $action      = $this->absen_enroll_model->save($data_save);
                        }

                    }
                    // else {
                    //   show_404();
                    // }

                }

                $data_update = array(
                    'updated_at' => $last_update,
                );
                $action = $this->master_device_model->edit($id, $data_update);

                // $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            }
            // else {
            //     $this->session->set_flashdata('message', array('message' => 'No Data..', 'class' => 'alert-success'));
            // }
        }
        // redirect('dashboard/tarik_absen');

        // $last_pull = $data['data_SN']->updated_at;
        // $tgl = date('Y-m-d', strtotime($last_pull));
        // $waktu = date('H:i:s', strtotime($last_pull));

        // $link_get_data_absen = "http://199.0.0.107/fingerspot/api/absen/devicescan?sn=$sn&tanggal=$tgl&waktu=$waktu";
        // // $link_get_data_absen = "http://36.66.239.107/fingerspot/api/absen/devicescan?sn=$sn&tanggal=$tgl&waktu=$waktu";
        // $get_all_data_absen  = file_get_contents($link_get_data_absen);
        // $data  = json_decode($get_all_data_absen);
        // $data1 = $data->data;

        // if($data1){
        //     $jml_data = count($data1);
        //     $last_update = $this->now;
        //     for($i=0;$i<$jml_data;$i++){
        //         $data_PNS = $this->pns_model->first(
        //             array(
        //                 "pns.id" => $data1[$i]->pin
        //             )
        //         );

        //         if($data_PNS){
        //             $data_absen_enroll = $this->absen_enroll_model->first(
        //                 array(
        //                     'code'          => $data1[$i]->pin,
        //                     'PNS_PNSNIP'    => $data_PNS->PNS_PNSNIP,
        //                     'time'          => $data1[$i]->scan_date,
        //                     'ip'            => $data1[$i]->sn
        //                 )
        //             );

        //             if(!$data_absen_enroll){
        //                 $data_save = array(
        //                     'code'          => $data1[$i]->pin,
        //                     'PNS_PNSNIP'    => $data_PNS->PNS_PNSNIP,
        //                     'PNS_PNSNAM'    => $data_PNS->PNS_PNSNAM,
        //                     'PNS_GLRDPN'    => $data_PNS->PNS_GLRDPN,
        //                     'PNS_GLRBLK'    => $data_PNS->PNS_GLRBLK,
        //                     'PNS_UNOR'      => $data_PNS->PNS_UNOR,
        //                     'ip'            => $data1[$i]->sn,
        //                     'time'          => $data1[$i]->scan_date,
        //                     'tanggal'       => date('Y-m-d', strtotime($data1[$i]->scan_date)),
        //                     'waktu'         => date('H:i:s', strtotime($data1[$i]->scan_date)),
        //                     'jenis'         => ((date('H:i:s', strtotime($data1[$i]->scan_date)) >= date('H:i:s', strtotime('12:00:00'))) ? 'out' : 'in'),
        //                     'keterangan'    => '0',
        //                     'uraian'        => 'up_face',
        //                     'user_created'  => null,
        //                 );

        //                 $last_update = date('Y-m-d', strtotime($data1[$i]->scan_date))." ".date('H:i:s', strtotime($data1[$i]->scan_date));
        //                 $action = $this->absen_enroll_model->save($data_save);
        //             }

        //         }
        //         // else {
        //         //     show_404();
        //         // }

        //     }

        //     $data_update = array(
        //         'updated_at'  => $last_update,
        //     );
        //     $action = $this->master_device_model->edit($id, $data_update);

        //     $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
        // } else {
        //     $this->session->set_flashdata('message', array('message' => 'No Data..', 'class' => 'alert-success'));
        // }
        // redirect('dashboard/tarik_absen');
    }

}
