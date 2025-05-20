<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends MY_Controller {

	/**
	 * Index Page for this controller.
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
		
		$this->load->model('product_model');
    // return $this->app_model->return_error("Missing parameter");

		$downloads_url = base_url('pdf/download') . "/";
		$file_url = array();
		$product_list = $this->product_model->product_list(1, $user);
		ksort($product_list);
		$fileName = array('_Brochure', '_ChineseBrochure', '_Benefit_Summary', '_Clinic_Map', '_Claim_Form', '_Claim_Procedure', '_Consent_Form', '_Policy', '_Medical_Questionnaire', '_Baggage_Claim_Form', '_Cancellation_Claim_Form', '_Medical_Claim_Form');
		
		foreach ($product_list as $product_short => $p) {
			$file_url[$product_short] = array('fullname' => $p['full_name'], 'files' => array());
			foreach ($fileName as $fn) {
				$name = str_replace('_', ' ', $fn);
				$fname = $product_short . $fn . ".pdf";
				if (file_exists(DOWNLOADDIR . $fname)) {
					$file_url[$product_short]['files'][] = array('url' => $downloads_url . $fname, 'name' => $name);
				}
				$fname = $product_short . $fn . "_French.pdf";
				if (file_exists(DOWNLOADDIR . $fname)) {
					$file_url[$product_short]['files'][] = array('url' => $downloads_url . $fname, 'name' => $name . "(French)");
				}
			}
		}
    $this->app_model->return_ok($file_url);
	}
}
