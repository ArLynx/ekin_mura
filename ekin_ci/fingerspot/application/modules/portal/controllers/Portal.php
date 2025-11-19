<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends MYPORTAL_Controller {
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
        $this->execute('index');
    }
}
