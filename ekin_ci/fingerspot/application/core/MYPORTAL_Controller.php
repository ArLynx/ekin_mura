<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class MYPORTAL_Controller extends CI_Controller
{

    protected $title_main;
    protected $header_main = 'template_portal/header';
    protected $index_main = 'template_portal/main';
    protected $footer_main = 'template_portal/footer';

    function __construct()
    {
        parent::__construct();
    }

    public function execute($page,$data = array()){
        $data['title_main'] = $this->config->item('APP_TITLE');
        $data['header_main'] = $this->load->view($this->header_main,$data,true);
        $data['footer_main'] = $this->load->view($this->footer_main,$data,true);
        $data['content_main'] = $this->load->view($page,$data,true);
        $this->load->view($this->index_main,$data);
    }

    public function page_error(){
        redirect('404_override');
    }
}