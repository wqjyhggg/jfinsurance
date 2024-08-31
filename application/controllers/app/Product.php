<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
  public $error;
  public $data;

  /**
   * Search All Announcement
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
    if ($bid = $this->input->post("bid")) {
      if ($user = $this->user_model->get_by_id($bid)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $this->load->model("product_model");
    $data = array();
    $data["products"] = $this->product_model->product_list(0, $user);
    $this->app_model->return_ok($data);
  }
}
