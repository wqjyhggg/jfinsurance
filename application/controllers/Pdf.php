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
		$data['upload_url'] = base_url('pdf/upload');
		$this->load->view ( 'pdf/pdflist', $data );
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
		$this->load->view ( 'pdf/upload', $data );
	}

	public function import()
	{
		$data['errormsg'] = "";
		if ($this->input->post()) {
			$uf = array_shift($_FILES);
			$name = $uf['name'];
			$type = $uf['type'];
			$tmp_name = $uf['tmp_name'];
			$size = $uf['size'];
			$fileinfo = pathinfo($name);
			if (empty($uf['error'])) {
				$data['errormsg'] = sprintf($this->lang->line ( 'error_file_upload' ), $name) . "<br />";
			} else if (!in_array($fileinfo['extension'], array('xlsx','csv'))) {
		    	$data['errormsg'] = sprintf($this->lang->line ( 'error_file_type' ), $name);
		    } else {
				$reader = ReaderFactory::create(Type::XLSX); // for XLSX files
				//$reader = ReaderFactory::create(Type::CSV); // for CSV files
				//$reader = ReaderFactory::create(Type::ODS); // for ODS files
				$reader->open($tmp_name);
				
				foreach ($reader->getSheetIterator() as $sheet) {
					foreach ($sheet->getRowIterator() as $row) {
						print_r($row);
					}
				}
				
				$reader->close();
				$gourl = $this->input->post('gourl');
				if (empty($gourl)) {
					$data['successmsg'] = $this->lang->line ( 'text_option_success' );
				} else {
					redirect ( base_url ( $gourl ) );
				}
			}
		}
		$data['action_url'] = current_url();
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$this->load->view ( 'pdf/import', $data );
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
	    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment; filename="' . $filename . '"');
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Pragma: no-cache');
	    readfile(DOWNLOADDIR . $filename);
	}
}
