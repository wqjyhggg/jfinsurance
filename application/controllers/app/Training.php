<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Training extends CI_Controller
{
  public $error;
  public $data;

  /**
   * Search All Training
   * default will list only started record. with all flag, it will list all record
   */
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
    $this->load->model("training_model");
    $start = intval($this->input->post("start"));
    $limit = intval($this->input->post("limit"));
    $para = $this->input->post();
    if (empty($start)) $start = 0;
    if (empty($limit)) $limit = 5;
    $data = array();
    $data["trainings"] = $this->training_model->search($para, $limit, $start);
    $data["totals"] = $this->training_model->search_total($para);
    $this->app_model->return_ok($data);
  }

  public function detail()
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
    $this->load->model("training_model");

    $data["training"] = $this->training_model->get_by_id($this->input->post('training_id'));
    $this->app_model->return_ok($data);
  }

  public function update()
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

    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("no premission");
    }
    $this->load->model("training_model");
    $data = array();
    if ($id = $this->input->post("training_id")) {
      if ($this->training_model->get_by_id($id)) {
        $this->training_model->update($id, $this->input->post());
      }
    } else {
      $id = $this->training_model->add($this->input->post());
    }
    $data["training"] = $this->training_model->get_by_id($id);
    $this->app_model->return_ok($data);
  }

  public function readed()
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

    $this->load->model("training_user_model");
    $data = array();
    $training_id = $this->input->post("training_id");
    $user_id = $this->input->post("user_id");
    if (empty($training_id) || empty($user_id)) {
      return $this->app_model->return_error("Perameter error");
    }
    $this->training_user_model->set_read($user_id, $training_id);
    $data["training_id"] = $training_id;
    $data["user_id"] = $user_id;
    $this->app_model->return_ok($data);
  }

  public function getreaded()
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

    $this->load->model("training_user_model");
    $data = array();
    $user_id = $this->input->post("user_id");
    if (empty($user_id)) {
      return $this->app_model->return_error("Perameter error");
    }
    $training = $this->training_user_model->get_user_training_ids($user_id);
    $data["training"] = $training;
    $data["user_id"] = $user_id;
    $this->app_model->return_ok($data);
  }

	public function deletefile() {
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
    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("Unknown agent");
    }
    $filename = $this->input->post("filename");
    if (file_exists(UPLOADDIR.$filename)) {
      unlink(UPLOADDIR.$filename);
    }
    $this->app_model->return_ok(array());
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
    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("Unknown agent");
    }

		if (!empty($_FILES)) {
			$this->load->library('upload');
			$this->load->helper('url');
			if (!empty($_FILES['uploadfile']) && !empty($_FILES['uploadfile']['tmp_name'])) {
				$para = array(
						'allowed_types' => 'gif|jpg|png|pdf', 
						'upload_path' => UPLOADDIR,
						'file_ext_tolower' => TRUE
				);
				$this->upload->initialize($para);
				if (!$this->upload->do_upload('uploadfile')) {
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
					$data = array("imagefile" => base_url("upload/") . $filedata['file_name']);
          $this->app_model->return_ok($data);
				}
			}
		}
    return $this->app_model->return_error("Upload failur");
	}
}
