<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller
{

    protected $title_main;
    protected $header_main = 'template_admin/header';
    protected $sidebar_main = 'template_admin/sidebar';
    protected $index_main = 'template_admin/main';
    protected $footer_main = 'template_admin/footer';

    function __construct()
    {
        parent::__construct();
        $this->load->model('menu/menu_model','menu_model');
        $this->load->model('privilage_level/privilage_level_model','privilage_level_model');
    }

    public function _remap($method,$params){
        $class_name = $this->router->class;
        $level_user_id = $this->session->userdata('level_user_id');
        if($class_name != "request"){
            if($class_name != "Custom404"){
                $menu = $this->menu_model->get(
                    array(
                        "where"=>array(
                            "nama_class"=>$class_name
                        )
                    ),"row"
                );
    
                $check_privilage = $this->privilage_level_model->get(
                    array(
                        "where"=>array(
                            "level_user_id"=>$level_user_id,
                            "menu_id"=>$menu->id_menu
                        )
                    ),"row"
                );
                if($check_privilage->view_content != 1){
                    $this->page_error();
                }   
            }
    
            if (method_exists($this, $method))
            {
                return call_user_func_array(array($this, $method), $params);
            }
        }else{
            if (method_exists($this, $method))
            {
                return call_user_func_array(array($this, $method), $params);
            }
            $this->page_error();
        }
    }

    public function menu($parent_id = 0,$level_user_id){
        $str = "";
        $master = $this->menu_model->query("SELECT id_menu,nama_menu,nama_module,nama_class,class_icon,IFNULL(a.jml_child,0) AS jml_child FROM `menu` LEFT JOIN (SELECT COUNT(*) AS jml_child, id_parent_menu FROM menu WHERE `menu`.`deleted_at` IS NULL GROUP BY id_parent_menu) AS a ON a.id_parent_menu=id_menu WHERE menu.`id_parent_menu` = ".$parent_id." AND `menu`.`deleted_at` IS NULL AND id_menu IN (SELECT menu_id FROM privilage_level_menu WHERE level_user_id  = ".$level_user_id." AND view_content = 1) ORDER BY order_menu")->result_array();

        for($i=0;$i<count($master);$i++){
            $child = "";
            $link = "";
            $li_class = "nav-item";
            $a_class = "nav_link";
            $icon_class = "";
            if($parent_id == 0){
                if($master[$i]['jml_child'] == 0){
                    $link = "<a href='".site_url($master[$i]['nama_module'])."' class='nav-link'><i class='".$master[$i]['class_icon']."'></i><span>".$master[$i]['nama_menu']."</span></a>";
                }else{
                    $li_class .= " nav-item-submenu";
                    $link = "<a href='#' class='nav-link'><i class='".$master[$i]['class_icon']."'></i><span>".$master[$i]['nama_menu']."</span></a>";
                    $child = "<ul class='nav nav-group-sub'>".$this->menu($master[$i]['id_menu'],$level_user_id)."</ul>";
                }
            }else{
                if($master[$i]['jml_child'] == 0){
                    $child = "<a href='".site_url($master[$i]['nama_module'])."' class='nav-link'>".$master[$i]['nama_menu']."</a>";
                }else{
                    $li_class .= " nav-item-submenu";
                    $link = "<a href='#' class='nav-link'><span>".$master[$i]['nama_menu']."</span></a>";
                    $child = "<ul class='nav nav-group-sub'>".$this->menu($master[$i]['id_menu'],$level_user_id)."</ul>";
                }
            }

            $str .= "<li class='".$li_class."'>".$link;
            $str .= $child;
            $str .= "</li>";
        }

        return $str;
    }

    public function execute($page,$data = array()){
        $CI =& get_instance();
        $CI->load->library('session');
        if($CI->session->userdata("is_logged_in")){
            $level_user_id = $CI->session->userdata('level_user_id');
            $data['sidebar'] = $this->menu(0,$level_user_id);
            $data['title_main'] = $this->config->item('APP_TITLE');
            $data['header_main'] = $this->load->view($this->header_main,$data,true);
            $data['sidebar_main'] = $this->load->view($this->sidebar_main,$data,true);
            $data['footer_main'] = $this->load->view($this->footer_main,$data,true);
            $data['content_main'] = $this->load->view($page,$data,true);
            $this->load->view($this->index_main,$data);
        }else{
            redirect("Login");
        }
    }

    public function ipost($name = ""){
        return $this->input->post($name,true);
    }

    public function iget($name = ""){
        return $this->input->get($name,true);
    }

    public function datetime(){
        return $this->config->item('date_now');
    }

    public function upload_file($name_field = "",$upload_path = "",$type_upload = "image"){
        if((!file_exists($upload_path)) && !(is_dir($upload_path))){
            mkdir($upload_path);
        }
        $config = array();
        $status = "";
        if($type_upload == 'image'){
            $filename = md5(uniqid(rand(), true));
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name'] = $filename;
        }else{
            $filename = md5(uniqid(rand(), true));
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'docx|pdf|xlsx|ppt';
            $config['file_name'] = $filename;
        }

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($name_field))
        {
                $status = array('error' => $this->upload->display_errors());
        }
        else
        {
                $status = array('data' => $this->upload->data());
        }

        return $status;
    }

    public function page_error(){
        redirect('404_override');
    }
}