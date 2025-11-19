<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    function get_kadis($unor) {
        $this->ci->db->where('pns.PNS_UNOR', $unor);
        $this->ci->db->where('pns.PNS_JABSTR', '0001'); //Kepala
        return $this->ci->db->get('pns')->row();
    }

    function get_kadis_plt($unor) {
        $this->ci->db->select('a.KD_GENPOS, c.PNS_PNSNIP, c.PNS_PNSNAM, c.PNS_GLRDPN, c.PNS_GLRBLK');
        $this->ci->db->join('pns_plt b', "b.pns_jabstr_plt = a.KD_GENPOS AND b.pns_unor_plt = {$unor}", 'left');
        $this->ci->db->join('pns c', "c.PNS_PNSNIP = b.pns_pnsnip", 'left');
        $this->ci->db->where('a.KD_UNOR', $unor);
        $this->ci->db->like('a.NM_GENPOS', 'kepala dinas');
        return $this->ci->db->get('genpos a')->row();
    }

    function get_sekretaris($unor) {
        $this->ci->db->where('pns.PNS_UNOR', $unor);
        $this->ci->db->where('pns.PNS_JABSTR', '0002'); //Sekretaris
        return $this->ci->db->get('pns')->row();
    }

    function get_atasan_skpd_satpol() {
        $this->ci->db->select('p.PNS_PNSNIP, p.PNS_PNSNAM, p.PNS_GLRDPN, p.PNS_GLRBLK');
        $this->ci->db->from('pns p');
        $this->ci->db->where('p.PNS_PNSNIP', '196310201989031013');
        return $this->ci->db->get()->row();
    }

    function get_atasan_skpd_keclada() {
        $this->ci->db->select('p.PNS_PNSNIP, p.PNS_PNSNAM, p.PNS_GLRDPN, p.PNS_GLRBLK');
        $this->ci->db->from('pns p');
        $this->ci->db->where('p.PNS_PNSNIP', '198211062000121001');
        return $this->ci->db->get()->row();
    }   

    function get_atasan_skpd_perikanan() {
        $this->ci->db->select('p.PNS_PNSNIP, p.PNS_PNSNAM, p.PNS_GLRDPN, p.PNS_GLRBLK');
        $this->ci->db->from('pns p');
        $this->ci->db->where('p.PNS_PNSNIP', '196411211993031004');
        return $this->ci->db->get()->row();
    }
}