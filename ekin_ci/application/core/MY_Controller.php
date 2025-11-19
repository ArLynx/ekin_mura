<?php

# @Author: Awan Tengah

defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class MY_Controller extends CI_Controller
{
    protected $auth   = true;
    protected $layout = 'layout';
    protected $is_login;
    protected $rules;
    protected $page_title;
    protected $svc;

    public $ci;
    public $now;
    public $_user_login;
    public $_app_title;
    public $_app_title_sort;
    public $_created;
    public $_updated;
    public $_deleted;

    public function _remap($method, $params = array())
    {
        if ($this->auth) {
            $groups     = $this->session->userdata('id_groups');
            $class_name = get_class($this);

            $menu = $this->menu_model->first(
                array(
                    'controller' => $class_name,
                )
            );
            if (!empty($menu)) {
                $privilege = $this->privilege_model->first(
                    array(
                        'id_groups' => $groups,
                        'id_menu'   => $menu->id,
                    )
                );
                if (!is_null($privilege)) {
                    //  if ($privilege->view == 1) {
                    if (1 == 1) {
                        $this->rules[$groups][] = 'index';
                        $this->rules[$groups][] = 'lists';
                        $this->rules[$groups][] = 'get_data';
                        $this->rules[$groups][] = 'get_count_pns_by_unor';
                        $this->rules[$groups][] = 'get_count_non_pns_by_unor';
                        $this->rules[$groups][] = 'get_absen_enroll';
                        $this->rules[$groups][] = 'report';
                        $this->rules[$groups][] = 'report_excel';
                        $this->rules[$groups][] = 'template_sipd';
                        $this->rules[$groups][] = 'is_unique_username';
                    }
                    //  if ($privilege->create == 1) {
                    if (1 == 1) {
                        $this->_created         = 1;
                        $this->rules[$groups][] = 'add';
                        $this->rules[$groups][] = 'select_jam_kerja';
                        $this->rules[$groups][] = 'add_tanggal_pelantikan';
                        $this->rules[$groups][] = 'action';
                        $this->rules[$groups][] = 'uploadDOC';
                        $this->rules[$groups][] = 'process_pending';
                    }
                    // if ($privilege->update == 1) {
                         if (1 == 1) {
                        $this->_updated         = 1;
                        $this->rules[$groups][] = 'edit';
                        $this->rules[$groups][] = 'upload_absen';
                    }
                    if ($privilege->delete == 1) {
                        $this->_deleted         = 1;
                        $this->rules[$groups][] = 'delete';
                    }
                }
            }
            if (!isset($this->rules[$groups])) {
                $this->rules[$groups] = array();
            }
            $rules = $this->rules[$groups];
            if (!empty($rules)) {
                if (in_array($method, $rules)) {
                    if (method_exists($this, $method)) {
                        return call_user_func_array(array($this, $method), $params);
                    }
                    show_404();
                    return;
                }
            }
            $data['message'] = 'You have no privilege to access it!';
            $this->render('error', $data);
        } else {
            if (method_exists($this, $method)) {
                return call_user_func_array(array($this, $method), $params);
            }

            show_404();
            return;
        }
    }

    public function __construct()
    {
        parent::__construct();

        //Uncomment For maintenance only
        // if(!is_null(get_session('id_groups')) && get_session('id_groups') != '5') {
        //     redirect("under-maintenance");
        // }

        $this->load->model('global_model');
        $this->load->model('menu_model');
        $this->load->model('privilege_model');
        $this->ci       = &get_instance();
        $this->now      = date('Y-m-d H:i:s');
        $this->is_login = $this->session->userdata('is_login');

        $dbkinerja  = get_config_item('dbkinerja');
        $dbpresensi = get_config_item('dbpresensi');

        $this->svc = get_config_item('svc');

        $selected_db = get_session('akses_login') == 1 ? $dbkinerja : $dbpresensi;
        set_session(['selected_db' => $selected_db]);
        $this->global_model->set_table("{$selected_db}.users");

        $groups_kepegawaian = [2, 6, 13];

        if (in_array(get_session('akses_login'), $groups_kepegawaian)) { //KEPEGAWAIAN AKA PRESENSI
            $this->_user_login = $this->global_model->all(
                array(
                    'fields'    => 'users.*, unit_profiles.nama_unit, unit_profiles.unor, users.nip as PNS_PNSNIP',
                    'left_join' => array(
                        "{$selected_db}.unit_profiles" => 'unit_profiles.unit_id = users.unit',
                    ),
                    'where'     => array(
                        'users.id' => $this->session->userdata('id_users'),
                    ),
                ),
                false
            );
        } else { //PNS AKA KINERJA
            $this->_user_login = $this->global_model->all(
                array(
                    'fields'      => "users.*, pns.id as id_pns, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, ' . '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, ' . '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                    pns.PNS_UNOR AS unor, pns.PNS_PHOTO AS photo, master_kelas_jabatan.nama_jabatan, master_unit_organisasi.unit_organisasi, gol.NM_GOL, gol.NM_PKT,
                    IF(pnsa.PNS_GLRDPN IS NOT NULL AND pnsa.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pnsa.PNS_GLRDPN, ' . '), CONCAT(pnsa.PNS_PNSNAM, CONCAT(', ', pnsa.PNS_GLRBLK))), IF(pnsa.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pnsa.PNS_GLRDPN, ' . '), pnsa.PNS_PNSNAM), IF(pnsa.PNS_GLRBLK IS NOT NULL, CONCAT(pnsa.PNS_PNSNAM, CONCAT(', ', pnsa.PNS_GLRBLK)), pnsa.PNS_PNSNAM))) as PNS_NAMA_ATASAN, pns.ID_TIPE_PEGAWAI",
                    'left_join'   => array(
                        "{$dbpresensi}.pns"                    => 'pns.PNS_PNSNIP = users.nip',
                        "{$dbpresensi}.master_kelas_jabatan"   => 'master_kelas_jabatan.id = pns.id_master_kelas_jabatan',
                        "{$dbpresensi}.master_unit_organisasi" => 'master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi',
                        "{$dbpresensi}.gol"                    => 'gol.KD_GOL = pns.PNS_GOLRU',
                        "{$dbkinerja}.pns_atasan"              => 'pns_atasan.PNS_PNSNIP = pns.PNS_PNSNIP',
                        "{$dbpresensi}.pns pnsa"               => 'pnsa.PNS_PNSNIP = pns_atasan.pns_atasan',
                    ),
                    'where_false' => array(
                        'users.id' => $this->session->userdata('id_users'),
                        // 'pns.ID_MASTER_JABATAN_GURU IS NULL' => '',
                    ),
                ),
                false
            );
        }

        $this->_app_title      = !is_null($this->config->item('app_title')) && !empty($this->config->item('app_title')) ? $this->config->item('app_title') : '<b>Ardra</b>ATS';
        $this->_app_title_sort = !is_null($this->config->item('app_title_sort')) && !empty($this->config->item('app_title_sort')) ? $this->config->item('app_title_sort') : '<b>Ardra</b>ATS';
    }

    public function check_validation($level_logged = 'dashboard')
    {
        if (!is_null($level_logged) && $this->is_login) {
            $akses_login = $this->session->userdata('akses_login');

            if ($akses_login == 1 || $akses_login == 2) { //pns atau kepegawaian
                $redirect = 'dashboard';
            } else {
                $redirect = 'login';
            }
            if ($redirect != $level_logged || !$this->_user_login) {
                $this->session->set_flashdata('message', array('message' => 'You don\'t allowed to access..', 'class' => 'alert-info'));
                redirect(site_url());
            }
        } else {
            $this->session->set_flashdata('message', array('message' => 'You must login to continue..', 'class' => 'alert-info'));
            redirect(site_url());
        }
    }

    public function render($page, $data = null)
    {
        $reflect    = new ReflectionClass($this);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $prop) {
            $data[$prop->name] = $this->{$prop->name};
        }

        $data['_main_content'] = $this->load->view($page, $data, true);
        // $this->output->enable_profiler(true);
        $this->load->view($this->layout, $data);
    }

    public function parent_menu()
    {
        $dbkinerja = get_config_item('dbkinerja');
        if (get_session('id_groups') == '3') {
            $nips       = $this->_user_login->PNS_PNSNIP;
            $sql_atasan = "SELECT * FROM $dbkinerja.pns_atasan WHERE pns_atasan = '$nips'";
            if ($this->db->query($sql_atasan)->result() == null) {
                $sql = "SELECT id, title, url, icon, `order` FROM (SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu WHERE menu.url = 'dashboard/#' AND menu.id NOT IN (64) UNION SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu JOIN privilege ON menu.id = privilege.id_menu WHERE privilege.id_groups = {$this->session->userdata('id_groups')} AND privilege.view = 1 AND menu.id_parent = 0) result ORDER BY `order` ASC";
            } else {
                $sql = "SELECT id, title, url, icon, `order` FROM (SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu WHERE menu.url = 'dashboard/#' UNION SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu JOIN privilege ON menu.id = privilege.id_menu WHERE privilege.id_groups = {$this->session->userdata('id_groups')} AND privilege.view = 1 AND menu.id_parent = 0) result ORDER BY `order` ASC";
            }
        } else {
            $sql = "SELECT id, title, url, icon, `order` FROM (SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu WHERE menu.url = 'dashboard/#' UNION SELECT menu.id, menu.title, menu.url, menu.icon, menu.order FROM menu JOIN privilege ON menu.id = privilege.id_menu WHERE privilege.id_groups = {$this->session->userdata('id_groups')} AND privilege.view = 1 AND menu.id_parent = 0) result ORDER BY `order` ASC";
        }
        return $this->db->query($sql)->result();
    }

    public function has_child_menu($id)
    {
        $child_menu = $this->menu_model->all(
            array(
                'fields'   => 'menu.*, privilege.view',
                'join'     => array('privilege' => 'privilege.id_menu = menu.id'),
                'where'    => array(
                    'privilege.id_groups' => $this->session->userdata('id_groups'),
                    'privilege.view'      => 1,
                    'menu.id_parent'      => $id,
                ),
                'order_by' => 'menu.order asc',
            )
        );
        if ($child_menu) {
            return $child_menu;
        } else {
            return null;
        }
    }

    public function getSalt()
    {
        $this->load->helper('string');
        return strtolower(random_string('alnum', 10));
    }

    public function makeRequest($requireOption)
    {
        try {
            $client       = new Client();
            $guzzleResult = $client->request($requireOption['method'], $requireOption['url'], ['headers' => $requireOption['headers'], 'json' => $requireOption['body']]);
            $response     = json_decode($guzzleResult->getBody(), true);
            return $requireOption['returnArray'] == true ? json_decode(json_encode($response)) : json_encode($response);
        } catch (ClientException $e) {
            $guzzleResult = $e->getResponse();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $guzzleResult = $e->getResponse();
            }
        }
        $code = $guzzleResult->getStatusCode();
        $data = json_decode($guzzleResult->getBody());

        if (empty($data)) {
            if (!empty($this->statusMessage[$guzzleResult->getStatusCode()])) {
                $message = $this->statusMessage[$guzzleResult->getStatusCode()];
            } else {
                $message = 'An error occurred';
            }
        } else {
            $message = $data->message;
        }

        $response = [
            'code'    => $code,
            'message' => $message,
            'data'    => [],
        ];

        return $requireOption['returnArray'] == true ? $data : json_encode($response);
    }
}
