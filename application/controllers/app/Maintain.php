<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Maintain extends CI_Controller
{
  public $white_list=["54.173.205.174", "52.205.81.107", "54.156.255.140", "127.0.0.1"];
  public $data;

  /**
   * Search All Announcement
   * default will list only started record. with all flag, it will list all record
   */
  public function index()
  {
		$ip = $_SERVER['REMOTE_ADDR'];
		if (!in_array($ip, $this->white_list)) {
			show_404();
		}
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("maintain_model");
    $rec = $this->maintain_model->get_first();

    if (empty($rec) || ($rec["active"] != 1)) {
      return $this->app_model->return_error("no data");
    }
    $data = array();
		if ($this->maintain_model->get_available()) {
			$data["active"] = 1;
		} else {
			$data["active"] = 0;
		}
		$data["start_time"] = $rec["start_time"];
		$data["end_time"] = $rec["end_time"];
		$data["reason"] = $rec["reason"];
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

    if ($user["user_group_id"] != 1) {
      return $this->app_model->return_error("no premission");
    }
    $this->load->model("maintain_model");
		if ($this->input->post("isupdate")) {
			$data = array();
			if ($rc = $this->maintain_model->get_first()) {
				$this->maintain_model->update($rc["maintain_id"], $this->input->post());
			} else {
				$this->maintain_model->add($this->input->post());
			}
		}
    $data["maintain"] = $this->maintain_model->get_first();
    $this->app_model->return_ok($data);
  }
}
