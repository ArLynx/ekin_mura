<?php
# @Author: Awan Tengah
# @Date:   2020-07-06T09:59:40+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2020-09-17T09:32:30+07:00

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Tpp_gabungan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_validation();

        $this->load->model('absen_enroll_model');
        $this->load->model('atasan_skpd_model');
        $this->load->model('cpns_model');
        $this->load->model('kinerja/tpp_gabungan_doc_model', 'tpp_gabungan_doc_model');
        $this->load->model('kinerja/tpp_max_before_2020_model', 'tpp_max_before_2020_model');
        $this->load->model('master_index_tpp_model');
        $this->load->model('master_pengurangan_tpp_model');
        $this->load->model('master_tukin_bpk_model');
        $this->load->model('pns_model');
         $this->load->model('pns_plt_model');
        $this->load->model('pns_hukuman_model');
        $this->load->model('unor_model');

        $this->page_title = 'TPP Gabungan';
    }

    public function get_data()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5 || $id_groups == 6 || $this->_user_login->PNS_PNSNIP == '197712242005012006') { //admin, superadmin & BPKAD
            $unor = decode_crypt($this->input->get('unor', true));
        } else {
            $unor = get_session('unor');
        }

        if (get_session('akses_login') == '1' && $this->_user_login->PNS_PNSNIP != '197712242005012006') {
            $nip = $this->_user_login->PNS_PNSNIP;
        } else {
            $nip = '';
        }

        $month = decode_crypt($this->input->get('month', true));
        $year  = $this->input->get('year', true);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_tpp_gabungan?unor={$unor}&month={$month}&year={$year}&nip={$nip}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $get_all_data = $this->makeRequest($requireOption);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($get_all_data));
    }

    public function index()
    {
        $id_groups = get_session('id_groups');
        if ($id_groups == 1 || $id_groups == 5 || $id_groups == 6 || $this->_user_login->PNS_PNSNIP == '197712242005012006') { //superadmin & BPKAD
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

        $data['page_title'] = $this->page_title;
        $data['breadcrumb'] = [
            ['link' => '', 'title' => 'TPP Gabungan', 'icon' => '', 'active' => '1'],
        ];
        $data['active_menu'] = 'rekapitulasi';

        $data['id_groups'] = get_session('id_groups');
        $data['unor']      = get_session('unor');
        $this->render('tpp_gabungan/list', $data);
    }

    public function report($unor_encrypt = null, $month = null, $year = null, $selected_ttd = null)
    {
        $id_groups = get_session('id_groups');
        // if ($id_groups != '5') {
        //     if ($year == 2022) {
        //         show_404();
        //     }
        // }
        if (is_null($unor_encrypt) && is_null($month) && is_null($year)) {
            show_404();
        }
        if ($id_groups != 1 && $id_groups != 2 && $id_groups != 5 && $id_groups != 6 && $this->_user_login->PNS_PNSNIP != '197712242005012006') {
            show_404();
        }
        if ($id_groups == 2) {
            if (decode_crypt($unor_encrypt) != get_session('unor')) {
                show_404();
            }
        }
        $get_unor           = $this->unor_model->first(decode_crypt($unor_encrypt));
        $unor               = decode_crypt($unor_encrypt);
        $month              = decode_crypt($month);
        $data['unor_text']  = $get_unor ? strtoupper($get_unor->NM_UNOR) : '';
        $data['month_text'] = strtoupper(get_indo_month_name($month));
        $data['month']      = $month;
        $data['year']       = $year;
        $data['nip_ttd']    = $selected_ttd;
        $data['selected_penanda_tangan_plt'] = $this->pns_plt_model->get_detail_pns_plt($selected_ttd,$unor);
        $data['selected_penanda_tangan'] = $this->pns_model->get_detail_pns($selected_ttd);
        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_tpp_gabungan?unor={$unor}&month={$month}&year={$year}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data['tpp_gabungan'] = $this->makeRequest($requireOption);

        $decode_unor = decode_crypt($unor_encrypt);

        $data['atasan_skpd'] = $this->atasan_skpd_model->all(
            array(
                'fields'      => "atasan_skpd.title, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA",
                'left_join'   => array(
                    'pns' => "pns.id_master_kelas_jabatan = atasan_skpd.id_master_kelas_jabatan",
                ),
                'where_false' => array(
                    'atasan_skpd.unor'                       => "'{$decode_unor}'",
                    'pns.PNS_PNSNIP NOT IN '                 => "(SELECT nip FROM pns_ex)",
                    'atasan_skpd.id_master_kelas_jabatan !=' => '0',
                ),
            ),
            false
        );

        $data['pengurangan_tpp'] = $this->master_pengurangan_tpp_model->first([
            'tahun' => $year,
        ]);

        $print_page = $this->load->view('tpp_gabungan/report', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode'        => 'utf-8',
            'orientation' => 'L',
        ]);
        $mpdf->WriteHTML($print_page);
        $mpdf->Output();
    }

    public function report_excel($unor_encrypt = null, $month = null, $year = null)
    {
        // if (decode_crypt($month) >= '7' && $year >= 2021) {
        //     show_404();
        // }
        if (is_null($unor_encrypt) && is_null($month) && is_null($year)) {
            show_404();
        }

        $id_groups = get_session('id_groups');
        if ($id_groups != 1 && $id_groups != 2 && $id_groups != 5 && $id_groups != 6 && $this->_user_login->PNS_PNSNIP != '197712242005012006') {
            show_404();
        }
        if ($id_groups == 2) {
            if (decode_crypt($unor_encrypt) != get_session('unor')) {
                show_404();
            }
        }
        $get_unor   = $this->unor_model->first(decode_crypt($unor_encrypt));
        $unor       = decode_crypt($unor_encrypt);
        $month      = decode_crypt($month);
        $unor_text  = $get_unor ? strtoupper($get_unor->NM_UNOR) : '';
        $month_text = strtoupper(get_indo_month_name($month));

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_tpp_gabungan?unor={$unor}&month={$month}&year={$year}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data_tpp_gabungan = $this->makeRequest($requireOption);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', "DAFTAR PENERIMA TAMBAHAN PENGHASILAN PNS (TPP)");
        $sheet->setCellValue('A2', "{$unor_text} KABUPATEN KOTAWARINGIN BARAT");
        $sheet->setCellValue('A3', "PERIODE {$month_text} {$year}");

        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $bold_style = [
            'font' => [
                'bold' => true,
            ],
        ];

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            $sheet->getStyle("A1:H3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A1:H3")->applyFromArray($bold_style);
        }

        $sheet->getStyle("A5:H5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:H5")->applyFromArray($bold_style);
        $sheet->getStyle("A5:H5")->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );

        $sheet->getRowDimension('5')->setRowHeight(30);

        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'NIP');
        $sheet->setCellValue('C5', 'NAMA');
        $sheet->setCellValue('D5', 'JUMLAH KOTOR');
        $sheet->setCellValue('E5', 'IWP/BPJS');
        $sheet->setCellValue('F5', 'PPH');
        $sheet->setCellValue('G5', 'TOTAL BERSIH');
        $sheet->setCellValue('H5', 'SKPD');

        $sheet->getStyle('D')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('G')->getNumberFormat()->setFormatCode('#,##0');

        $i                            = 6;
        $sum_tpp_gabungan             = 0;
        $sum_cost_bpjs                = 0;
        $sum_pph                      = 0;
        $sum_tpp_gabungan_setelah_pph = 0;

        if ($data_tpp_gabungan) {
            if (!empty($data_tpp_gabungan->data)) {
                foreach ($data_tpp_gabungan->data as $key => $row) {
                    $sheet->setCellValue("A{$i}", ++$key);
                    $sheet->setCellValueExplicit("B{$i}", $row->PNS_PNSNIP, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue("C{$i}", $row->PNS_NAMA);
                    $sheet->setCellValue("D{$i}", $row->tpp_gabungan);
                    $sheet->setCellValue("E{$i}", $row->cost_bpjs);
                    $sheet->setCellValue("F{$i}", $row->pph);
                    $sheet->setCellValue("G{$i}", $row->tpp_gabungan_setelah_pph);
                    $sheet->setCellValue("H{$i}", $unor_text);

                    $sum_tpp_gabungan += $row->tpp_gabungan;
                    $sum_cost_bpjs += $row->cost_bpjs;
                    $sum_pph += $row->pph;
                    $sum_tpp_gabungan_setelah_pph += $row->tpp_gabungan_setelah_pph;

                    $i++;
                }
            }
        }

        $lastColumn = $sheet->getHighestColumn();
        $lastRow    = $sheet->getHighestRow() + 1;

        $sheet->mergeCells("A{$lastRow}:C{$lastRow}");

        $sheet->getStyle("A{$lastRow}:C{$lastRow}")->applyFromArray($bold_style);
        $sheet->getStyle("A{$lastRow}:C{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue("A{$lastRow}", 'TOTAL');
        $sheet->setCellValue("D{$lastRow}", $sum_tpp_gabungan);
        $sheet->setCellValue("E{$lastRow}", $sum_cost_bpjs);
        $sheet->setCellValue("F{$lastRow}", $sum_pph);
        $sheet->setCellValue("G{$lastRow}", $sum_tpp_gabungan_setelah_pph);

        $filename = "TPP GABUNGAN {$unor_text} PERIODE {$month_text} {$year}.xlsx";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save("php://output");
    }

    public function template_sipd($unor_encrypt = null, $month = null, $year = null)
    {
        if (is_null($unor_encrypt) && is_null($month) && is_null($year)) {
            show_404();
        }

        $id_groups = get_session('id_groups');
        if ($id_groups != 1 && $id_groups != 2 && $id_groups != 5 && $id_groups != 6 && $this->_user_login->PNS_PNSNIP != '197712242005012006') {
            show_404();
        }
        if ($id_groups == 2) {
            if (decode_crypt($unor_encrypt) != get_session('unor')) {
                show_404();
            }
        }

        $get_unor   = $this->unor_model->first(decode_crypt($unor_encrypt));
        $unor       = decode_crypt($unor_encrypt);
        $month      = decode_crypt($month);
        $unor_text  = $get_unor ? strtoupper($get_unor->NM_UNOR) : '';
        $month_text = strtoupper(get_indo_month_name($month));

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_tpp_gabungan?unor={$unor}&month={$month}&year={$year}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data_tpp_gabungan = $this->makeRequest($requireOption);

        $requireOption = [
            'method'      => 'GET',
            'url'         => $this->svc . "api/get_pegawai_tpp?unor={$unor}",
            'headers'     => [
                'Authorization' => get_session('auth_token'),
            ],
            'body'        => [],
            'returnArray' => true,
        ];
        $data_pegawai = $this->makeRequest($requireOption);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $header_template = [
            "nama_pegawai",
            "nip",
            "nik",
            "tanggal_lahir",
            "alamat",
            "tipe_jabatan",
            "eselon",
            "golongan",
            "pns_pppk",
            "nama_jabatan",
            "kode_bank",
            "nama_bank",
            "npwp",
            "nomor_rekening_bank_pegawai",
            "belanja_tpp_beban_kerja",
            "belanja_tpp_tempat_bertugas",
            "belanja_tpp_kondisi_kerja",
            "belanja_tpp_kelangkaan_profesi",
            "belanja_tpp_prestasi_kerja",
            "tunjangan_iuran_jaminan_kesehatan",
            "tunjangan_iuran_jaminan_kecelakaan_kerja",
            "tunjangan_iuran_jaminan_kematian",
            "tunjangan_jaminan_hari_tua",
            "tunjangan_jaminan_pensiun",
            "iwp_1%",
            "tunjangan_iuran_simpanan_tapera",
            "pph_21",
            "zakat",
            "bulog",
            "jumlah_ditransfer",
            "jumlah_potongan",
        ];

        $columnRange = function ($startColumn, $endColumn) {
            ++$endColumn;
            for ($column = $startColumn; $column !== $endColumn; ++$column) {
                yield $column;
            }
        };

        foreach ($columnRange('A', 'AE') as $key => $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheet->setCellValue("{$column}1", $header_template[$key]);
        }

        $arr_pegawai = [];

        if ($data_pegawai) {
            if (!empty($data_pegawai->data)) {
                foreach ($data_pegawai->data as $key => $row) {
                    $getdob = substr($row->PNS_PNSNIP, 0, 8);
                    $ydob   = substr($getdob, 0, 4);
                    $mdob   = substr($getdob, 4, 2);
                    $ddob   = substr($getdob, 6, 2);
                    $dob    = to_date_format("{$ydob}-{$mdob}-{$ddob}", 'd-M-y', false);

                    $tipe_jabatan = ($row->id_master_jabatan_pns == '1' || $row->id_master_jabatan_pns == '2') ? 'STRUKTURAL' : (($row->id_master_jabatan_pns == '3' || $row->id_master_jabatan_pns == '4') ? 'JFU' : '');

                    $check_cpns   = $this->cpns_model->first(['nip' => $row->PNS_PNSNIP]);
                    $tipe_pegawai = $check_cpns ? 'CPNS' : $row->tipe_pegawai;

                    $arr_pegawai[$row->PNS_PNSNIP]["nik"]                         = $row->PNS_NIK;
                    $arr_pegawai[$row->PNS_PNSNIP]["tanggal_lahir"]               = $dob;
                    $arr_pegawai[$row->PNS_PNSNIP]["alamat"]                      = $row->PNS_ALAMAT;
                    $arr_pegawai[$row->PNS_PNSNIP]["tipe_jabatan"]                = $tipe_jabatan;
                    $arr_pegawai[$row->PNS_PNSNIP]["eselon"]                      = $row->SEBUTAN_ESELON;
                    $arr_pegawai[$row->PNS_PNSNIP]["golongan"]                    = $row->NM_GOL;
                    $arr_pegawai[$row->PNS_PNSNIP]["pns_pppk"]                    = $tipe_pegawai;
                    $arr_pegawai[$row->PNS_PNSNIP]["nama_jabatan"]                = '';
                    $arr_pegawai[$row->PNS_PNSNIP]["kode_bank"]                   = '';
                    $arr_pegawai[$row->PNS_PNSNIP]["nama_bank"]                   = '';
                    $arr_pegawai[$row->PNS_PNSNIP]["npwp"]                        = $row->PNS_NPWP;
                    $arr_pegawai[$row->PNS_PNSNIP]["nomor_rekening_bank_pegawai"] = $row->PNS_NO_REK;
                }
            }
        }

        if ($data_tpp_gabungan) {
            if (!empty($data_tpp_gabungan->data)) {
                foreach ($data_tpp_gabungan->data as $key => $row) {
                    $arr_pegawai[$row->PNS_PNSNIP]["nama_pegawai"]                             = $row->PNS_NAMA;
                    $arr_pegawai[$row->PNS_PNSNIP]["nip"]                                      = $row->PNS_PNSNIP;
                    $arr_pegawai[$row->PNS_PNSNIP]["belanja_tpp_beban_kerja"]                  = $row->tpp_beban_kerja != 0 ? round(($row->tpp_gabungan + $row->tunjangan_plt + $row->nominal_rapel) * 40 / 100) : 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["belanja_tpp_tempat_bertugas"]              = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["belanja_tpp_kondisi_kerja"]                = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["belanja_tpp_kelangkaan_profesi"]           = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["belanja_tpp_prestasi_kerja"]               = $row->tpp_beban_kerja != 0 ? round(($row->tpp_gabungan + $row->tunjangan_plt + $row->nominal_rapel) * 72 / 100) : ($row->tpp_gabungan + $row->tunjangan_plt + $row->nominal_rapel);
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_iuran_jaminan_kesehatan"]        = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_iuran_jaminan_kecelakaan_kerja"] = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_iuran_jaminan_kematian"]         = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_jaminan_hari_tua"]               = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_jaminan_pensiun"]                = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["iwp_1%"]                                   = $row->cost_bpjs;
                    $arr_pegawai[$row->PNS_PNSNIP]["tunjangan_iuran_simpanan_tapera"]          = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["pph_21"]                                   = $row->pph;
                    $arr_pegawai[$row->PNS_PNSNIP]["zakat"]                                    = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["bulog"]                                    = 0;
                    $arr_pegawai[$row->PNS_PNSNIP]["jumlah_ditransfer"]                        = $row->tpp_gabungan_setelah_pph;
                    $arr_pegawai[$row->PNS_PNSNIP]["jumlah_potongan"]                          = ($row->cost_bpjs + $row->pph);
                }
            }
        }

        $arr_pegawai = json_decode(json_encode($arr_pegawai), false);

        if (!empty($arr_pegawai)) {
            $i = 2;
            foreach ($arr_pegawai as $key2 => $row2) {
                foreach ($columnRange('A', 'AE') as $key => $column) {
                    if (!empty($row2->nama_pegawai)) {
                        if (in_array($column, ['B', 'C', 'M', 'N'])) {
                            $sheet->setCellValueExplicit("{$column}{$i}", (isset($row2->{$header_template[$key]}) ? $row2->{$header_template[$key]} : ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        } else {
                            $sheet->setCellValue("{$column}{$i}", (isset($row2->{$header_template[$key]}) ? $row2->{$header_template[$key]} : ''));
                        }
                    }
                }
                if (!empty($row2->nama_pegawai)) {
                    $i++;
                }
            }
        }

        $filename = "Template SIPD {$unor_text} - {$month_text} {$year}.xlsx";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save("php://output");
    }

    public function uploadDOC()
    {
        if ($this->input->post()) {
            $selected_sopd_modal  = $this->input->post('selected_sopd_modal', true);
            $selected_month_modal = $this->input->post('selected_month_modal', true);
            $selected_year_modal  = $this->input->post('selected_year_modal', true);

            $get_tpp_gabungan_doc = $this->tpp_gabungan_doc_model->first(
                array(
                    'unor'  => decode_crypt($selected_sopd_modal),
                    'month' => decode_crypt($selected_month_modal),
                    'year'  => $selected_year_modal,
                )
            );

            $name         = 'doc_upload_modal';
            $check_upload = !empty($_FILES[$name]['name']);

            if ($check_upload) {
                $this->load->library('upload_file');
                create_folder(get_config_item('lampiran_path'));
                $type       = 'file';
                $doc_upload = $this->upload_file->upload($name, get_config_item('lampiran_path'), $type);
                if ($get_tpp_gabungan_doc) {
                    unlink_file(get_config_item('lampiran_path') . $get_tpp_gabungan_doc->doc);
                }
            } else {
                $doc_upload = '';
            }

            if (!$get_tpp_gabungan_doc) {
                $data_save = array(
                    'unor'       => decode_crypt($selected_sopd_modal),
                    'month'      => decode_crypt($selected_month_modal),
                    'year'       => $selected_year_modal,
                    'doc'        => $doc_upload,
                    'created_at' => $this->now,
                );
                $this->tpp_gabungan_doc_model->save($data_save);
            } else {
                $data_update = array(
                    'doc'        => $doc_upload,
                    'created_at' => $this->now,
                );
                $this->tpp_gabungan_doc_model->edit($get_tpp_gabungan_doc->id, $data_update);
            }

            $this->session->set_flashdata('message', array('message' => 'Action Successfully..', 'class' => 'alert-success'));
            redirect('dashboard/tpp-gabungan');
        }
    }
}
