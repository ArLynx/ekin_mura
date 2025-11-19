<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tempat_bertugas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('master_unit_organisasi_model');
        $this->load->model('unor_model');
        $this->page_title = 'Master Tempat Bertugas';
    }



    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Kondisi Kerja', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';

        $this->render('tempat_bertugas/list', $data);
    }


        public function add()
{
     if ($this->input->post()) {
    // $id_users         = get_session('id_users');
    // $dbkinerja        = get_config_item('dbkinerja');
    $kelas_jabatan = $this->input->post('selected_kelas_jabatan', true);
    $tahun = $this->input->post('selected_tahun', true);
    $alokasi_tpp = $this->input->post('alokasi_tpp', true);
    $basic_tpp = $this->input->post('basic_tpp', true);
    $dibayarkan = $this->input->post('dibayarkan', true);
    $edit = $this->input->post('edit', true);
    $id = $this->input->post('id', true);

     
     if($edit==1){
    $requireOption = [
                    'method'      => 'PUT',
                    'url'         => $this->svc . "api/tempat_bertugas/" . $id,
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [
                    'kelas_jabatan'    => $kelas_jabatan,
                    'tahun'            => $tahun,
                    'basic_tpp'        => $basic_tpp,
                    'alokasi_tpp'      => $alokasi_tpp,
                    'dibayarkan'       => $dibayarkan
                    // Include other fields as needed
                ],
                    'returnArray' => true,
                ];
     }else{
    $requireOption = [
                    'method'      => 'POST',
                    'url'         => $this->svc . "api/tempat_bertugas",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [
                    'kelas_jabatan'    => $kelas_jabatan,
                    'tahun'            => $tahun,
                    'basic_tpp'        => $basic_tpp,
                    'alokasi_tpp'      => $alokasi_tpp,
                    'dibayarkan'       => $dibayarkan
                    // Include other fields as needed
                ],
                    'returnArray' => true,
                ];
     }
            


                $save = $this->makeRequest($requireOption);

                          if ($save) {
                             if($edit==1){
                        $this->session->set_flashdata('message', ['message' => 'Edit Berhasil..', 'class' =>  'alert-success' ]);
                                                    }else{
                        $this->session->set_flashdata('message', ['message' => 'Input Berhasil..', 'class' =>  'alert-success' ]);
                             }
                  
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong..', 'class' => 'alert-danger'));
                }
       

                   redirect('dashboard/tempat_bertugas');
            }

}


    public function delete()
{
     if ($this->input->post()) {
   

    $id = $this->input->post('id_delete', true);

    $requireOption = [
                    'method'      => 'DELETE',
                    'url'         => $this->svc . "api/tempat_bertugas/" . $id,
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [
                ],
                    'returnArray' => true,
                ];
     
                $save = $this->makeRequest($requireOption);

                if ($save) {
                    $this->session->set_flashdata('message', ['message' => 'Berhasil Hapus...', 'class' =>  'alert-success' ]);
                } else {
                    $this->session->set_flashdata('message', array('message' => 'Something went wrong..', 'class' => 'alert-danger'));
                }
       

                   redirect('dashboard/tempat_bertugas');
            }

}



}
