<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_tpp extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('master_agama_model');
        $this->load->model('pns_model');
        $this->load->model('unor_model');
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $unor = $this->input->get('unor', true);
        } else {
            $unor = get_session('unor');
        }

        $tipe_pegawai = $this->input->get('tipe_pegawai', true);

        if (!empty($unor)) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$unor}&tipe_pegawai={$tipe_pegawai}",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data = $this->makeRequest($requireOption)->data;
        } else {
            $data = [];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'data pegawai', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'data pegawai';
        $data['page_title']  = 'Data Pegawai TPP';

        $data['data_agama'] = $this->master_agama_model->all();
 $data['base_url'] = base_url();
        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');

        $this->render('pegawai/index_pns', $data);
    }

          public function report()
    {
        $selectedUnor= $this->input->get('unor', true);
        $get_unor   = $this->unor_model->first($selectedUnor);
          if (!empty($selectedUnor)) {
            $requireOption = [
                'method'      => 'GET',
                'url'         => $this->svc . "api/get_pegawai_tpp?unor={$selectedUnor}&tipe_pegawai=0",
                'headers'     => [
                    'Authorization' => get_session('auth_token'),
                ],
                'body'        => [],
                'returnArray' => true,
            ];
            $data['pns'] = $this->makeRequest($requireOption)->data;
        } else {
            $data['pns'] = [];
        }
        // $data['pns'] = $this->pns_model->first();
           $data['unor_text']  = $get_unor ? strtoupper($get_unor->NM_UNOR) : '';
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'data pegawai', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'data pegawai';
        $data['page_title']  = 'Data Pegawai TPP';

        $data['data_agama'] = $this->master_agama_model->all();


        $data['base_url'] = base_url();
        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');

        // $this->render('pegawai/report', $data);
        $print_page = $this->load->view('pegawai/report', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode'        => 'utf-8',
            'orientation' => 'L',
        ]);
        $mpdf->WriteHTML($print_page);
        $mpdf->Output();
     
    }

    public function add()
    {
        $sopd                    = $this->input->post('sopd', true);
        $nip                     = $this->input->post('nip', true);
        $gelar_depan             = $this->input->post('gelar_depan', true);
        $nama                    = $this->input->post('nama', true);
        $gelar_belakang          = $this->input->post('gelar_belakang', true);
        $golru                   = $this->input->post('golru', true);
        $id_master_kelas_jabatan = $this->input->post('id_master_kelas_jabatan', true);
        $id_bank                 = $this->input->post('id_bank', true);
        $no_rek                  = $this->input->post('no_rek', true);
        $npwp                    = $this->input->post('npwp', true);
        $nik                     = $this->input->post('nik', true);
        $alamat                  = $this->input->post('alamat', true);
        $tipe_pegawai            = $this->input->post('tipe_pegawai', true);

        if (
            !empty($sopd) &&
            !empty($nip) &&
            !empty($nama) &&
            !empty($golru) &&
            !empty($id_master_kelas_jabatan)
        ) {
            $name         = 'file';
            $check_upload = !empty($_FILES[$name]['name']);
            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(path_image('user_path'));
                $type = 'image';
                $foto = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());
            } else {
                $foto = null;
            }

            $save_data_to_pns = array(
                'PNS_PNSNIP'              => $nip,
                'PNS_GLRDPN'              => (!is_null($gelar_depan) && !is_null($gelar_depan) && !empty($gelar_depan)) ? $gelar_depan : null,
                'PNS_PNSNAM'              => $nama,
                'PNS_GLRBLK'              => (!is_null($gelar_belakang) && !is_null($gelar_belakang) && !empty($gelar_belakang)) ? $gelar_belakang : null,
                'PNS_UNOR'                => $sopd,
                'PNS_GOLRU'               => $golru,
                'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                'PNS_ID_BANK'             => $id_bank,
                'PNS_NO_REK'              => $no_rek,
                'PNS_NPWP'                => $npwp,
                'PNS_NIK'                 => $nik,
                'PNS_ALAMAT'              => $alamat,
                'PNS_PHOTO'               => $foto,
                'ID_TIPE_PEGAWAI'         => $tipe_pegawai,
            );
            $this->pns_model->save($save_data_to_pns);

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

    public function edit()
    {
        $id_pegawai_tpp          = $this->input->post('id_pegawai_tpp', true);
        $sopd                    = $this->input->post('sopd', true);
        $nip                     = $this->input->post('nip', true);
        $gelar_depan             = $this->input->post('gelar_depan', true);
        $nama                    = $this->input->post('nama', true);
        $gelar_belakang          = $this->input->post('gelar_belakang', true);
        $golru                   = $this->input->post('golru', true);
        $id_master_kelas_jabatan = $this->input->post('id_master_kelas_jabatan', true);
        $id_bank                 = $this->input->post('id_bank', true);
        $no_rek                  = $this->input->post('no_rek', true);
        $npwp                    = $this->input->post('npwp', true);
        $nik                     = $this->input->post('nik', true);
        $alamat                  = $this->input->post('alamat', true);

        if (
            !empty($id_pegawai_tpp) &&
            !empty($sopd) &&
            // !empty($nip) &&
            // !empty($nama) &&
            !empty($golru)
            // &&
            // !empty($id_master_kelas_jabatan)
        ) {
            $check_pegawai_exists = $this->pns_model->first($id_pegawai_tpp);
            if ($check_pegawai_exists) {
                $name         = 'file';
                $check_upload = !empty($_FILES[$name]['name']);
                if ($check_upload) {
                    $this->load->library('upload_file');
                    create_folder(path_image('user_path'));
                    $type = 'image';
                    $foto = $this->upload_file->upload($name, path_image('user_path'), $type, null, null, false, false, current_url());
                    unlink_file(path_image('user_path') . $check_pegawai_exists->PNS_PHOTO);
                } else {
                    $foto = $check_pegawai_exists->PNS_PHOTO;
                }

                if (get_session('id_groups') == '1' || get_session('id_groups') == '5' || get_session('id_groups') == '2') {
                    $update_data_to_pns0 = array(
                        'PNS_PNSNIP'              => $nip,
                        'PNS_GLRDPN'              => (!is_null($gelar_depan) && !empty($gelar_depan)) ? $gelar_depan : null,
                        'PNS_PNSNAM'              => $nama,
                        'PNS_GLRBLK'              => (!is_null($gelar_belakang) && !empty($gelar_belakang)) ? $gelar_belakang : null,
                        'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                    );
                }

                $update_data_to_pns1 = array(
                    // 'PNS_PNSNIP'              => $nip,
                    // 'PNS_GLRDPN'              => (!is_null($gelar_depan) && !empty($gelar_depan)) ? $gelar_depan : null,
                    // 'PNS_PNSNAM'              => $nama,
                    // 'PNS_GLRBLK'              => (!is_null($gelar_belakang) && !empty($gelar_belakang)) ? $gelar_belakang : null,
                    // 'PNS_UNOR'    => $sopd,
                    'PNS_GOLRU'   => $golru,
                    // 'id_master_kelas_jabatan' => $id_master_kelas_jabatan,
                    'PNS_ID_BANK' => $id_bank,
                    'PNS_NO_REK'  => $no_rek,
                    'PNS_NPWP'    => $npwp,
                    'PNS_NIK'     => $nik,
                    'PNS_ALAMAT'  => $alamat,
                    'PNS_PHOTO'   => $foto,
                );

                if (get_session('id_groups') == '1' || get_session('id_groups') == '5' || get_session('id_groups') == '2') {
                    $update_data_to_pns = array_merge($update_data_to_pns0, $update_data_to_pns1);
                } else {
                    $update_data_to_pns = $update_data_to_pns1;
                }
                $this->pns_model->edit($id_pegawai_tpp, $update_data_to_pns);

                $message = array(
                    'type' => 'success',
                    'msg'  => 'Ubah data sukses',
                );
            } else {
                $message = array(
                    'type' => 'danger',
                    'msg'  => 'Pegawai tidak ditemukan',
                );
            }
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

//     public function delete()
//     {
//    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
//         $urlSegments = explode('/', $_SERVER['REQUEST_URI']);
//         $id = end($urlSegments);
//         //   $action = $this->pns_model->delete($id);
//  $save_data_to_pns_ex = array(
//                 'nip'              => $nip,
//                 'unor'              => "unor",
//                 'status'              => "nama",
//                 'date'              => "date",
//                   'keterangan'              => "keterangan"
        
//             );
//             $this->pns_model->save($save_data_to_pns);
//             $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
//             redirect('dashboard/pegawai-tpp');
//         }
//     }

}
