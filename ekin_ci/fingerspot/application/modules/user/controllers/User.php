<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('level_user/level_user_model','level_user_model');
	}

	public function index()
	{
        $data['selected_level'] = "";
        $temp_wh = array();
        if(!empty($_POST)){
            $data['selected_level'] = decrypt_data($this->ipost('level_search'));
            $temp_wh['user.level_user_id'] = decrypt_data($this->ipost('level_search'));
        }

		$data['list_user'] = $this->user_model->get(
            array(
                'where'=>$temp_wh,
                "join"=>array(
                    "level_user"=>"level_user_id=id_level_user"
                )
            )
        );

        $data['list_level_user'] = $this->level_user_model->get(
            array(
                'order_by'=>array(
                    'nama_level_user'=>'ASC'
                )
            )
        );

        $data['breadcrumb'] = [['link'=>false,'content'=>'User','is_active'=>true]];
        $this->execute('index',$data);
    }
    
    public function tambah_user(){
        if(empty($_POST)){
            $data['level_user'] = $this->level_user_model->get(
                array(
                    'order_by'=>array(
                        'nama_level_user'=>'ASC'
                    )
                )
            );
            $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'user','content'=>'User','is_active'=>false],['link'=>false,'content'=>'Tambah User','is_active'=>true]];
            $this->execute('form_user',$data);
        }else{

            $input_name = 'foto_user';
            $upload_foto = $this->upload_file($input_name,$this->config->item('user_path'));
            
            if(!isset($upload_foto['error'])){
                $data = array(
                    "nama_lengkap"=>$this->ipost('nama_lengkap'),
                    "username"=>$this->ipost('username'),
                    'password'=>password_hash($this->ipost('password'),PASSWORD_BCRYPT,array('cost'=>12)),
                    'level_user_id'=>$this->ipost('level_user'),
                    'foto_user'=>$upload_foto['data']['file_name'],
                    'created_at'=>$this->datetime()
                );
    
                $status = $this->user_model->save($data);
                if($status){
                    $this->session->set_flashdata('message','Data baru berhasil ditambahkan');
                }else{
                    $this->session->set_flashdata('message','Data baru gagal ditambahkan');
                }
                
            }else{
                $this->session->set_flashdata('message','Gagal upload foto');
            }

            redirect('user');
        }
    }

    public function edit_user($id_user){
        $data_master = $this->user_model->get_by(decrypt_data($id_user));

        if(!$data_master){
            $this->page_error();
        }

        if(empty($_POST)){
            $data['content'] = $data_master;
            $data['level_user'] = $this->level_user_model->get(
                array(
                    'order_by'=>array(
                        'nama_level_user'=>'ASC'
                    )
                )
            );
            $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'user','content'=>'User','is_active'=>false],['link'=>false,'content'=>'Tambah User','is_active'=>true]];
            $this->execute('form_user',$data);
        }else{

            $input_name = 'foto_user';
            $foto_user = '';
            if(!empty($_FILES['foto_user'])){
                if(file_exists($this->config->item('user_path').$data_master->foto_user)){
                    unlink($this->config->item('user_path').$data_master->foto_user);
                }
                $upload_foto = $this->upload_file($input_name,$this->config->item('user_path'));
                $foto_user = $upload_foto['data']['file_name'];
            }else{
                $foto_user = $data_master->foto_user;
            }
            
            $password = "";
            if(!empty($this->ipost('password'))){
                $password = password_hash($this->ipost('password'),PASSWORD_BCRYPT,array('cost'=>12));
            }else{
                $password = $data_master->password;
            }
            $data = array(
                "nama_lengkap"=>$this->ipost('nama_lengkap'),
                "username"=>$this->ipost('username'),
                'password'=>$password,
                'level_user_id'=>$this->ipost('level_user'),
                'foto_user'=>$foto_user,
                'updated_at'=>$this->datetime()
            );

            $status = $this->user_model->edit(decrypt_data($id_user),$data);
            if($status){
                $this->session->set_flashdata('message','Data berhasil diubah');
            }else{
                $this->session->set_flashdata('message','Data gagal diubah');
            }

            redirect('user');
        }
    }

    public function delete_user(){
        $id_user = $this->iget('id_user');
        $data_master = $this->user_model->get_by(decrypt_data($id_user));

        if(!$data_master){
            $this->page_error();
        }

        $status = $this->user_model->remove(decrypt_data($id_user));
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($status));
    }
}
