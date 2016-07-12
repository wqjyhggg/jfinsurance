<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Pdf extends MY_Controller {
	public $uploadtype = array('pdf', 'doc', 'docx', 'xls', 'xlsx');
	
	public function index()
	{
		$mpdf = new mPDF('c');
		$data = array();
		$html = $this->load->view('pdftest', $data, TRUE);
		$mpdf->writeHTML($html);
		$mpdf->Output();
	}

	public function pdflist()
	{
		$data['filelist'] = array();
		if ($handle = opendir(DOWNLOADDIR)) {
			while (($file = readdir($handle)) !== false) {
				if (substr($file, 0, 1) == '.') continue;
				$data['filelist'][] = $file;
			}
		}
		$data['title_txt'] = 'Manage Download Files';
		$data['upload_url'] = base_url('pdf/upload');
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		//$this->load->view ( 'pdf/pdflist', $data );
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'pdf/pdflist', $data );
	}

	public function upload()
	{
		$data['errormsg'] = "";
		if ($this->input->post()) {
			foreach ($_FILES as $uf) {
				$name = $uf['name'];
				$type = $uf['type'];
				$tmp_name = $uf['tmp_name'];
				$size = $uf['size'];
				if (empty($uf['error'])) {
					$data['errormsg'] .= sprintf($this->lang->line ( 'error_file_upload' ), $name) . "<br />";
					continue;
				}
				$fileinfo = pathinfo($name);
				$filename = $this->input->post('lcfilename');
			    if (!in_array($fileinfo['extension'], $this->uploadtype)) {
			    	$data['errormsg'] .= sprintf($this->lang->line ( 'error_file_type' ), $name) . "<br />";
			    } else {
			    	$filename = DOWNLOADDIR . $filename;
			    	move_uploaded_file($tmp_name, $filename);
			    }
			}
			redirect( base_url ( 'pdf/pdflist' ) );
		}
		$data['action_url'] = current_url();
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'pdf/upload', $data );
	}

	public function import()
	{
		$beuser = $this->func_model->verify_login();
		$user = $this->session->userdata('user');
		$this->load->model('product_model');
		$this->load->model('batch_model');
		
		$data['errormsg'] = "";
		if ($user['user_group_id'] > 2) {
			$data['errormsg'] = $this->lang->line ("error_no_permission");
		}
		if (empty($data['errormsg']) && $this->input->post('submit')) {
			$uf = array_shift($_FILES);
			$name = $uf['name'];
			$type = $uf['type'];
			$tmp_name = $uf['tmp_name'];
			$size = $uf['size'];
			$fileinfo = pathinfo($name);
			if (!empty($uf['error'])) {
				$data['errormsg'] = sprintf($this->lang->line ( 'error_file_upload' ), $name) . "<br />";
			} else if (!in_array($fileinfo['extension'], array('xlsx'/*,'csv'*/))) {
				$data['errormsg'] = sprintf($this->lang->line ( 'error_file_type' ), $name);
		    } else {
		    	$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
				//$reader = ReaderFactory::create(Type::CSV); // for CSV files
				//$reader = ReaderFactory::create(Type::ODS); // for ODS files
				$reader->open($tmp_name);
				$keyArr = array();
				$batch_number = $this->batch_model->get_batch_number($name, "Upload file by (" . $user['user_id'] . "): " . $user['firstname']. " " . $user['lastname']);
				foreach ($reader->getSheetIterator() as $sheet) {
					$i = 0;
					foreach ($sheet->getRowIterator() as $row) {
						$i++;
						if (empty($keyArr)) {
							$keyArr = $row;
							continue;
						} else if (sizeof($keyArr) != sizeof($row)) {
							$data['errormsg'] .= "File data error at line " . $i . ": " . join("|", $row) . "<br>\n";
							continue;
						}
						$data = array();
						for ($j = 0; $j < sizeof($keyArr); $j++) {
							$data[$keyArr[$j]] = $row[$j];
						}
						if (empty($data['user_id'])) {
							if ($this->input->post('user_id')) {
								$data['user_id'] = $this->input->post('user_id');
							} else {
								$data['user_id'] = $beuser['user_id'];
							}
						}
						if (empty($data['product_short'])) {
							if ($this->input->post('product_short')) {
								$data['product_short'] = $this->input->post('product_short');
							} else {
								$data['errormsg'] .= "No product at line " . $i . ": " . join("|", $row) . "<br>\n";
								continue;
							}
						}
						if (!in_array('batch_number', $keyArr)) {
							$data['batch_number'] = $batch_number;
						}
						$plan_id = $this->batch_model->add_record($data);
						if ($plan_id) {
							$plan = $this->plan_model->get_plan_by_id($plan_id);
							$para = array(
									'plan_id' => $plan_id, 
									'customer_id' => $plan['customer_id'], 
									'payment_id' => 0, 
									'message' => $this->plan_model->logstr, 
									'systemlog' => $this->plan_model->sqlstr
							);
							$this->log_model->activity('plan', $para);
						}
					}
				}
				
				$reader->close();
				$data['successmsg'] = "Processed upload file: " . $name;
			}
		}
		$data['user_id'] = $this->input->post('user_id');
		$data['product_id'] = $this->input->post('product_id');
		$data['schools'] = $this->user_model->get_school_id_list();
		$data['products'] = $this->product_model->product_list();
		$data['action_url'] = current_url();
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data['top_menu'] = $this->menu_model->load_top_menu();
		$data['menu'] = $this->menu_model->load_meun();
		$this->load->common ( 'pdf/import', $data );
	}

	public function download($filename)
	{
		$fileinfo = pathinfo($filename);
	    $mimeType = '';
	    switch ($fileinfo['extension']) {
	        case 'pdf':
	            $mimeType = 'application/pdf';
	            break;
	        case 'doc':
	            $mimeType = 'application/msword';
	            break;
	        case 'docx':
	            $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	            break;
	        case 'xls':
	            $mimeType = 'application/vnd.ms-excel';
	            break;
	        case 'xlsx':
	            $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	            break;
	        case 'ppt':
	            $mimeType = 'application/vnd.ms-powerpoint';
	            break;
	        case 'pptx':
	            $mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	            break;
	        default:
	        	$this->session->set_userdata ( "error_message", $this->lang->line ( 'error_no_file' ) );
	        	redirect ( base_url ( 'errorpage' ) );
	    }       
	    header('Content-type: ' . $mimeType);
	    //header('Content-Disposition: attachment; filename="' . $filename . '"');
	    header('Content-Disposition: inline; filename="' . $filename . '"');
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Pragma: no-cache');
	    readfile(DOWNLOADDIR . $filename);
	}
}
