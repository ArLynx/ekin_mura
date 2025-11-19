<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kelangkaan_profesi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('master_kelas_jabatan_model');
        $this->load->model('master_unit_organisasi_model');
        $this->load->model('unor_model');
        $this->page_title = 'Master Kelangkaan Profesi';
    }



    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'master', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Master Kondisi Kerja', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'master';

                $data['kondisi_kerja'] = $this->master_kelas_jabatan_model->all(
                  array(
                    'fields'    => 'master_kelas_jabatan.*,kondisi_kerja.id_kelas_jabatan AS kj,unor.nm_unor as NM_UNOR,kondisi_kerja.besaran_tpp, master_unit_organisasi.unit_organisasi, master_jabatan_pns.jabatan_pns, pns.PNS_PNSNIP as nip , pns.PNS_PNSNAM as nama_pns,pns.PNS_GLRDPN as gelar_depan, pns.PNS_GLRBLK as gelar_belakang, master_unit_organisasi.index_jabatan, master_unit_organisasi.status',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        'master_jabatan_pns'     => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        'pns'     => 'pns.id_master_kelas_jabatan = master_kelas_jabatan.id AND pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex)',
                        'kondisi_kerja' => 'kondisi_kerja.id_kelas_jabatan = master_kelas_jabatan.id',
                        'unor' => 'master_kelas_jabatan.unor = unor.kd_unor',
                    ),
                    'where' => array(
                        'master_unit_organisasi.status' => 'aktif',
                        'Status_Jabatan' => 'aktif'
                    ),
                    // 'where_false' => array(
                    //     'kondisi_kerja.id_kelas_jabatan IS NOT' => 'NULL',
                    // ),
                    'order_by'  => 'kelas_jabatan DESC',
                )
            );   

            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_sopd",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data_sopd = $this->makeRequest($requireOption);
            $data['sopd'] = $data_sopd->data;
           
            

        $selected_year = $this->input->get('selected_year', true);
        $data['selected_year'] = $selected_year;
            

        $this->render('kelangkaan_profesi/list', $data);
    }


        public function get_data()
    {
       
        $selected_sopd = $this->input->get('selected_sopd', true);
        
        if ($selected_sopd) {
            $data = $this->master_kelas_jabatan_model->all(
          array(
                    'fields'    => 'master_kelas_jabatan.*, master_unit_organisasi.unit_organisasi, master_jabatan_pns.jabatan_pns, pns.PNS_PNSNIP as nip , pns.PNS_PNSNAM as nama_pns,pns.PNS_GLRDPN as gelar_depan, pns.PNS_GLRBLK as gelar_belakang, master_unit_organisasi.index_jabatan, master_unit_organisasi.status',
                    'left_join' => array(
                        'master_unit_organisasi' => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        'master_jabatan_pns'     => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        'pns'     => 'pns.id_master_kelas_jabatan = master_kelas_jabatan.id ',
                    ),
                    'where'     => array(
                        'master_kelas_jabatan.unor' => $selected_sopd,
                        'master_unit_organisasi.status' => 'aktif',
                        'Status_Jabatan' => 'aktif'
                    ),
                       'where_false' => array(
                            'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex)",
                        ),
                    'order_by'  => 'kelas_jabatan DESC',
                )
            );
            
        }
         else {
            $data = array();
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));

            
    }


        public function add()
{
     if ($this->input->post()) {
    // $id_users         = get_session('id_users');
    // $dbkinerja        = get_config_item('dbkinerja');
    $id_kelas_jabatan = $this->input->post('selected_kelas_jabatan', true);
    $tahun = $this->input->post('selected_tahun', true);
    $besaran_tpp = $this->input->post('besaran_tpp', true);
    $edit = $this->input->post('edit', true);
    $kelangkaan_id = $this->input->post('kelangkaan_id', true);

       $data['selected_year'] = $tahun;
     
     if($edit==1){
    $requireOption = [
                    'method'      => 'PUT',
                    'url'         => $this->svc . "api/kelangkaan_profesi/" . $kelangkaan_id,
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [
                    'id_kelas_jabatan' => $id_kelas_jabatan,
                    'tahun'            => $tahun,
                    'besaran_tpp'      => $besaran_tpp,
                    // Include other fields as needed
                ],
                    'returnArray' => true,
                ];
     }else{
    $requireOption = [
                    'method'      => 'POST',
                    'url'         => $this->svc . "api/kelangkaan_profesi",
                    'headers'     => [
                        'Authorization' => get_session('auth_token'),
                    ],
                    'body'        => [
                    'id_kelas_jabatan' => $id_kelas_jabatan,
                    'tahun'            => $tahun,
                    'besaran_tpp'      => $besaran_tpp,
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
       
                redirect('dashboard/kelangkaan_profesi?selected_year=' . urlencode($data['selected_year']));

                //    redirect('dashboard/kelangkaan_profesi');
            }

}

  
    public function delete()
{
     if ($this->input->post()) {
        
    //  $tahun = $this->input->post('selected_tahun', true);
    //  $data['selected_year'] = $tahun;
    $kelangkaan_id = $this->input->post('kelangkaan_id_delete', true);

      $requireOption = [
                    'method'      => 'DELETE',
                    'url'         => $this->svc . "api/kelangkaan_profesi/" . $kelangkaan_id,
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
       

                   redirect('dashboard/kelangkaan_profesi');
            }

}


}
