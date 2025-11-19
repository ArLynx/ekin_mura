<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Absen extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('absen_model');
    }
	
	public function devicescan_get()
    {
		$sn = $this->get("sn");
		$tanggal = $this->get("tanggal");
		$waktu = $this->get("waktu");
		
		$tanggal_waktu = $tanggal." ".$waktu;
		
        $data_logscan = $this->absen_model->get(
			array(
				"where"=>array(
					"sn"=>$sn
				),
				"where_false"=>"scan_date > '".$tanggal." ".$waktu."'",
				"order_by"=>array(
					"scan_date"=>"ASC"
				)
			)
		);
		
		if($data_logscan){
			$this->response([
					"data"=>$data_logscan
				],REST_Controller::HTTP_OK);
		}else{
			$this->response([
					"data"=>array()
				],REST_Controller::HTTP_OK
			);
		}
        
    }
	
	public function uploadscan_post()
    {
		$sn = $this->post("sn");
		$scan_date = $this->post("scan_date");
		$pin = $this->post("pin");
		$verify_mode = $this->post("verify_mode");
		$io_mode = $this->post("io_mode");
		
		echo $sn;
		
		/* $tanggal_waktu = $tanggal." ".$waktu;
		
        $data_logscan = $this->absen_model->get(
			array(
				"where"=>array(
					"pin"=>$pin
				),
				"where_false"=>"scan_date > '".$tanggal." ".$waktu."'",
				"order_by"=>array(
					"scan_date"=>"ASC"
				)
			)
		);
		
		if($data_logscan){
			$this->response([
					"data"=>$data_logscan
				],REST_Controller::HTTP_OK);
		}else{
			$this->response([
					"data"=>array()
				],REST_Controller::HTTP_OK
			);
		} */
        
    }

}
