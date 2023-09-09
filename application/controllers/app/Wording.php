<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wording extends CI_Controller
{
  public $error;
  public $data;

  /**
   * Search All Word definition
   * 
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
    $this->load->model("wording_model");
    $data = array();
    $data["wording"] = $this->wording_model->getAll();
    $this->app_model->return_ok($data);
  }

  /**
   * Search All Word definition
   * 
   */
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
    $word = $this->input->post("word");
    $desc = $this->input->post("desc");
    if (empty($word) || empty($desc)) {
      return $this->app_model->return_error("Missing Parameter");
    }

    $this->load->model("wording_model");
    $data = array();
    if ($this->wording_model->save($word, $desc)) {
      $data["wording"] = $this->wording_model->getAll();
      $this->app_model->return_ok($data);
    }
    return $this->app_model->return_error("Can't save word: ".$word." with desc: ".$desc);
  }
}
