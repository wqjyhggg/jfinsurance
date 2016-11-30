<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Setting extends MY_Controller {
	public function index() {
		$beuser = $this->func_model->verify_login ();
		$user = $this->session->userdata ( 'user' );
		
		$data ['errormsg'] = "";
		if ($user ['user_group_id'] > 1) {
			$data ['errormsg'] = $this->lang->line ( "error_no_permission" );
		}
		
		$this->load->model('setting_model');
		
		$data['api'] = $this->setting_model->get_settings('api');
		$data['delete_url'] = base_url('setting/delete');
		$data['add_url'] = base_url('setting/add');
		
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data ['top_menu'] = $this->menu_model->load_top_menu ();
		$data ['menu'] = $this->menu_model->load_meun ();
		$this->load->common ( 'setting/api', $data );
	}
	
	public function add() {
		$this->load->model('setting_model');
		if (empty($this->input->post('type'))) {
			$data['errormsg'] = 'Please Seleect Setting Type';
		} else if (empty($this->input->post('name'))) {
			$data['errormsg'] = 'Please input API IP';
		} else if (empty($this->input->post('value'))) {
			$data['errormsg'] = 'Please input Key';
		} else if ($this->input->post()) {
			$setting_id = $this->setting_model->add(array('type' => trim($this->input->post('type')), 'name' => trim($this->input->post('name')), 'value' => trim($this->input->post('value'))));
			if ($setting_id) {
				$data['successmsg'] = 'Added new setting';
			} else {
				$data['errormsg'] = "Can't add new setting";
			}
		} else {
			$data['errormsg'] = 'Unknown request';
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function update() {
		$data['errormsg'] = 'Unknown request';
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function delete() {
		if (empty($this->input->post('setting_id' ))) {
			$data['errormsg'] = 'Unknown request';
		} else {
			$this->load->model('setting_model');
			$this->setting_model->delete($this->input->post('setting_id'));
			$data['successmsg'] = 'OK';
		}
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
}
