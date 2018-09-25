<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$beuser = $this->func_model->verify_login();
		
		$data['title_txt'] = 'Downloads';
		$this->load->model('product_model');

		$downloads_url = base_url('pdf/download') . "/";
		$file_url = array();
		$product_list = $this->product_model->product_list(1);
		ksort($product_list);
		$fileName = array('_Brochure', '_Benefit_Summary', '_Claim_Form', '_Claim_Procedure', '_Consent_Form', '_Policy', '_Questionnaire');
		
		foreach ($product_list as $product_short => $p) {
			$file_url[$product_short] = array('fullname' => $p['full_name'], 'files' => array());
			foreach ($fileName as $fn) {
				$name = str_replace('_', ' ', $fn);
				$fname = $product_short . $fn . ".pdf";
				if (file_exists(DOWNLOADDIR . $fname)) {
					$file_url[$product_short]['files'][] = array('url' => $downloads_url . $fname, 'name' => $name);
				}
			}
		}

		$data['file_url'] = $file_url;
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();

		$this->load->common('downloads', $data);
	}
}
