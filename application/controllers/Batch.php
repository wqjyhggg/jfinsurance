<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;

class Batch extends MY_Controller {
	public $planArr = array('plan_id' => 0,
			'customer_id' => 0,
			'user_id' => 0,
			'status_id' => 0,
			'policy' => '',
			'product_short' => '',
			'batch_number' => 0,
			'isfamilyplan' => 0,
			'apply_date' => '',
			'arrival_date' => '',
			'effective_date' => '',
			'expiry_date' =>  '',
			'beneficiary' => '',
			'sum_insured' => 0,
			'deductible_amount' => 0,
			'dailyrate' => 0,
			'totaldays' => 0,
			'premium' => 0,
			'street_number' => '',
			'street_name' => '',
			'suite_number' => '',
			'city' => '',
			'province2' => '',
			'country2' => '',
			'postcode' => '',
			'phone1' => '',
			'phone2' => '',
			'contact_email' => '',
			'contact_phone' => '',
			'residence' => '',
			'payinfo' => 'Batch Upload Other');
	public $customerArr = array(
			'firstname' => '',
			'lastname' => '',
			'gender' => '',
			'birthday' => '');
	public $paymentArr = array(
			'payment_id' => 0,
			'amount' => 0,
			'admin_fee' => 0,
			'rate' => 0,
			'ispaid' => 0,
			'pay_mothed' => 'Checque',
			'pay_type' => 'premium',
			'currency' => 'CAD',
			'added' => '',
			'invoice_num' => '',
			'pay_date' => '',
			'bank_name' => '',
			'payor_name' => '',
			'cheque_number' => '',
			'cheque_cash_date' => '2010-01-01',
			'pay_to' => '',
			'name' => '',
			'first5' => '',
			'last4' => '',
			'expiry_month' => '',
			'expiry_year' => '',
			'note' => '');
	
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
		if (0 && empty ( $data ['errormsg'] ) && $this->input->post ( 'userfilename' )) {
			// Disabled for extent timeout
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
				set_time_limit(600); // Max run time 10 minutes
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
						}
						$emptyline = TRUE;
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr[$j]] = isset($row[$j]) ? $row [$j] : '';
							if (!empty($data [$keyArr[$j]])) {
								$emptyline = TRUE;
							}
						}
						if ($emptyline) continue;
						
						if ($beuser['user_group_id'] > 100) {
							if (!empty ( $data ['user_id'] )) {
								$data ['errormsg'] .= "Can't assign user at line " . $i . ": " . @join ( "|", $row ) . "<br>\n";
								$this->rollback($planArr);
								break;
							} else {
								$data ['user_id'] = $beuser ['user_id'];
							}
						} else if (empty ( $data ['user_id'] )) {
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
							// $data ['batch_number'] = 0;		Keep current batch_number if it existed
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
		$data ['process_url'] = base_url('batch/processe');
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data ['top_menu'] = $this->menu_model->load_top_menu ();
		$data ['menu'] = $this->menu_model->load_meun ();
		$this->load->common ( 'batch/import', $data );
	}
	
	public function processe() {
		$beuser = $this->func_model->verify_login ();
		$user = $this->session->userdata ( 'user' );
		$this->load->model ( 'product_model' );
		$this->load->model ( 'batch_model' );
		
		$data ['errormsg'] = "";
		if ($user ['user_group_id'] > 103) {
			$data ['errormsg'] = $this->lang->line ( "error_no_permission" );
		}
		if (empty ($this->input->post ( 'userfilename' ))) {
			$data ['errormsg'] = "Please select upload file";
		}
		if (empty ( $data ['errormsg'] )) {
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
				set_time_limit(600); // Max run time 10 minutes
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
						}
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr [$j]] = isset($row [$j]) ? $row [$j] : '';
						}
						$emptyline = TRUE;
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr[$j]] = isset($row[$j]) ? $row [$j] : '';
							if (!empty($data [$keyArr[$j]])) {
								$emptyline = FALSE;
							}
						}
						if ($emptyline) continue;
						
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
							// $data ['batch_number'] = 0;
						}
						$plan_id = $this->batch_model->add_record ( $data );
						if ($plan_id) {
							$planArr[] = $plan_id;
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
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function other() {
		$beuser = $this->func_model->verify_login ();
		$user = $this->session->userdata ( 'user' );
		$this->load->model ( 'product_model' );
		
		$data ['errormsg'] = "";
		if ($user ['user_group_id'] > 103) {
			$data ['errormsg'] = $this->lang->line ( "error_no_permission" );
		}
		// $data ['agents'] = $this->user_model->get_all_agent_list ();
		$data ['action_url'] = current_url ();
		$data ['process_url'] = base_url('batch/loadother');
		$data ['download_url'] = base_url('batch/downloadothers');
		$data ['csrf'] = array (
				'name' => $this->security->get_csrf_token_name (),
				'value' => $this->security->get_csrf_hash () 
		);
		$data ['top_menu'] = $this->menu_model->load_top_menu ();
		$data ['menu'] = $this->menu_model->load_meun ();
		$this->load->common ( 'batch/other', $data );
	}
	
	function downloadothers() {
		$beuser = $this->func_model->verify_login();
		$this->load->model('plan_model');
		
		$data = array('batch_number' => (int)$this->input->get_post('batch_number'));
		
		$rt = $this->plan_model->plan_export_search($data, 1);
		
		if (empty($rt)) {
			die("Not result");
		}
		$w = WriterFactory::create(Type::XLSX); // for XLSX files
		
		$w->openToBrowser("other_policy_" . date('Ymd') . ".xlsx");
		//$w->openToFile(/tmp/jf_test.xlsx);
		
		$arr = array();
		
		foreach ($this->planArr as $k => $v) { $arr[] = $k; } 
		foreach ($this->customerArr as $k => $v) { $arr[] = $k; }
		for ($i = 1; $i < 9; $i++) {
			foreach ($this->customerArr as $k => $v) {
				$arr[] = $k . "_" . $i;
			}
		}
		for ($i = 0; $i < 6; $i++) {
			foreach ($this->paymentArr as $k => $v) {
				$arr[] = $k . "_" . $i;
			}
		}
		$w->addRow($arr);
		
		$kArr = $arr;
		foreach ($rt as $data) {
			$arr = array();
			foreach ($kArr as $k) {
				if (isset($data[$k])) {
					$arr[] = $data[$k];
				} else {
					$arr[] = '';
				}
            }
            // for payment more than 6 record
            $i = 6;
            while (isset($data['payment_id_'.$i])) {
            	foreach ($this->paymentArr as $k => $v) {
            		$key = $k . "_" . $i;
            		if (isset($data[$key])) {
            			$arr[] = $data[$key];
            		} else {
            			$arr[] = '';
            		}
            	}
            	$i++;
            }
            $w->addRow($arr);
        }
        $w->close();
        /*
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Policy' . date('Ymd') . '.xlsx"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: no-cache');
        readfile($tmpfname);
        */
        //unlink($tmpfname);
    }
	
	public function loadother() {
		$beuser = $this->func_model->verify_login ();
		$user = $this->session->userdata ( 'user' );
		$this->load->model('product_model');
		$this->load->model('batch_model');
		$this->load->model('plan_model');
		$this->load->model('payment_model');
		
		date_default_timezone_set('UTC');
		
		$data ['errormsg'] = "";
		if ($user ['user_group_id'] > 103) {
			$data ['errormsg'] = $this->lang->line ( "error_no_permission" );
		}
		if (empty ($this->input->post ( 'userfilename' ))) {
			$data ['errormsg'] = "Please select upload file";
		}
		if (empty ( $data ['errormsg'] )) {
			$uf = array_shift ( $_FILES );
			$name = $uf ['name'];
			$type = $uf ['type'];
			$tmp_name = $uf ['tmp_name'];
			$size = $uf ['size'];
			$fileinfo = pathinfo ( $name );
			if (! empty ( $uf ['error'] )) {
				$data ['errormsg'] = sprintf ( $this->lang->line ( 'error_file_upload' ), $name ) . "<br />";
			} else if (! in_array ( $fileinfo ['extension'], array ('xlsx') )) {
				$data ['errormsg'] = sprintf ( $this->lang->line ( 'error_file_type' ), $name );
			} else {
				set_time_limit(600); // Max run time 10 minutes
				$batch_number = $this->batch_model->get_batch_number ( $name, "Upload file by (" . $user ['user_id'] . "): " . $user ['firstname'] . " " . $user ['lastname'] );

				$this->planArr['status_id'] = Plan_model::SOLD;
				$this->planArr['batch_number'] = $batch_number;
				$this->planArr['payinfo'] = 'Batch Upload Other';
				$this->paymentArr['user_id'] = $beuser['user_id'];
				$this->paymentArr['currency'] = 'CAD';
				$this->paymentArr['added'] = date('Y-m-d H:i:s');
						
				$reader = ReaderFactory::create ( Type::XLSX ); // for XLSX files
				                                             // $reader = ReaderFactory::create(Type::CSV); // for CSV files
				                                             // $reader = ReaderFactory::create(Type::ODS); // for ODS files
				$reader->open ( $tmp_name );
				$keyArr = array ();
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
							if (!in_array('user_id', $keyArr)) {
								$data ['errormsg'] .= "Must has user_id column for upload<br>\n";
								break 2;
							}
							continue;
						}
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr [$j]] = isset($row [$j]) ? $row [$j] : '';
						}
						$emptyline = TRUE;
						for($j = 0; $j < sizeof ( $keyArr ); $j ++) {
							$data [$keyArr[$j]] = isset($row[$j]) ? $row [$j] : '';
							if (!empty($data [$keyArr[$j]])) {
								$emptyline = FALSE;
							}
						}
						if ($emptyline) continue;
						if (empty($data['product_short'])) {
							if (empty($data['plan_id'])) {
								$data ['errormsg'] .= "No product at line " . $i . ": " . @join ( "|", $row ) . "<br>\n";
								$this->rollback($planArr);
								break;
							}
						} else {
							$p = $this->product_model->get_product($data['product_short']);
							if (empty($p) || $p['calculate']) {
								$data ['errormsg'] .= "product_short wrong at line " . $i . " (should be " . $product_short . "): " . @join ( "|", $row ) . "<br>\n";
								$this->rollback($planArr);
								break;
							}
						}
						$fakebeuser = $this->user_model->get_user_by_id($data['user_id']);
						if (empty($fakebeuser)) {
							$data ['errormsg'] .= "Can't find user (agent) at line " . $i . ": " . @join ( "|", $row ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
						$this->session->set_userdata ( 'beuser',  $fakebeuser);
						
						if (isset($data['apply_date'])) $data['apply_date'] = date('Y-m-d', $this->batch_model->unixstamp($data['apply_date']));
						if (isset($data['arrival_date'])) $data['arrival_date'] = date('Y-m-d', $this->batch_model->unixstamp($data['arrival_date']));
						if (isset($data['effective_date'])) $data['effective_date'] = date('Y-m-d', $this->batch_model->unixstamp($data['effective_date']));
						if (isset($data['expiry_date'])) $data['expiry_date'] = date('Y-m-d', $this->batch_model->unixstamp($data['expiry_date']));
						if (isset($data['birthday'])) $data['birthday'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday']));
						if (isset($data['birthday_1'])) $data['birthday_1'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_1']));
						if (isset($data['birthday_2'])) $data['birthday_2'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_2']));
						if (isset($data['birthday_3'])) $data['birthday_3'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_3']));
						if (isset($data['birthday_4'])) $data['birthday_4'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_4']));
						if (isset($data['birthday_5'])) $data['birthday_5'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_5']));
						if (isset($data['birthday_6'])) $data['birthday_6'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_6']));
						if (isset($data['birthday_7'])) $data['birthday_7'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_7']));
						if (isset($data['birthday_8'])) $data['birthday_8'] = date('Y-m-d', $this->batch_model->unixstamp($data['birthday_8']));
						if (isset($data['added'])) $data['added'] = date('Y-m-d H:i:s', $this->batch_model->unixstamp($data['added']));
						if (isset($data['pay_date'])) $data['pay_date'] = date('Y-m-d', $this->batch_model->unixstamp($data['pay_date']));
						if (isset($data['plan_id'])) {
							// Update
							$data['payinfo'] = isset($data['payinfo']) ? $data['payinfo'] . ' Batch Upload Update' : ' Batch Upload Update';
							$plan_id = $this->plan_model->update($data['plan_id'], $row);
							if (isset($data['payment_id'])) {
								// update payment
								$payment_id = $data['payment_id'];
								unset($this->paymentArr['payment_id']);
								$para = array();
								foreach ($this->paymentArr as $k => $v) {
									if (isset($data[$k])) {
										$para[$k] = $data[$k];
										if ($k == 'added') {
											$para['last_update'] = $data[$k];
										}
									}
								}
								$para['note'] = isset($para['note']) ? $para['note'] . '; Batch updated' : 'Batch updated';
								$pay_type = $this->payment_model->pay_type($data['pay_type']);
								if ($pay_type) {
									$this->payment_model->update($payment_id, $para);
								}
							} else {
								// new payment
								unset($this->paymentArr['payment_id']);
								$para = array();
								foreach ($this->paymentArr as $k => $v) {
									if (isset($data[$k])) {
										$para[$k] = $data[$k];
										if ($k == 'added') {
											$para['last_update'] = $data[$k];
										}
									}
								}
								$para['user_id'] = $beuser['user_id'];
								$para['plan_id'] = $plan_id;
								$para['note'] = isset($para['note']) ? $para['note'] . '; Batch added' : 'Batch added';
								$pay_type = $this->payment_model->pay_type($data['pay_type']);
								if ($pay_type == 'premium') {
									$payment_id = $this->payment_model->add($para); // 'premium';
									$para['premium_payment_id'] = $payment_id;
									if (!empty($data['commission_rate'])) {
										$para['pay_type'] = 'commission';
										$para['rate'] = $data['commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['commission_rate'] / 100.0;
										$this->payment_model->add($para);
									}
									if (!empty($data['up_commission_rate'])) {
										$para['pay_type'] = 'up_commission';
										$para['rate'] = $data['up_commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['up_commission_rate'] / 100.0;
										echo "UPUP[".$para['amount']."]";
										$this->payment_model->add($para);
									}
								} else if ($pay_type == 'refund') {
									$payment_id = $this->payment_model->add($para); // 'premium';
									$para['premium_payment_id'] = $payment_id;
									if (!empty($data['commission_rate'])) {
										$para['pay_type'] = 'refund_commission';
										$para['rate'] = $data['commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['commission_rate'] / 100.0;
										if ($para['amount'] > 0) $this->payment_model->add($para);
									}
									if (!empty($data['up_commission_rate'])) {
										$para['pay_type'] = 'refund_up_commission';
										$para['rate'] = $data['up_commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['up_commission_rate'] / 100.0;
										$this->payment_model->add($para);
									}
								} else if ($pay_type == 'cancel') {
									$payment_id = $this->payment_model->add($para); // 'premium';
									$para['premium_payment_id'] = $payment_id;
									if (!empty($data['commission_rate'])) {
										$para['pay_type'] = 'cancel_commission';
										$para['rate'] = $data['commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['commission_rate'] / 100.0;
										$this->payment_model->add($para);
									}
									if (!empty($data['up_commission_rate'])) {
										$para['pay_type'] = 'cancel_up_commission';
										$para['rate'] = $data['up_commission_rate'];
										$para['amount'] = (float)$data['amount'] * (float)$data['up_commission_rate'] / 100.0;
										$this->payment_model->add($para);
									}
								}
							}
						} else {
							// New Plan
							$data['status_id'] = empty($data['ispaid']) ? Plan_model::SOLD : Plan_model::PAID;
							$data['batch_number'] = $batch_number;
							$data['note'] = 'Batch Upload Add';
							$plan_id = $this->plan_model->add($data);
							if ($plan_id) {
								// add payment
								unset($this->paymentArr['payment_id']);
								$para = array();
								foreach ($this->paymentArr as $k => $v) {
									if (isset($data[$k])) {
										$para[$k] = $data[$k];
										if ($k == 'added') {
											$para['last_update'] = $data[$k];
										}
									}
								}
								$para['user_id'] = $beuser['user_id'];
								$para['plan_id'] = $plan_id;
								$para['note'] = isset($para['note']) ? $para['note'] . '; Batch added' : 'Batch added';
								
								$para['pay_type'] = 'premium';
								$payment_id = $this->payment_model->add($para); // 'premium';
								$para['premium_payment_id'] = $payment_id;
								if (!empty($data['commission_rate'])) {
									$para['pay_type'] = 'commission';
									$para['rate'] = $data['commission_rate'];
									$para['amount'] = (float)$data['amount'] * (float)$data['commission_rate'] / 100.0;
									$this->payment_model->add($para); // 'premium';
								}
								if (!empty($data['up_commission_rate'])) {
									$para['pay_type'] = 'up_commission';
									$para['rate'] = $data['up_commission_rate'];
									$para['amount'] = (float)$data['amount'] * (float)$data['up_commission_rate'] / 100.0;
									$this->payment_model->add($para); // 'premium';
								}
							}
						}
						if ($plan_id) {
							$planArr[] = $plan_id;
							$plancnt++;
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
							$data ['errormsg'] .= "Error happened (" . $this->batch_model->error . ") record at line " . $i . ": " . @join ( "|", $data ) . "<br>\n";
							$this->rollback($planArr);
							break;
						}
					}
					break;
				}
				
				$reader->close ();
				if (empty($data['errormsg'])) {
					$data ['successmsg'] = "Processed upload file: " . $name;
					$data ['successmsg'] .= "; Created plan (" . $plancnt . ")";
					$data ['successmsg'] .= "\n\rBatch Number is: " . $batch_number;
					$data ['succ_url'] = base_url('plan') . '?search=1&batch_number=' . $batch_number;
				}
			}
		}
		$this->session->set_userdata ( 'beuser',  $beuser);
		header('Content-Type: application/json');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		echo json_encode($data);
	}
	
	public function rollback($planArr) {
		foreach ($planArr as $plan_id) {
			$this->plan_model->delete($plan_id);
		}
	}
}
