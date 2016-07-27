<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends MY_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data['title_txt'] = 'Downloads';
		$data['downloads_url'] = base_url('pdf/download') . "/";
		
		$data['url_brochure'] = '_Brochure';
		$data['url_benefit'] = '_Benefit_Summary';
		$data['url_claimf'] = '_Claim_Form';
		$data['url_claimp'] = '_Claim_Procedure';
		$data['url_consent'] = '_Consent_Form';
		$data['url_policy'] = '_Policy';

		$data['text_brochure'] = 'Brochure';
		$data['text_benefit'] = 'Benefit Summary';
		$data['text_claimf'] = 'Claim Form';
		$data['text_claimp'] = 'Claim Procedure';
		$data['text_consent'] = 'Consent Form';
		$data['text_policy'] = 'Policy';

		$data['opl'] = 'OPL';
		$data['nus'] = 'NUS';
		$data['jus'] = 'JUS';
		$data['jfc'] = 'JFC';
		$data['jfr'] = 'JFR';
		$data['jfep'] = 'JES';

		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();

		$this->load->common('downloads', $data);
	}
}
