<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('content_model');
	}

	function get_data_content(){
        $data_content = $this->content_model->get();

        $templist = array();
        foreach($data_content as $key=>$row){
            foreach($row as $keys=>$rows){
                $templist[$key][$keys] = $rows;
            }
            $templist[$key]['id_encrypt'] = encrypt_data($row->id_content);
        }

        $data = $templist;
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
    }
}
