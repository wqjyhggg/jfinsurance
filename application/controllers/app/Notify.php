<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notify extends CI_Controller
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
		$this->load->model('user_notify_model');
    $do = $this->input->post("do");
    $user_id = intval($this->input->post("user_id"));
    $notify_type = intval($this->input->post("notify_type"));
    if (empty($user_id)) {
      return $this->app_model->return_error("Missing parameter");
    }
    if ($do == 'save') {
      $this->user_notify_model->save($user_id, $notify_type);
    }
    
    $data["user_id"] = $user_id;
    $data["notify_type"] = 1;
    if ($notify = $this->user_notify_model->get_by_id($user_id)) {
      $data["notify_type"] = $notify["notify_type"];
    }
    $this->app_model->return_ok($data);
  }
}
