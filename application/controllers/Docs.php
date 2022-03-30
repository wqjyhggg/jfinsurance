<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Docs extends MY_Controller
{
  public $uploadtype = array('pdf', 'doc', 'docx', 'xls', 'xlsx');

  public function index()
  {
    if ($this->input->post()) {
      $policy = preg_replace("/[^0-9A-Z]/", "", strtoupper($this->input->post('policy')));
      if ($policy) {
        $this->load->model('plan_model');

        if ($plan = $this->plan_model->get_plan_by_policy($policy)) {
          $this->data['filelist'] = array();
          if ($handle = opendir(DOWNLOADDIR)) {
            while (($file = readdir($handle)) !== false) {
              if (preg_match("/".$plan["product_short"]."_/", $file)) {
                if ($file == "JFR_Policy.pdf") {
                  if ($plan["apply_date"] <= "2018-05-31") {
                    $file = "JFRV1_Policy.pdf";
                  } else if ($plan["apply_date"] <= "2019-03-02") {
                    $file = "JFRV2_Policy.pdf";
                  } else if ($plan["apply_date"] <= "2019-07-25") {
                    $file = "JFRV3_Policy.pdf";
                  }
                } else if ($file == "JES_Policy.pdf") {
                  if ($plan["apply_date"] <= "2021-08-15") {
                    $file = "JESV1_Policy.pdf";
                  }
                }
		if (preg_match("/Claim_Form.pdf/i", $file) || preg_match("/Policy.pdf/i", $file)) {
                  $this->data['filelist'][] = $file;  // base_url("pdf/download/". $file);
                }
              }
            }
            asort($this->data['filelist']);
          }
        } else {
          $this->data['error_message'] = "Can't find the policy";
        }
      }
    }
    $this->data['action_url'] = current_url();
    $this->data['csrf'] = array(
      'name' => $this->security->get_csrf_token_name(),
      'value' => $this->security->get_csrf_hash()
    );
    $this->load->view('docs', $this->data);
  }
}
