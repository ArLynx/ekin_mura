<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $get_config_dbkinerja = get_config_item('dbkinerja');
        $this->table          = "{$get_config_dbkinerja}.kegiatan";
        $this->primary_key    = 'id';
    }

    function get_jam_kerja($hari_kerja,$hari){
        $this->db->select('*');
        $this->db->where('hari_kerja',$hari_kerja);
        $this->db->where('hari',$hari);
        return $this->db->get('kinerja.jam_kerja');
    }
    
    function get_hari_libur($tgl){
		$libur = $this->db->query('select * from absen_libur where tanggal = "'.$tgl.'"');
		$jml_hari_libur = $libur->num_rows();
		return $jml_hari_libur;
    }
    
    function cek_jam_exist($nip,$mulai,$akhir,$id){
        $id_qry = "";

        if($id)
        $id_qry =  " AND id <> '$id'";

        $qry = "SELECT * FROM kinerja.kegiatan
        WHERE PNS_PNSNIP = '$nip' AND (('$mulai' BETWEEN waktu_mulai AND waktu_akhir)
        OR ('$akhir' BETWEEN waktu_mulai AND waktu_akhir)
        OR (waktu_mulai BETWEEN '$mulai' AND '$akhir')
        OR (waktu_akhir BETWEEN '$mulai' AND '$akhir')) AND (status <> '4' AND status <> '9') $id_qry;";

        $pekerjaan = $this->db->query($qry);
        return $pekerjaan;
    }

    function get_tgl_max_entry()
	{
		$this->db->select('*');
		$this->db->where('nama','max_entry_kinerja');
		return $this->db->get('presensi.setting');
	}

    function get_tgl_max_approve()
	{
		$this->db->select('*');
		$this->db->where('nama','max_approve_kinerja');
		return $this->db->get('presensi.setting');
    }
    
    public function get_open_input_bulanan($nip, $bulan)
    {
        $this->db->where('a.nip', $nip);
        $this->db->where('a.month', $bulan);
        $this->db->from('presensi.pns_open_input_bulanan a');
        return $this->db->count_all_results();
    }
}
