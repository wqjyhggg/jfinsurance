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
    $search = trim($this->input->post("search"));
    if (empty($start)) $start = 0;
    if (empty($limit)) $limit = 5;
    $para = array();
    if (!empty($search)) {
      $para["title"] = $search;
      $para["desc"] = $search;
    }
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

    if ($user["user_group_id"] > 100) {
      return $this->app_model->return_error("no premission");
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
}
