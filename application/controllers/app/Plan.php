<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Plan extends CI_Controller
{
  public $error;
  public $data;

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

    $data = array();
    $post = $this->input->post();
    if ($user["group_id"] > 100) {
      $post["user_id"] = $user["user_id"];
    }
    $this->load->model("plan_model");
    $limit = $this->input->post("limit");
    $start = $this->input->post("start");
    if ($plans = $this->plan_model->plan_activities($post, $limit, $start)) {
      $data["plans"] = $plans;
      $this->app_model->return_ok($data);
    }
    return $this->app_model->return_error("Can't find plan");
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

    $data = array();
    $plan_id = $this->input->post("plan_id");
    $this->load->model("plan_model");
    if ($id = $this->input->post("plan_id")) {
      if ($plan = $this->plan_model->get_by_id($id)) {
        if (($user["group_id"] > 100) && ($plan["user_id"] != $user["user_id"])) {
          return $this->app_model->return_error("Can't find plan");
        }
        $data["plan"] = $this->plan_model->get_by_id($id);
        $this->app_model->return_ok($data);
      }
    }
    return $this->app_model->return_error("Can't find plan");
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

    if ($user["group_id"] > 100) {
      return $this->app_model->return_error($this->error);
    }
    $this->load->model("plan_model");
    $data = array();
    if ($id = $this->input->post("plan_id")) {
      if ($this->plan_model->get_by_id($id)) {
        $this->plan_model->update($id, $this->input->post());
      }
    } else {
      $id = $this->plan_model->add($this->input->post());
    }
    $data["plan"] = $this->plan_model->get_by_id($id);
    $this->app_model->return_ok($data);
  }

	public function quote() {
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

    if ($user["group_id"] > 100) {
      return $this->app_model->return_error($this->error);
    }
    $this->load->model("plan_model");
    $data = array();

    $post = $this->input->post();
    $this->load->model('product_model');
    if ($post['product_short'] == 'TOP') {
      $post['totaldays'] = $post['total_days'];
      if ($premium = $this->product_model->get_top_quote($post)) {
        $data['premiumArr'] = $premium;
      }
    } else {
      if ($premium = $this->product_model->get_premium_sub($post)) {
        $data['premiumArr'] = $premium;
      }
    }
		if (empty($data['premiumArr']) {
      if (empty($this->error)) {
			  $this->error = "Can't get premium";
      }
      return $this->app_model->return_error($this->error);
		}
		return $this->app_model->return_ok($data);
	}
}
