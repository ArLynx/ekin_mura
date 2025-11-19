<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('content_model');
	}

	public function index()
	{
        $data['list_content'] = $this->content_model->get();

        $data['breadcrumb'] = [['link'=>false,'content'=>'Content','is_active'=>true]];
        $this->execute('index',$data);
    }
    
    public function tambah_content(){
        if(empty($_POST)){
            $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'content','content'=>'Content','is_active'=>false],['link'=>false,'content'=>'Tambah Content','is_active'=>true]];
            $this->execute('form_content',$data);
        }else{

            $data = array(
                "judul"=>$this->ipost('judul'),
                "description"=>htmlentities($this->ipost('content')),
                'created_at'=>$this->datetime()
            );

            $status = $this->content_model->save($data);
            if($status){
                $this->session->set_flashdata('message','Data baru berhasil ditambahkan');
            }else{
                $this->session->set_flashdata('message','Data baru gagal ditambahkan');
            }

            redirect('content');
        }
    }

    public function edit_content($id_content){
        $data_master = $this->content_model->get_by(decrypt_data($id_content));

        if(!$data_master){
            $this->page_error();
        }

        if(empty($_POST)){
            $data['content'] = $data_master;
            $data['breadcrumb'] = [['link'=>true,'url'=>base_url().'content','content'=>'Content','is_active'=>false],['link'=>false,'content'=>'Tambah Content','is_active'=>true]];
            $this->execute('form_content',$data);
        }else{

            $data = array(
                "judul"=>$this->ipost('judul'),
                "description"=>htmlentities($this->ipost('content')),
                'updated_at'=>$this->datetime()
            );

            $status = $this->content_model->edit(decrypt_data($id_content),$data);
            if($status){
                $this->session->set_flashdata('message','Data berhasil diubah');
            }else{
                $this->session->set_flashdata('message','Data gagal diubah');
            }

            redirect('content');
        }
    }

    public function delete_content(){
        $id_content = $this->iget('id_content');
        $data_master = $this->content_model->get_by(decrypt_data($id_content));

        if(!$data_master){
            $this->page_error();
        }

        $status = $this->content_model->remove(decrypt_data($id_content));
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($status));
    }
}
