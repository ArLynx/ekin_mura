<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privilage_level extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('privilage_level_model');
		$this->load->model('menu/menu_model','menu_model');
		$this->load->model('level_user/level_user_model','level_user_model');
	}

	public function index()
	{
        $data['breadcrumb'] = [['link'=>false,'content'=>'Privilage Level','is_active'=>true]];
        $this->execute('index',$data);
    }
    
    public function set_privilage_menu($id_level_user){
        $data_master = $this->level_user_model->get_by(decrypt_data($id_level_user));
        if(!$data_master){
            $this->page_error();
        }

        if(empty($_POST)){

            $data['menu'] = $this->menu_model->query("SELECT id_menu,nama_menu,GROUP_CONCAT(create_content) AS create_content,GROUP_CONCAT(update_content) AS update_content,GROUP_CONCAT(delete_content) AS delete_content,GROUP_CONCAT(view_content) AS view_content FROM menu
            LEFT JOIN (
                SELECT menu_id,GROUP_CONCAT(create_content) AS create_content,GROUP_CONCAT(update_content) AS update_content,GROUP_CONCAT(delete_content) AS delete_content,GROUP_CONCAT(view_content) AS view_content 
                FROM privilage_level_menu WHERE level_user_id = ".decrypt_data($id_level_user)." GROUP BY menu_id) AS a ON id_menu=a.menu_id
            WHERE deleted_at IS NULL
            GROUP BY id_menu")->result();
            
            $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'privilage_level','content'=>'Privilage Menu','is_active'=>false],['link'=>false,'content'=>'Set Privilage Menu','is_active'=>true]];
            $this->execute('form_privilage_level',$data);
        }else{
            $menu = $this->menu_model->get();
            foreach($menu as $key=>$row){
                $check_privilage = $this->privilage_level_model->get(
                    array(
                        "where"=>array(
                            "level_user_id"=>decrypt_data($id_level_user),
                            "menu_id"=>$row->id_menu
                        )
                    )
                );

                if(!$check_privilage){
                    if(isset($_POST['privilage_level'][$row->id_menu])){
                        $data_insert = array(
                            "level_user_id"=>decrypt_data($id_level_user),
                            "menu_id"=>$row->id_menu,
                            "view_content" => !isset($_POST['privilage_level'][$row->id_menu]['view'])?'0':$_POST['privilage_level'][$row->id_menu]['view'],
                            "update_content" => !isset($_POST['privilage_level'][$row->id_menu]['update'])?'0':$_POST['privilage_level'][$row->id_menu]['update'],
                            "delete_content" => !isset($_POST['privilage_level'][$row->id_menu]['delete'])?'0':$_POST['privilage_level'][$row->id_menu]['delete'],
                            "create_content" => !isset($_POST['privilage_level'][$row->id_menu]['add'])?'0':$_POST['privilage_level'][$row->id_menu]['add']
                        );
        
                        $this->privilage_level_model->save($data_insert);
                    }
                }else{
                    $data_update = array(
                        "view_content" => !isset($_POST['privilage_level'][$row->id_menu]['view'])?'0':$_POST['privilage_level'][$row->id_menu]['view'],
                            "update_content" => !isset($_POST['privilage_level'][$row->id_menu]['update'])?'0':$_POST['privilage_level'][$row->id_menu]['update'],
                            "delete_content" => !isset($_POST['privilage_level'][$row->id_menu]['delete'])?'0':$_POST['privilage_level'][$row->id_menu]['delete'],
                            "create_content" => !isset($_POST['privilage_level'][$row->id_menu]['add'])?'0':$_POST['privilage_level'][$row->id_menu]['add']
                    );

                    if(!isset($_POST['privilage_level'][$row->id_menu]['view']) && !isset($_POST['privilage_level'][$row->id_menu]['update']) && !isset($_POST['privilage_level'][$row->id_menu]['delete']) && !isset($_POST['privilage_level'][$row->id_menu]['add'])){
                        $this->privilage_level_model->remove($check_privilage->id_privilage);
                    }else{
                        $this->privilage_level_model->edit_by(array("level_user_id"=>decrypt_data($id_level_user),"menu_id"=>$row->id_menu),$data_update);
                    }
    
                }
            }
            // $data = array(
            //     "class_icon"=>$this->ipost('icon_privilage_level'),
            //     "id_parent_privilage_level"=>decrypt_data($this->ipost('parent_privilage_level')),
            //     "nama_privilage_level"=>$this->ipost('nama_privilage_level'),
            //     "nama_module"=>$this->ipost('nama_module'),
            //     "nama_class"=>$this->ipost('nama_class'),
            //     "order_privilage_level"=>$this->ipost('order_privilage_level'),
            //     'updated_at'=>$this->datetime()
            // );

            // $status = $this->privilage_level_model->edit(decrypt_data($id_privilage_level),$data);
            // if($status){
            //     $this->session->set_flashdata('message','Data berhasil diubah');
            // }else{
            //     $this->session->set_flashdata('message','Data gagal diubah');
            // }

            redirect('privilage_level');
        }
    }

    public function delete_privilage_level($id_privilage_level){
        $data_master = $this->privilage_level_model->get_by(decrypt_data($id_privilage_level));

        if(!$data_master){
            $this->page_error();
        }

        $status = $this->privilage_level_model->remove(decrypt_data($id_privilage_level));
        if($status){
            $this->session->set_flashdata('message','Data berhasil dihapus');
        }else{
            $this->session->set_flashdata('message','Data gagal dihapus');
        }
        redirect('privilage_level');
    }
}
