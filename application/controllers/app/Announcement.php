<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Announcement extends CI_Controller
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
    $this->load->model("announcement_model");
    $data = array();
    $data["announcements"] = $this->announcement_model->search(array(), 5);
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

    $data["announcement"] = $this->announcement_model->get_by_id($this->input->post('announcement_id'));
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
    $this->load->model("announcement_model");
    $data = array();
    if ($id = $this->input->post("announcement_id")) {
      if ($this->announcement_model->get_by_id($id)) {
        $this->announcement_model->update($id, $this->input->post());
      }
    } else {
      $id = $this->announcement_model->add($this->input->post());
    }
    $data["announcement"] = $this->announcement_model->get_by_id($id);
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
    $this->load->model("announcement_user_model");
    $announcement_id = $this->input->post("announcement_id");
    $user_id = $this->input->post("user_id");
    if (empty($announcement_id) || empty($user_id)) {
      return $this->app_model->return_error("Parameter error");
    }
    $this->announcement_user_model->set_read($announcement_id, $user_id);
    $data["announcement_id"] = $announcement_id;
    $data["user_id"] = $user_id;
    $this->app_model->return_ok($data);
  }
}
