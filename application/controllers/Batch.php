<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Batch extends MY_Controller {
	public function index() {
		show_error ( 'Page not found', 404 );
	}

	public function import() {
		$beuser = $this->func_model->verify_login ();
		$user = $this->session->userdata ( 'user' );
		$this->load->model ( 'product_model' );
		$this->load->model ( 'batch_model' );
		
		$data ['errormsg'] = "";
		if ($user ['user_group_id'] > 103) {
			$data ['errormsg'] = $this->lang->line ( "error_no_permission" );
		}
		if (empty ( $data ['errormsg'] ) && $this->input->post ( 'submit' )) {
			$uf = array_shift ( $_FILES );
			$name = $uf ['name'];
			$type = $uf ['type'];
			$tmp_name = $uf ['tmp_name'];
			$size = $uf ['size'];
			$fileinfo = pathinfo ( $name );
			if (! empty ( $uf ['error'] )) {
				$data ['errormsg'] = sprintf ( $this->lang->line ( 'error_file_upload' ), $name ) . "<br />";
			} else if (! in_array ( $fileinfo ['extension'], array (
					'xlsx'/*,'csv'*/) )) {
				$data ['errormsg'] = sprintf ( $this->lang->line ( 'error_file_type' ), $name );
			} else {
				$reader = ReaderFactory::create ( Type::XLSX ); // for XLSX files
				                                             // $reader = ReaderFactory::create(Type::CSV); // for CSV files
				                                             // $reader = ReaderFactory::create(Type::ODS); // for ODS files
				$reader->open ( $tmp_name );
				$keyArr = array ();
				$batch_number = $this->batch_model->get_batch_number ( $name, "Upload file by (" . $user ['user_id'] . "): " . $user ['firstname'] . " " . $user ['lastname'] );
				$needShowBatch = 0;
				$plancnt = 0;
				$data = array ('errormsg' => '');
				$planArr = array();
				foreach ( $reader->getSheetIterator () as $sheet ) {
					$i = 0;
					foreach ( $sheet->getRowIterator () as $row ) {
						$i ++;
						if (empty ( $keyArr )) {
							$keyArr = $row;
							continue;
						} else if (sizeof ( $keyArr ) != sizeof ( $row )) {
							$data ['errormsg'] .= "File data error at line " . $i . ": " . join ( "|", $row ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr [$j]] = $row [$j];
						}
						if (empty ( $data ['user_id'] )) {
							if ($this->input->post ( 'user_id' )) {
								$data ['user_id'] = $this->input->post ( 'user_id' );
							} else {
								$data ['user_id'] = $beuser ['user_id'];
							}
						}
						if (empty ( $data ['product_short'] )) {
							if ($this->input->post ( 'product_short' )) {
								$data ['product_short'] = $this->input->post ( 'product_short' );
							} else {
								$data ['errormsg'] .= "No product at line " . $i . ": " . @join ( "|", $row ) . "<br>\n";
								$this->rollback($planArr);
								break;
							}
						} else {
							if ($this->input->post ( 'product_short' )) {
								$product_short = $this->input->post ( 'product_short' );
								if ($product_short != $data ['product_short']) {
									$data ['errormsg'] .= "product_short wrong at line " . $i . " (should be " . $product_short . "): " . @join ( "|", $row ) . "<br>\n";
									$this->rollback($planArr);
									break;
								}
							}
						}
						
						if ($beuser['region_id'] && isset($data['region_id']) && ($beuser['region_id'] != $data['region_id'])) {
							$data ['errormsg'] .= "You need permission to upload at line " . $i . " (" . $beuser['region_id'] . "): " . @join ( "|", $row ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
						
						$product_short = $data ['product_short'];
						$p = $this->product_model->get_product($product_short);
						if (empty($p)) {
							$data ['errormsg'] .= "Unknown product_short at line " . $i . " (should be " . $product_short . "): " . @join ( "|", $row ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
						if (! in_array ( 'batch_number', $keyArr )) {
							$data ['batch_number'] = $batch_number;
							$needShowBatch = 1;
						} else {
							$data ['batch_number'] = 0;
						}
						$plan_id = $this->batch_model->add_record ( $data );
						if ($plan_id) {
							$plancnt ++;
							$plan = $this->plan_model->get_plan_by_id ( $plan_id );
							$para = array (
									'plan_id' => $plan_id,
									'customer_id' => $plan ['customer_id'],
									'payment_id' => $plan ['payment_id'],
									'message' => $this->plan_model->logstr,
									'systemlog' => $this->plan_model->sqlstr 
							);
							$this->log_model->activity ( 'plan', $para );
						} else {
							$data ['errormsg'] .= "Error happened (" . $this->batch_model->error . ") record at line " . $i . ": " . @join ( "|", $row ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
					}
				}
				
				$reader->close ();
				if (empty($data['errormsg'])) {
					$data ['successmsg'] = "Processed upload file: " . $name;
					$data ['successmsg'] .= "; Created plan (" . $plancnt . ")";
					if ($needShowBatch) {
						$data ['successmsg'] .= "<br>Batch Number is: " . $batch_number;
					}
				}
			}
		}
		$data ['user_id'] = $this->input->post ( 'user_id' );
		$data ['product_id'] = $this->input->post ( 'product_id' );
		$data ['schools'] = $this->user_model->get_school_id_list ();
		$data ['products'] = $this->product_model->product_list ();
		$data ['action_url'] = current_url ();
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data ['top_menu'] = $this->menu_model->load_top_menu ();
		$data ['menu'] = $this->menu_model->load_meun ();
		$this->load->common ( 'batch/import', $data );
	}
	
	public function rollback($planArr) {
		foreach ($planArr as $plan_id) {
			$this->plan_model->delete($plan_id);
		}
	}
}
