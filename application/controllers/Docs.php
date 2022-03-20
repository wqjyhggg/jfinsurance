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
      $policy = preg_replace("/[^1-9A-Z]/", "", strtoupper($this->input->post('policy')));
      if ($policy) {
        $this->load->model('plan_model');

        if ($plan = $this->plan_model->get_plan_by_policy($policy)) {
          $this->data['filelist'] = array();
          if ($handle = opendir(DOWNLOADDIR)) {
            while (($file = readdir($handle)) !== false) {
              if (preg_match("/".$plan["product_short"]."/", $file)) {
                $this->data['filelist'][] = $file;  // base_url("pdf/download/". $file);
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
