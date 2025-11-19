<?php
# @Author: Awan Tengah
# @Date:   2017-01-07T20:22:10+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2018-12-18T11:09:41+07:00

defined('BASEPATH') or exit('No direct script access allowed');

class Upload_file
{

    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function upload($name, $path, $type, $width = null, $height = null, $thumb = false, $ratio = false, $redirect_url = false)
    {
        $config['upload_path'] = $path;
        if ($type === 'image') {
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size']      = '5120';
        } else if ($type === 'file') {
            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
            $config['max_size']      = '10240';
        }
        $config['encrypt_name'] = true;
        $this->ci->load->library('upload');
        $this->ci->upload->initialize($config);

        if (!$this->ci->upload->do_upload($name)) {
            $error = $this->ci->upload->display_errors();
            if ($redirect_url != false) {
                error_upload_message($redirect_url, $error);
            } else {
                var_dump($error);die;
            }
        } else {
            $upload = $this->ci->upload->data();
            if (!is_null($width) && !is_null($height)) {
                if ($type === 'image') {
                    // Image manipulation
                    $this->ci->load->library('image_lib');

                    $config_resize['image_library']  = 'gd2';
                    $config_resize['source_image']   = $upload['full_path'];
                    $config_resize['create_thumb']   = $thumb;
                    $config_resize['maintain_ratio'] = $ratio;
                    $config_resize['width']          = $width;
                    $config_resize['height']         = $height;

                    $this->ci->image_lib->clear();
                    $this->ci->image_lib->initialize($config_resize);
                    if (!$this->ci->image_lib->resize()) {
                        $error = $this->ci->image_lib->display_errors();
                        if ($redirect_url != false) {
                            error_upload_message($redirect_url, $error);
                        }
                    }
                }
            }

            $filename = $upload['file_name'];
            return $filename;
        }
    }

}
