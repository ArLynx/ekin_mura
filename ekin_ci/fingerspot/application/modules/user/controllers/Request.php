<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('level_user/level_user_model','level_user_model');
	}

	function get_data_user(){
        $level = decrypt_data($this->iget('level',true));
        $temp_wh = array();
        if(!empty($level)){
            $temp_wh['level_user_id'] = $level;
        }
        $data_user = $this->user_model->get(
            array(
                "join"=>array(
                    "level_user"=>"level_user_id=id_level_user"
                ),
                "where"=>$temp_wh
            )
        );

        $templist = array();
        foreach($data_user as $key=>$row){
            foreach($row as $keys=>$rows){
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_user);
        }

        $data = $templist;
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}
