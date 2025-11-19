<?php

/*
 * @Author: Awan Tengah
 * @Date: 2020-09-02 10:26:19
 * @Last Modified by: Awan Tengah
 * @Last Modified time: 2020-09-02 14:34:19
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Atasan_langsung extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('kinerja/pns_atasan_model', 'pns_atasan_model');
        $this->load->model('pns_model');
    }

    public function get_data()
    {
        $selected_sopd = $this->input->get('selected_sopd', true);

        $where1 = array();
        $where2 = array();

        if (!empty($selected_sopd)) {
            $selected_sopd = decode_crypt($selected_sopd);

            $dbkinerja = get_config_item('dbkinerja');

            $where1 = array(
                'pns.PNS_UNOR'          => "'{$selected_sopd}'",
                'pns.PNS_PNSNIP NOT IN' => "(SELECT nip FROM pns_ex where unor = '{$selected_sopd}') AND pns.PNS_PNSNIP NOT IN (SELECT pns_pnsnip FROM mutasi_detail WHERE mutasi_detail.pns_unor_lama = '{$selected_sopd}' AND mutasi_detail.status = 0)",
            );

            $where2 = array(
                'mutasi_detail.pns_unor_baru' => "'{$selected_sopd}'",
                'mutasi_detail.status'        => '0',
                'pns.PNS_PNSNIP NOT IN'       => "(SELECT nip FROM pns_ex where unor = '{$selected_sopd}')",
            );

            $query1 = $this->pns_model->all(
                array(
                    'fields'      => "pns.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_PNSNAM, pns.PNS_GLRDPN, pns.PNS_GLRBLK, unor.KD_UNOR, unor.NM_UNOR, CONCAT(gol.NM_PKT, CONCAT(' ', gol.NM_GOL)) as pangkat,
                    eselon.NM_ESELON, pns.PNS_GOLRU, pns.id_master_kelas_jabatan, pns.PNS_PHOTO, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_kelas_jabatan.id_master_jabatan_pns, master_jabatan_pns.jabatan_pns,
                    IF(pa_pns.PNS_GLRDPN IS NOT NULL AND pa_pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pa_pns.PNS_GLRDPN, '. '), CONCAT(pa_pns.PNS_PNSNAM, CONCAT(', ', pa_pns.PNS_GLRBLK))), IF(pa_pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pa_pns.PNS_GLRDPN, '. '), pa_pns.PNS_PNSNAM), IF(pa_pns.PNS_GLRBLK IS NOT NULL, CONCAT(pa_pns.PNS_PNSNAM, CONCAT(', ', pa_pns.PNS_GLRBLK)), pa_pns.PNS_PNSNAM))) as PNS_ATASAN_NAMA,
                    pa.id as id_pns_atasan, pa.pns_atasan",
                    'left_join'   => array(
                        'unor'                       => 'unor.KD_UNOR = pns.PNS_UNOR',
                        'gol'                        => 'gol.KD_GOL = pns.PNS_GOLRU',
                        'eselon'                     => 'eselon.KD_ESELON = pns.PNS_KODECH',
                        'master_kelas_jabatan'       => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                        'master_jabatan_pns'         => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        "{$dbkinerja}.pns_atasan pa" => 'pa.PNS_PNSNIP = pns.PNS_PNSNIP',
                        'pns pa_pns'                 => 'pa_pns.PNS_PNSNIP = pa.pns_atasan',
                    ),
                    'where_false' => $where1,
                    'not_like'    => array(
                        'pns.PNS_PNSNIP' => 'TKD',
                    ),
                    'group_by'    => 'pns.PNS_PNSNIP',
                ), true, true
            );
            $this->db->reset_query();

            $query2 = $this->pns_model->all(
                array(
                    'fields'      => "pns.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_PNSNAM, pns.PNS_GLRDPN, pns.PNS_GLRBLK, unor.KD_UNOR, unor.NM_UNOR, CONCAT(gol.NM_PKT, CONCAT(' ', gol.NM_GOL)) as pangkat,
                    eselon.NM_ESELON, pns.PNS_GOLRU, pns.id_master_kelas_jabatan, pns.PNS_PHOTO, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_kelas_jabatan.id_master_jabatan_pns, master_jabatan_pns.jabatan_pns,
                    IF(pa_pns.PNS_GLRDPN IS NOT NULL AND pa_pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pa_pns.PNS_GLRDPN, '. '), CONCAT(pa_pns.PNS_PNSNAM, CONCAT(', ', pa_pns.PNS_GLRBLK))), IF(pa_pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pa_pns.PNS_GLRDPN, '. '), pa_pns.PNS_PNSNAM), IF(pa_pns.PNS_GLRBLK IS NOT NULL, CONCAT(pa_pns.PNS_PNSNAM, CONCAT(', ', pa_pns.PNS_GLRBLK)), pa_pns.PNS_PNSNAM))) as PNS_ATASAN_NAMA,
                    pa.id as id_pns_atasan, pa.pns_atasan",
                    'left_join'   => array(
                        'mutasi_detail'              => 'mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP',
                        'unor'                       => 'unor.KD_UNOR = mutasi_detail.pns_unor_baru',
                        'gol'                        => 'gol.KD_GOL = pns.PNS_GOLRU',
                        'eselon'                     => 'eselon.KD_ESELON = pns.PNS_KODECH',
                        'master_kelas_jabatan'       => 'master_kelas_jabatan.id = mutasi_detail.id_master_kelas_jabatan_baru',
                        'master_jabatan_pns'         => 'master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns',
                        "{$dbkinerja}.pns_atasan pa" => 'pa.PNS_PNSNIP = pns.PNS_PNSNIP',
                        'pns pa_pns'                 => 'pa_pns.PNS_PNSNIP = pa.pns_atasan',
                    ),
                    'where_false' => $where2,
                    'not_like'    => array(
                        'pns.PNS_PNSNIP' => 'TKD',
                    ),
                    'group_by'    => 'pns.PNS_PNSNIP',
                ), true, true
            );
            $this->db->reset_query();

            $data = $this->db->query("SELECT * FROM ($query1 UNION $query2) as zzz ORDER BY zzz.kelas_jabatan DESC, zzz.PNS_GOLRU DESC")->result();

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_pns_encrypt']        = encode_crypt($row->id);
                $tmp[$key]['id_pns_atasan_encrypt'] = encode_crypt($row->id_pns_atasan);
                $tmp[$key]['unor_encrypt']          = encode_crypt($row->KD_UNOR);
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
        $data['page_title'] = 'Atasan Langsung';
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'setup', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'Atasan Langsung', 'icon' => '', 'active' => '1'],
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

        $data['selected_unor'] = decode_crypt($this->input->get('unor', true));

        $this->render('atasan_langsung/list', $data);
    }

    public function action()
    {
        $modal_id_pns_atasan   = $this->input->post('modal_id_pns_atasan', true);
        $modal_pns_pnsnip      = $this->input->post('modal_pns_pnsnip', true);
        $modal_atasan_langsung = $this->input->post('modal_atasan_langsung', true);
        $modal_unor            = $this->input->post('modal_unor', true);
        if ($modal_id_pns_atasan &&
            $modal_pns_pnsnip &&
            $modal_atasan_langsung &&
            $modal_unor) {
            $modal_id_pns_atasan = decode_crypt($modal_id_pns_atasan);
            if (!empty($modal_id_pns_atasan)) {
                $check_exists = $this->pns_atasan_model->first($modal_id_pns_atasan);
            } else {
                $check_exists = [];
            }
            if ($check_exists) {
                $data = array(
                    'pns_atasan' => $modal_atasan_langsung,
                );
                $this->pns_atasan_model->edit($modal_id_pns_atasan, $data);
            } else {
                $data = array(
                    'PNS_PNSNIP' => $modal_pns_pnsnip,
                    'pns_atasan' => $modal_atasan_langsung,
                );
                $this->pns_atasan_model->save($data);
            }
            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/atasan-langsung?unor=' . $modal_unor);
        } else {
            $this->session->set_flashdata('message', array('message' => 'Atasan langsung belum dipilih..', 'class' => 'alert-danger'));
            redirect('dashboard/atasan-langsung?unor=' . $modal_unor);
        }
    }

}
