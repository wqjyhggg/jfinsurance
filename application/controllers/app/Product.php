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
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      if ($user = $this->user_model->get_user_by_id($bid)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $this->load->model("product_model");
    $data = array();
    $data["products"] = $this->product_model->product_list(0, $user);
    $this->app_model->return_ok($data);
  }

  function getpremium() {
    $this->error = "";
    $this->load->model("app_model");
    $this->load->model("user_model");
		$this->load->model('product_model');
    $this->load->model("plan_model");
    $user = $this->app_model->check_token($this->input->post("token"));

    if (empty($user)) {
      if (empty($this->error)) {
        $this->error = "Session Expired";
      }
      return $this->app_model->return_error($this->error);
    }
    if (($user["user_group_id"] < 100) && ($bid = $this->input->post("bid"))) {
      if ($user = $this->user_model->get_user_by_id($bid)) {
        return $this->app_model->return_error("Unknown agent");
      }
    }

    $data = array();
    $post = $this->input->post();
    if ($post['product_short'] == "TOP") {
      $premiumarr = $this->product_model->get_top_premium($post);
      if (empty($premiumarr)) {
        return $this->app_model->return_error("Unknown Error");
      } else {
        $data = array('premiumarr' => $premiumarr);
      }
    } else {
      $para = array(
        'status_id' => $post['status_id'],
        'plan_id' => $post['plan_id'],
        'product_short' => $post['product_short'],
        'apply_date' => $post['apply_date'],
        'effective_date' => $post['effective_date'],
        'expiry_date' => $post['expiry_date'],
        'isfamilyplan' => $post['isfamilyplan'],
        'sum_insured' => $post['sum_insured'],
        'deductible_amount' => $post['deductible_amount'],
        'rate_options' => $post['rate_options'],
        'stable_condition' => $post['stable_condition'],
        'holiday_rate' => $post['holiday_rate'],
        'birthday' => $post['birthday'],
        'birthdays' => $post['birthdays'],
        'spouse' => $post['spouse'],
        'number_customer' => $post['number_customer']);
      $premiumarr = $this->product_model->get_premium($para, $user);
      if (empty($premiumarr)) {
        return $this->app_model->return_error("Unknown Error");
      } else if (empty($premiumarr['premium']) && empty($premiumarr['message'])) {
        $days = 0;
        if (!empty($para['effective_date']) && !empty($para['expiry_date'])) {
          $days = $this->product_model->getDays($para['effective_date'], $para['expiry_date']);
        }
        if ($days > 0) {
          return $this->app_model->return_error("Unknown Days");
        } else {
          return $this->app_model->return_error("Unknown Error");
        }
      } else {
        //$premiumarr['premium'] = number_format($premiumarr['premium'], 2, '.', ',');
        $data = array('premiumarr' => $premiumarr);
      }
    }
    return $this->app_model->return_ok($data);
	}
}
