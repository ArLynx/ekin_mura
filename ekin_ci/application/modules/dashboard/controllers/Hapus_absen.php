<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hapus_absen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();
        $this->load->model('absen_enroll_model');
        $this->page_title = 'Hapus Absen';
    }

    public function get_data()
    {
        $selected_sopd  = $this->input->get('selected_sopd', true);
        $selected_date  = $this->input->get('selected_date', true);
        $selected_month = $this->input->get('selected_month', true);
        $selected_year  = $this->input->get('selected_year', true);
        if ($selected_sopd &&
            $selected_date &&
            $selected_month &&
            $selected_year) {

            $unor      = decode_crypt($selected_sopd);
            $hari      = $selected_date;
            $bulan     = decode_crypt($selected_month);
            $tahun     = $selected_year;
            $arr_where = [
                'ae.PNS_UNOR' => "'{$unor}'",
            ];
            $arr_where_md = [
                'md.pns_unor_baru' => "'{$unor}'",
            ];
            if (!empty($hari)) {
                $where        = ['DAY(ae.tanggal)' => $hari];
                $arr_where    = array_merge($arr_where, $where);
                $arr_where_md = array_merge($arr_where_md, $where);
            }
            if (!empty($bulan)) {
                $where        = ['MONTH(ae.tanggal)' => $bulan];
                $arr_where    = array_merge($arr_where, $where);
                $arr_where_md = array_merge($arr_where_md, $where);
            }
            if (!empty($tahun)) {
                $where        = ['YEAR(ae.tanggal)' => $tahun];
                $arr_where    = array_merge($arr_where, $where);
                $arr_where_md = array_merge($arr_where_md, $where);
            }
            // if (!empty($hari) && !empty($bulan) && !empty($tahun)) {
            //     //DD, DL, CT
            //     $where        = ['ae.PNS_PNSNIP IN ' => "(SELECT PNS_PNSNIP FROM absen_enroll WHERE ae.tanggal = tanggal AND (keterangan = 1 OR keterangan = 2 OR keterangan = 14) AND ae.PNS_UNOR = '{$unor}' AND YEAR(tanggal) = '{$tahun}' AND MONTH(tanggal) = '{$bulan}' AND DAY(tanggal) = '{$hari}') "];
            //     $where_md     = ['md.pns_pnsnip IN ' => "(SELECT PNS_PNSNIP FROM absen_enroll WHERE ae.tanggal = tanggal AND (keterangan = 1 OR keterangan = 2 OR keterangan = 14) AND md.pns_unor_baru = '{$unor}' AND YEAR(tanggal) = '{$tahun}' AND MONTH(tanggal) = '{$bulan}' AND DAY(tanggal) = '{$hari}') "];
            //     $arr_where    = array_merge($arr_where, $where);
            //     $arr_where_md = array_merge($arr_where_md, $where_md);
            // }

            $query1 = $this->absen_enroll_model->all(
                [
                    'fields'          => 'ae.*, u.NM_UNOR',
                    'from'            => 'absen_enroll ae',
                    'left_join'       => [
                        'pns p'  => 'p.PNS_PNSNIP = ae.PNS_PNSNIP',
                        'unor u' => 'u.KD_UNOR = ae.PNS_UNOR',
                    ],
                    'group_start_end' => [
                        'where'    => [
                            'ae.uraian' => 'up_face',
                        ],
                        'or_where' => [
                            'ae.uraian' => '[MANUAL]',
                        ],
                    ],
                    'where_false'     => $arr_where,
                ], true, true
            );

            $query2 = $this->absen_enroll_model->all(
                [
                    'fields'          => 'ae.*, u.NM_UNOR',
                    'from'            => 'absen_enroll ae',
                    'left_join'       => [
                        'pns p'            => 'p.PNS_PNSNIP = ae.PNS_PNSNIP',
                        'unor u'           => 'u.KD_UNOR = ae.PNS_UNOR',
                        'mutasi_detail md' => "md.pns_pnsnip = p.PNS_PNSNIP AND md.status = '0'",
                    ],
                    'group_start_end' => [
                        'where'    => [
                            'ae.uraian' => 'up_face',
                        ],
                        'or_where' => [
                            'ae.uraian' => '[MANUAL]',
                        ],
                    ],
                    'where_false'     => $arr_where_md,
                    'group_by'        => 'PNS_UNOR, PNS_PNSNAM, tanggal',
                ], true, true
            );
            $sql  = "SELECT * FROM ({$query1} UNION {$query2}) as zzz ORDER BY PNS_UNOR, PNS_PNSNAM, tanggal ASC";
            $data = $this->absen_enroll_model->query($sql)->result();
            // echo $this->db->last_query();die;

            $tmp = array();
            foreach ($data as $key => $row) {
                foreach ($row as $childkey => $childrow) {
                    $tmp[$key][$childkey] = $childrow;
                }
                $tmp[$key]['id_encrypt'] = encode_crypt($row->id);
            }
            $data = $tmp;
        } else {
            $data = [];
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function index()
    {
        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '#', 'title' => 'setup', 'icon' => '', 'active' => '0'],
            ['link' => '', 'title' => 'hapus Absen DL/DD', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'setup';

        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5) {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd" : base_url('api/get_all_sopd');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        } else {
            $link_get_all_sopd = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_sopd?unor=" . get_session('unor') : base_url('api/get_all_sopd?unor=') . get_session('unor');
            $get_all_sopd      = file_get_contents($link_get_all_sopd);
        }

        $data['all_sopd'] = json_decode($get_all_sopd);

        $link_get_all_month = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_month" : base_url('api/get_all_month');
        $get_all_month      = file_get_contents($link_get_all_month);
        $data['all_month']  = json_decode($get_all_month);

        $link_get_all_year = ($_SERVER['SERVER_NAME'] == get_config_item('server_name')) ? get_config_item('server_name_with_port') . "api/get_all_year" : base_url('api/get_all_year');
        $get_all_year      = file_get_contents($link_get_all_year);
        $data['all_year']  = json_decode($get_all_year);

        $this->render('hapus_absen/list', $data);
    }

    public function delete()
    {
        $id_encrypt = $this->input->get('id_encrypt', true);
        $check      = $this->absen_enroll_model->first(decode_crypt($id_encrypt));
        if ($check) {
            $action  = $this->absen_enroll_model->delete(decode_crypt($id_encrypt));
            $message = 'Action Successfully..';
            $class   = 'alert-success';
        } else {
            $message = 'Data gagal dihapus';
            $class   = 'alert-danger';
        }

        $this->session->set_flashdata('message', array('message' => $message, 'class' => $class));
        redirect('dashboard/hapus-absen');
    }

}
