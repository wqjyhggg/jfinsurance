<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
class Annual extends MY_Controller {
	public $annual = "annual/";
	public $uploadtype = array('pdf', 'xls', 'xlsx'/*, 'doc', 'docx'*/);
	public $data = array();
	
	/**
	 * Index Page for this controller.
	 */
	public function filelist($agent_id)
	{
		$dir = DOWNLOADDIR . $this->annual . $agent_id;
		if ( ! file_exists($dir)) {
			if ( ! mkdir($dir, 0777, TRUE)) {
				show_error("Can't create directory " . $dir);
			}
		}
		$filelist = array();
		$dt = new DateTime();
		$dt->sub(date_interval_create_from_date_string("1 years"));
		for ($i = 0; $i < 12; $i++) {
			$filename = $this->annual . $agent_id . "/" . $dt->format("Y-m");
			if (file_exists(DOWNLOADDIR . $filename . ".pdf")) {
				$filelist[$dt->format("Y-m")] = array("filename" => $filename . ".pdf", "has" => 1);
			} else if (file_exists(DOWNLOADDIR . $filename . ".xls")) {
				$filelist[$dt->format("Y-m")] = array("filename" => $filename . ".xls", "has" => 1);
			} else if (file_exists(DOWNLOADDIR . $filename . ".xlsx")) {
				$filelist[$dt->format("Y-m")] = array("filename" => $filename . ".xlsx", "has" => 1);
			} else {
				$filelist[$dt->format("Y-m")] = array("filename" => $filename, "has" => 0);
			}
			$dt->add(date_interval_create_from_date_string("1 months"));
		}
		return $filelist;
	}
	
	public function index() {
		$beuser = $this->func_model->verify_login();
		$this->load->model('report_model');
		
		$this->data['csrf'] = array(
				'name' => $this->security->get_csrf_token_name(),
				'value' => $this->security->get_csrf_hash() 
		);
		
		$this->data['title_txt'] = 'Annual Report';
		$this->data['top_menu'] = $this->menu_model->load_top_menu();
		$this->data['menu'] = $this->menu_model->load_meun();
		$this->data['action_url'] = current_url();
		$this->data['upload_url'] = base_url("reports/annual/upload");
		
		if ($beuser['user_group_id'] > 100) {
			$this->data['agent_id'] = $beuser['user_id'];
		} else {
			$this->data['agent_id'] = empty($this->input->post('agent_id')) ? 0 : (int)$this->input->post('agent_id');
			$this->data['user_list'] = $this->user_model->get_available_user_list();
		}

		if ($this->data['agent_id']) {
			// Get user file list
			$this->data['filelist'] = $this->filelist($this->data['agent_id']);
		}
		$this->data['beuser'] = $beuser;
		$this->load->common('reports/annual', $this->data);
	}
	
	function upload() {
		$this->data['errormsg'] = "";
		if ($this->input->post()) {
			foreach ($_FILES as $uf) {
				$name = $uf['name'];
				$type = $uf['type'];
				$tmp_name = $uf['tmp_name'];
				$size = $uf['size'];
				if (!empty($uf['error'])) {
					$this->data['errormsg'] .= sprintf($this->lang->line ( 'error_file_upload' ), $name) . "<br />";
					continue;
				}
				$fileinfo = pathinfo($name);
				$filename = $this->input->post('key') . "." . $fileinfo['extension'];
			    if (!in_array($fileinfo['extension'], $this->uploadtype)) {
			    	$this->data['errormsg'] .= sprintf($this->lang->line ( 'error_file_type' ), $name) . "<br />";
			    } else {
			    	$filename = DOWNLOADDIR . $this->annual . $this->input->post('agent_id') . "/" . $filename;
			    	move_uploaded_file($tmp_name, $filename);
			    }
			}
		}
		return $this->index();
	}
}
