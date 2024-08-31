<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Userhome extends CI_Controller
{
  public $error;

  public function index()
  {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }
    if ($bid = $this->input->post("bid")) {
      if ($user = $this->user_model->get_by_id($bid)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }
    $beuser = $user;
		$this->load->model('user_home_model');
    $do = $this->input->post("do");
    $paras = $this->input->post("paras");
		$user_id = $beuser["user_id"];
    if (($beuser["user_group_id"] < 100) && ($this->input->post("user_id"))) {
      $user_id = $this->input->post("user_id");
    }
    if (empty($user_id)) {
      return $this->app_model->return_error("Missing parameter");
    }
    if ($do == 'save') {
      $this->user_home_model->save($user_id, $notify_type);
    }
    
    $data["user_id"] = $user_id;
    $data["paras"] = "";
    if ($h = $this->user_home_model->get_by_id($user_id)) {
      $data["paras"] = $h["paras"];
    }
    $this->app_model->return_ok($data);
  }

  public function image($fname) {
    $fullname = DOWNLOADDIR."userhome/".$fname;
    if (file_exists($fullname)) {
      $extension = pathinfo($fname, PATHINFO_EXTENSION);
      $mimeTypes = [
          'jpg' => 'image/jpeg',
          'jpeg' => 'image/jpeg',
          'png' => 'image/png',
          'gif' => 'image/gif',
          'bmp' => 'image/bmp',
          'svg' => 'image/svg+xml',
          // Add more file extensions and their corresponding MIME types as needed
      ];

      $mimetype = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';

      header('Content-Type: '.$mimetype); // MIME type
      header('Content-Length: ' . filesize($fullname));

      // Output the image data
      readfile($fullname);
      exit;      
    }
    show_404();
  }

	public function upload() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Timeout";
      }
      return $this->app_model->return_error($this->error);
    }
    if ($bid = $this->input->post("bid")) {
      if ($user = $this->user_model->get_by_id($bid)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }
    $beuser = $user;
		$user_id = $beuser["user_id"];
    if (($beuser["user_group_id"] < 100) && ($this->input->post("user_id"))) {
      $user_id = $this->input->post("user_id");
    }
    if (empty($user_id)) {
      return $this->app_model->return_error("Missing parameter");
    }

		$imagefile = '';
		if (!empty($_FILES)) {
			$this->load->library('upload');
			if (!empty($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
				$imgfile = "u".$user_id;
				$para = array(
						'allowed_types' => 'gif|jpg|png', 
						'file_name' => $imgfile,
						'upload_path' => DOWNLOADDIR."userhome/",
						'file_ext_tolower' => TRUE
				);
				$this->upload->initialize($para);
				if (!$this->upload->do_upload('image')) {
          return $this->app_model->return_error($this->error);
        } else {
					$filedata = $this->upload->data();
					/*
					[file_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf33.png
					[file_type] => image/png
					[file_path] => /home/jackw/Public/jfgroup/agentinfo/
					[full_path] => /home/jackw/Public/jfgroup/agentinfo/logeccbc87e4b5ce2fe28308fd9f2a7baf33.png
					[raw_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf33
					[orig_name] => logeccbc87e4b5ce2fe28308fd9f2a7baf3.png
					[client_name] => 2.PNG
					[file_ext] => .png
					[file_size] => 296.71
					[is_image] => 1
					[image_width] => 1409
					[image_height] => 974
					[image_type] => png
					[image_size_str] => width="1409" height="974"d
					*/	
					$data = array("imagefile" => base_url("userhome/image/") . $filedata['file_name']);
          $this->app_model->return_ok($data);
				}
			}
		}
    return $this->app_model->return_error("Upload failur");
	}
}
