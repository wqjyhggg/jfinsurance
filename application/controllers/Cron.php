<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Cron extends MY_Controller {
	public $error;

	const FTP_HOST="72.142.65.148";
	const FTP_PORT=7790;
	const FTP_USER='jfu';
	const FTP_PASS='!Tgu7oPb';

/*
	const FTP_HOST="filetransfer.allianz-assistance.ca";
	const FTP_PORT=7790;
	const FTP_USER='JFUTEST';
	const FTP_PASS='T$sting!23';
*/
	
	private function valid() {
		$this->error = '';

		if ((php_sapi_name() === 'cli')) {
			$this->load->model('setting_model');
			$this->setting_model->set_default_user();
			return TRUE;
		}
		show_error("ERROR", 404);
	}
	
  public function check_customer($plan, $st, $firstname, $lastname, $dob) {
    $vrecords = $this->plan_model->verify_customer($firstname, $lastname, $dob);
    $claim_amount = 0;
    $case_amount = 0;
    if ($vrecords['status'] == 'OK') {
      foreach ($vrecords['cases'] as $case) {
        $case_amount += (float)$case['amount'];
      }
      foreach ($vrecords['claims'] as $claim) {
        $claim_amount += (float)$claim['amount'];
      }
    }
    if (empty($claim_amount) && empty($case_amount)) {
      // continue check next customer
      ;
    } else if (($claim_amount <= 2000) && ($case_amount <= 2000)) {
      echo '"'.$plan['policy'].'","'.$st.'","<2000","'.$lastname.'","'.$firstname.'","'.$dob.'"'."\n";
    } else /* if (($claim_amount > 2000) || ($case_amount > 2000)) */ {
      echo '"'.$plan['policy'].'","'.$st.'",">2000","'.$lastname.'","'.$firstname.'","'.$dob.'"'."\n";
    }
  }

	public function index() {
		if ($this->valid()) {
      $this->load->model('plan_model');

      $status = array(1=>"Quote", 2=>"Sold",3=>"Paid",4=>"Claimed",7=>"Changed");
      $dt = date("Y-m-d");
      foreach ($status as $st=>$sstr) {
        $plans = $this->plan_model->plan_search(array('status_id'=>$st,'expiry_date'=>$dt));
        foreach ($plans as $plan) {
          $this->check_customer($plan, $sstr, $plan['firstname'], $plan['lastname'], $plan['birthday']);
          if ($plan['isfamilyplan']) {
            $customers = $this->customer_model->get_plan_customers_by_id($plan['plan_id']);
            foreach ($customers as $cust) {
              if ($plan['customer_id'] == $cust['customer_id']) {
                continue;
              }
              $this->check_customer($plan, $sstr, $cust['firstname'], $cust['lastname'], $cust['birthday']);
            }
          }
        }
      }
			die("OK\n");
		} else {
			die($this->error."\n");
		}
	}
	
	private function ftp($src, $dst) {
		$this->valid();
		$conn = ssh2_connect(self::FTP_HOST, self::FTP_PORT);
		if (!$conn) {
			echo "Can't open connect to ". self::FTP_HOST . " prot " . self::FTP_PORT . " at time " .date('Ymd His') . "\n";
			return FALSE;
		}
		$login_result = ssh2_auth_password($conn, self::FTP_USER, self::FTP_PASS);
		
		if (!$login_result) {
			echo "can't login at time " .date('Ymd His') . "\n";
			return FALSE;
		} else {
			echo "connected ";
			$resSFTP = ssh2_sftp($conn);
			$resFile = fopen("ssh2.sftp://".intval($resSFTP)."/".$dst, 'w');
			$srcFile = fopen($src, 'r');
			$writtenBytes = stream_copy_to_stream($srcFile, $resFile);
			echo " and send file: ".$dst." with ".$writtenBytes." bytes data at time " .date('Ymd His') . "\n";
			fclose($resFile);
			fclose($srcFile);
			//ssh2_exec($conn, 'exit');
			unset($conn);
		}
		return TRUE;
	}

	public function add_premium_id() {
		$this->valid();
		
		$this->db->where('pay_type', 'premium');
		$rs = $this->db->get('payment')->result_array();
		foreach ($rs as $r) {
			echo "ID :" . $r['payment_id'] . "\n";
			
			$payment_id = $r['payment_id'] + 1;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'up_commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: up_commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
			
			$payment_id = $r['payment_id'] + 2;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
		}
			
		$this->db->where('pay_type', 'refund');
		$rs = $this->db->get('payment')->result_array();
		foreach ($rs as $r) {
			echo "ID :" . $r['payment_id'] . "\n";
			
			$payment_id = $r['payment_id'] + 2;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'refund_up_commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: refund_up_commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
			
			$payment_id = $r['payment_id'] + 1;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'refund_commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: refund_commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
		}
			
		$this->db->where('pay_type', 'cancel');
		$rs = $this->db->get('payment')->result_array();
		foreach ($rs as $r) {
			echo "ID :" . $r['payment_id'] . "\n";
			
			$payment_id = $r['payment_id'] + 2;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'cancel_up_commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: cancel_up_commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
			
			$payment_id = $r['payment_id'] + 1;
			$this->db->where('payment_id', $payment_id);
			$this->db->where('pay_type', 'cancel_commission');
			$row = $this->db->get('payment')->row_array();
			if (empty($row)) {
				die("ERROR: cancel_commission\n");
			} else {
				$this->db->set('premium_payment_id', $r['payment_id']);
				$this->db->where('payment_id', $payment_id);
				$this->db->update('payment');
			}
		}
	}

	public function import($filename) {
		$this->valid();

		//$filename = '/home/jackw/Downloads/agent_2016_12_19.xls';
		
		$this->load->model ( 'product_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'batch_model' );
		
		set_time_limit(0);

		if (!file_exists($filename)) {
			exit("Can't find file: ".$filename."\n");
		}

		echo date('H:i:s') , " Load workbook from Excel5 file" , "\n";
		$callStartTime = microtime(true);

		$objPHPExcel = PHPExcel_IOFactory::load($filename);

		$callEndTime = microtime(true);
		$callTime = $callEndTime - $callStartTime;
		
		echo 'Call time to load Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , "\n";
		// Echo memory usage
		echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , "\n";
		
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$rowData = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
		print_r($rowData[0]);
		$product_list = $this->product_model->product_list(1);
		unset($product_list['JUS']);
		/*  [0] => AgentID
            [1] => Active
            [2] => UserName
            [3] => UserPassword
            [4] => UserType
            [5] => AgencyCode
            [6] => Region
            [7] => BannerHeader
            [8] => AgentName
            [9] => FirstName
            [10] => LastName
            [11] => Title
            [12] => Address
            [13] => City
            [14] => Province
            [15] => Country
            [16] => PostalCode
            [17] => Email
            [18] => Website
            [19] => BusPhone
            [20] => Fax
            [21] => Mobile
            [22] => Toll
            [23] => Last_Access
            [24] => DateCreated
            [25] => UpdateDate
            [26] => ProvCode
            [27] => CardPaymentOnly
            [28] => RestrictedProductCode
            [29] => LicenceNo
            [30] => LicenceExpiryDate
            [31] => SubAgencyCode
            [32] => AllProduct
            [33] => AboutUs
            [34] => BrokerLogo
            [35] => EmailReport
            [36] => NoConfirmLogo */

		//  Loop through each row of the worksheet in turn
		for ($row = 2; $row <= $highestRow; $row++) { 
			//  Read a row of data into an array
			$Data = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			//  Insert row data array into your database of choice here
			$rowData = $Data[0];
			//print_r($rowData);
			$DateCreatedCell = $sheet->getCell('Y' . $row);
			$DateCreated = PHPExcel_Style_NumberFormat::toFormattedString($DateCreatedCell->getCalculatedValue(), 'YYYY-MM-DD hh:mm:ss');

			$data = array();
			if (empty($rowData[1])) {
				$data['status'] = '0';
			} else {
				$data['status'] = '1';
			}
			if ($this->user_model->check_username(0, $rowData[2])) {
				echo "This user [" . $rowData[2] . "] is existed in system\n";
				continue;
			}
			$data['username'] = trim($rowData[2]);
			$data['password'] = trim($rowData[3]);
			switch(trim($rowData[4])) {
				case 'Y': $data['user_group_id'] = 1; break;	// System Admin
				case 'Z': $data['user_group_id'] = 1; break;	// System Admin
				case 'J': $data['user_group_id'] = 1; break;	// System Admin
				case 'F': $data['user_group_id'] = 2; break;	// Staff
				case 'A': $data['user_group_id'] = 104; break;	// Brokeage
				case 'E': $data['user_group_id'] = 105; break;	// Agent
				case 'S': $data['user_group_id'] = 103; break;	// School
				case 'I': $data['user_group_id'] = 105; break;	// Agent
				default:  $data['user_group_id'] = 0; break;		// Unknown
			}
			if (empty($data['user_group_id'])) {
				echo "Unknown user type: ".$rowData[4]."\n";
				exit;
			}
			switch(trim($rowData[6])) {
				case 'ON': $data['region_id'] = 1; break;	// TORONTO
				case 'BC': $data['region_id'] = 2; break;	// British Columbia
				case 'ALL': $data['region_id'] = 0; break;	// All region
				default:  $data['region_id'] = -1; break;		// Unknown
			}
			if ($data['region_id'] < 0) {
				echo "Unknown user region: ".$rowData[6]." skippid user " .$data['username']. "\n";
				continue;
			}
			$data['business'] = trim($rowData[8]);

			$data['firstname'] = trim($rowData[9]);
			$data['lastname'] = trim($rowData[10]);

			switch(trim($rowData[11])) {
				case 'Mr': $data['gender'] = 'M'; break;
				case 'Mrs': $data['gender'] = 'F'; break;
				case 'Miss': $data['gender'] = 'F'; break;
				default:  $data['gender'] = 'M'; break;
			}

			$data['address'] = $data['mail_address'] = trim($rowData[12]);
			$data['city'] = $data['mail_city'] = trim($rowData[13]);
			switch(trim(strtoupper($rowData[14]))) {
				case 'ONTARIO': $data['province2'] = $data['mail_province2'] = 'ON'; break;
				case 'ON': $data['province2'] = $data['mail_province2'] = 'ON'; break;
				case 'BRITISH COLUMBIA': $data['province2'] = $data['mail_province2'] = 'BC'; break;
				case 'BC': $data['province2'] = $data['mail_province2'] = 'BC'; break;
				case 'ALBERTA': $data['province2'] = $data['mail_province2'] = 'AB'; break;
				case 'AB': $data['province2'] = $data['mail_province2'] = 'AB'; break;
				case 'QUEBEC': $data['province2'] = $data['mail_province2'] = 'QC'; break;
				case 'QC': $data['province2'] = $data['mail_province2'] = 'QC'; break;
				case 'NS': $data['province2'] = $data['mail_province2'] = 'NS'; break;
				case 'CA': $data['province2'] = $data['mail_province2'] = 'CA'; break;
				default: $data['province2'] = $data['mail_province2'] = 'ON'; break;
			}
			switch(trim(strtoupper($rowData[15]))) {
				case 'CANADA': $data['country2'] = $data['mail_country2'] = 'CA'; break;
				case 'USA': $data['country2'] = $data['mail_country2'] = 'US'; break;
				default: $data['country2'] = $data['mail_country2'] = 'CA'; break;
			}
			$data['postcode'] = $data['mail_postcode'] = trim(strtoupper($rowData[16]));
			$data['email'] = trim($rowData[17]);
			$data['website'] = trim($rowData[18]);
			$data['business_phone'] = trim($rowData[19]);
			$data['fax_number'] = trim($rowData[20]);
			$data['mobile_phone'] = trim($rowData[21]);
			$data['toll_free'] = trim($rowData[22]);
			$data['date_added'] = $DateCreated;
			/*
			if (trim(strtoupper($rowData[27])) == 'Y') $data['paytype_list'] = array('Credit Card');
			else                                       $data['paytype_list'] = array('Credit Card','Cash','Cheque');
			*/
			$data['paytype_list'] = array('Credit Card','Cash','Cheque','Ali');
			/*
			$RestrictedProductCode = trim($rowData[28]);
			if (empty($RestrictedProductCode) || ($RestrictedProductCode == NULL)) $RestrictedProductCode = '';
			
			if ($RestrictedProductCode) {
				if ($this->product_model->get_product($RestrictedProductCode)) {
					// Insert user_product table with this product only
					$data['product_list'] = array($RestrictedProductCode);
				} else {
					echo "Unknown RestrictedProductCode : ".$RestrictedProductCode."\n";
					exit;
				}
			} else {
				// Insert all products to user_product table
				$data['product_list'] = array_keys($product_list);
			}
			*/
			$data['product_list'] = array_keys($product_list);
			
			foreach($data['product_list'] as $p) {
				$data['product_commission_'.$p] = $product_list[$p]['commission'];
			}
			$data['licence_number'] = trim($rowData[29]);
			$tm = trim($rowData[30]);
			if (empty($tm) || ($tm == 'NULL')) {
				$data['licence_expire'] = '2017-02-15';		// 
			} else {
				$data['licence_expire'] = date("Y-m-d", $this->batch_model->unixstamp($tm));
			}

			$data['parent_user_id'] = 0;
			$data['receive_type'] = 'Cheque';
			$data['ip'] = '';
			$data['note'] = 'Imported User';
			$user_id = $this->user_model->update(0, $data, 0);
			echo "================================>>>>>>> " .$user_id. "\n";
		}
	}

	public function format_phone($phone) {
		$phone = preg_replace("/[^0-9]/", "", $phone);
		$len = strlen($phone);
		if ($len == 10) {
			$phone = substr($phone, 0, 3) . "-" . substr($phone, 3, 3) . "-" . substr($phone, 6);
		} else if ($len == 11) {
			$phone = substr($phone, 0, 1) . "-" . substr($phone, 1, 3) . "-" . substr($phone, 4, 3) . "-" . substr($phone, 7);
		} else {
			$head = substr($phone, 0, 1);
			if ($head == "1") {
				$phone = substr($phone, 0, 1) . "-" . substr($phone, 1);
			} else {
				$phone = $phone . " ";
			}
		}
		return $phone;
	}

	public function export() {
		$this->valid();
		set_time_limit(0);
		$this->load->model ( 'product_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'batch_model' );
		$this->load->model ( 'plan_model' );
		$this->load->model ( 'payment_model' );
		$this->load->model ( 'status_model' );
		$this->load->model ( 'customer_model' );
		$outdir = '/tmp/';
		$pattern = "/^([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,}))(.*)$/";
		
		$uploadFilename = 'OPL2_Sales_Report_' . date('Y.m.d_H.i.s.B') . '.xlsx';
		//$uploadFilename = 'OPL2_Sales_Report_2019.03.31_03.00.00.111.xlsx';
		$outfile = $outdir . $uploadFilename;
		/*
		$filename = DOWNLOADDIR . 'OPL_Sales_Report.xls';
		if (!file_exists($filename)) {
			exit("Can't find file: ".$filename."\n");
		}
		
		//$product_list = $this->product_model->product_list(1);
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		*/
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("AuroraTech Inc.")
			->setLastModifiedBy("Jack Wu")
			->setTitle("Office Document")
			->setSubject("Office Document")
			->setDescription("Generated using PHP classes.")
			->setKeywords("php")
			->setCategory("result file");
		
		
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->setTitle('Sheet1');
		$sheet->setCellValue('A1', 'Policy No');
		$sheet->setCellValue('B1', 'Plan Name ');
		$sheet->setCellValue('C1', 'Status');
		$sheet->setCellValue('D1', 'Type');
		$sheet->setCellValue('E1', 'First Name');
		$sheet->setCellValue('F1', 'Last Name');
		$sheet->setCellValue('G1', 'Gender');
		$sheet->setCellValue('H1', 'Birth Date');
		$sheet->setCellValue('I1', 'Address1');
		$sheet->setCellValue('J1', 'Address2');
		$sheet->setCellValue('K1', 'City');
		$sheet->setCellValue('L1', 'Province');
		$sheet->setCellValue('M1', 'Postal Code');
		$sheet->setCellValue('N1', 'Contact Phone');
		$sheet->setCellValue('O1', 'Contact Email');
		$sheet->setCellValue('P1', 'Notes');
		$sheet->setCellValue('Q1', 'Arrival Date');
		$sheet->setCellValue('R1', 'Application Date');
		$sheet->setCellValue('S1', 'Effective Date');
		$sheet->setCellValue('T1', 'Expiry Date');
		$sheet->setCellValue('U1', 'Trip  Length');
		$sheet->setCellValue('V1', 'Sum  Insured');
		$sheet->setCellValue('W1', 'Deductible Amout');
		$sheet->setCellValue('X1', 'Commission Rate');
		$sheet->setCellValue('Y1', 'Commission Amout');
		$sheet->setCellValue('Z1', 'Daily Rate');
		$sheet->setCellValue('AA1', 'Gross Premium');
		$sheet->setCellValue('AB1', 'Net Premium');
		$sheet->setCellValue('AC1', 'Fee1');
		$sheet->setCellValue('AD1', 'Fee2');
		$sheet->setCellValue('AE1', 'Amount Due');
		$sheet->setCellValue('AF1', 'Insured First Name');
		$sheet->setCellValue('AG1', 'Insured Last Name');
		$sheet->setCellValue('AH1', 'Birthdate');
		$sheet->setCellValue('AI1', 'Update Date');
		
		$product_list = $this->product_model->product_list(1);
		$status_list = $this->status_model->status_list();
		
		$para = array();
		$para['last_update'] = date('Y-m-d', time() - 86400) . " 00:00:00";
		$para['last_update2'] = date('Y-m-d', time() - 86400) . " 23:59:59";
		//$para['last_update'] =  "2019-03-30 00:00:00";
		//$para['last_update2'] = "2019-03-30 23:59:59";
		$para['product_short'] = 'OPL';
		$plansOPL = $this->plan_model->plan_search($para, 0);
		$para['product_short'] = 'JFC';
		$plansJFC = $this->plan_model->plan_search($para, 0);
		
		$plans = array_merge($plansOPL, $plansJFC);
		$sz = sizeof($plans);
		echo "Total Record: " . $sz . "; OPL: " . sizeof($plansOPL) . "; JFC: " . sizeof($plansJFC) . "; \n";
		//$sheet = $objPHPExcel->setActiveSheetIndex(0);
//		print_r(get_class_methods(get_class($objPHPExcel)));

		$row = 2;
		foreach ($plans as $plan) {
			if ($plan['status_id'] <= Plan_model::QUOTE) continue;  // Skip Quote status
			if ($plan['status_id'] == Plan_model::CANCEL) {
				if ($this->payment_model->check_payment_period($plan['plan_id'], $para['last_update'], $para['last_update2'])) {
					continue;
				}
			}
			$sheet->setCellValue('A'.$row, $plan['policy']);
			$b = '';
			if ($plan['product_short'] == 'OPL') {
				if ($plan['stable_condition'] == 1) $b = "With Stable Pre-existing Medical Condition Coverage";
				else if ($plan['stable_condition'] == 2) $b = "Without Stable Pre-existing Medical Condition Coverage";
			} else {
				// $plan['product_short'] == 'JFC'
			}
			$sheet->setCellValue('B'.$row, $b);
			$status_str = $status_list[$plan['status_id']]['name'];
			$premium = $plan['premium'];
			if (($plan['product_short'] == 'JFC') && ($premium > 470)) {
				$premium = 470;
			}
			if ($plan['status_id'] == Plan_model::SOLD) $status_str = 'New';
			if ($plan['status_id'] == Plan_model::CHANGED) $status_str = 'Change';
			if ($plan['status_id'] == Plan_model::CLAIMED) {
				$status_str = 'Paid';
				$payrow = $this->payment_model->get_last_payment($plan['plan_id'], $plan['apply_date']);
				if ($payrow && empty($payrow['ispaid'])) {
					$status_str = 'Sold';
				}
			}
			if ($plan['status_id'] == Plan_model::REFUND) {
				//$status_str = 'Refund';
				$status_str = 'Change';
				$payment = $this->payment_model->get_payment_by_id($plan['payment_id'], $plan['apply_date']);
				if ($payment) {
					$premium += $payment['amount'] - $payment['admin_fee'];
				}
				$plan['expiry_date'] = $plan['refund_date'];
				$plan['totaldays'] = 1 + (strtotime($plan['refund_date']) - strtotime($plan['effective_date'])) / 86400;
			}
			if (empty($plan['firstname'])) $plan['firstname'] = '.';
			if (empty($plan['lastname'])) $plan['lastname'] = '.';
			if (empty($plan['gender'])) $plan['gender'] = '.';
			
			$plan['firstname'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['firstname']);
			$plan['lastname'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['lastname']);
			$plan['suite_number'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['suite_number']);
			$plan['street_number'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['street_number']);
			$plan['street_name'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['street_name']);
			$plan['city'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['city']);
			$plan['postcode'] = preg_replace('/[^\x20-\x7f]/', '.', $plan['postcode']);
				
			$sheet->setCellValue('C'.$row, $status_str);
			$sheet->setCellValue('D'.$row, $plan['isfamilyplan'] ? "Family" : "Single");
			$sheet->setCellValue('E'.$row, $plan['firstname']);
			$sheet->setCellValue('F'.$row, $plan['lastname']);
			$sheet->setCellValue('G'.$row, substr($plan['gender'], 0, 1));
			$sheet->setCellValue('H'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['birthday'] . ' 00:00:00 UTC')));
			//$sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME2);
			$sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			
			$address1 = $plan['street_number'] . " " . $plan['street_name']; // [Address1] field text must be 50 characters maximum
			if (strlen($address1) > 49) $address1 = substr($address1, 0, 49);
			$sheet->setCellValue('I'.$row, $address1); // Address1
			$sheet->setCellValue('J'.$row, ($plan['suite_number']) ? " Suite " . $plan['suite_number'] : ""); // Address2
			$sheet->setCellValue('K'.$row, $plan['city']); // City
			$sheet->setCellValue('L'.$row, $plan['province2']); // Provincec
			$sheet->setCellValue('M'.$row, $plan['postcode']); // Postal Code
			$sheet->setCellValue('N'.$row, $this->format_phone($plan['contact_phone'])); // Contact Phone
			$mlArr = array();
			$mailaddr = '';
			$r = preg_match($pattern, $plan['contact_email'], $mlArr);
			if ($r) {
				$mailaddr = $mlArr[1];
			}
			$sheet->setCellValue('O'.$row, $mailaddr); // Contact Email
			$note = $plan['note'];		// [Notes] field text should be 255 characters maximum
			if (strlen($note) > 255) $note = substr($note, 0, 254);
			$sheet->setCellValue('P'.$row, $note); // Notes
			$sheet->setCellValue('Q'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['arrival_date'] . ' 00:00:00 UTC'))); // Arrival Date
			$sheet->getStyle('Q'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$sheet->setCellValue('R'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['apply_date'] . ' 00:00:00 UTC'))); // Application Date
			$sheet->getStyle('R'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$sheet->setCellValue('S'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['effective_date'] . ' 00:00:00 UTC'))); // Effective Date
			$sheet->getStyle('S'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$sheet->setCellValue('T'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['expiry_date'] . ' 00:00:00 UTC'))); // Expiry Date
			$sheet->getStyle('T'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$sheet->setCellValue('U'.$row, $plan['totaldays']); // Trip  Length
			$sheet->setCellValue('V'.$row, sprintf("%0.2f",$plan['sum_insured'])); // Sum  Insured
			$sheet->getStyle('V'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('W'.$row, sprintf("%0.2f",$plan['deductible_amount'])); // Deductible Amout
			$sheet->getStyle('W'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('X'.$row, (empty($premium) || ($premium == 0)) ? 0 : (float)($plan['commission_amount'] * 100 / $premium)); // Commission Rate
			$sheet->setCellValue('Y'.$row, sprintf("%0.2f",$plan['commission_amount'])); // Commission Amout
			$sheet->getStyle('Y'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('Z'.$row, sprintf("%0.2f",$plan['dailyrate'])); // Daily Rate
			$sheet->getStyle('Z'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('AA'.$row, sprintf("%0.2f",$premium)); // Gross Premium
			$sheet->getStyle('AA'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('AB'.$row, sprintf("%0.2f",$premium)); // Net Premium
			$sheet->getStyle('AB'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
			$sheet->setCellValue('AC'.$row, 0); // Fee1
			$sheet->setCellValue('AD'.$row, 0); // Fee2
			$sheet->setCellValue('AE'.$row, 0); // Amount Due
			$sheet->setCellValue('AF'.$row, $plan['firstname']); // Insured First Name
			$sheet->setCellValue('AG'.$row, $plan['lastname']); // Insured Last Name
			$sheet->setCellValue('AH'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['birthday'] . ' 00:00:00 UTC'))); // Birthdate
			$sheet->getStyle('AH'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$sheet->setCellValue('AI'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['last_update'] . ' UTC'))); // Update Date
			$sheet->getStyle('AI'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
			$row++;
			if ($plan['isfamilyplan']) {
				$customers = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
				foreach ($customers as $c) {
					$c['firstname'] = preg_replace('/[^\x20-\x7f]/', '.', $c['firstname']);
					$c['lastname'] = preg_replace('/[^\x20-\x7f]/', '.', $c['lastname']);
						
					$sheet->setCellValue('A'.$row, $plan['policy']);
					$sheet->setCellValue('B'.$row, $b);
					$sheet->setCellValue('C'.$row, $status_str);
					$sheet->setCellValue('D'.$row, "Family");
					$sheet->setCellValue('E'.$row, $c['firstname']);
					$sheet->setCellValue('F'.$row, $c['lastname']);
					$sheet->setCellValue('G'.$row, $c['gender']);
					$sheet->setCellValue('H'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($c['birthday'] . ' 00:00:00 UTC')));
					$sheet->getStyle('H'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('I'.$row, $address1); // Address1
					$sheet->setCellValue('J'.$row, ($plan['suite_number']) ? "Suite " . $plan['suite_number'] : ""); // Address2
					$sheet->setCellValue('K'.$row, $plan['city']); // City
					$sheet->setCellValue('L'.$row, $plan['province2']); // Provincec
					$sheet->setCellValue('M'.$row, $plan['postcode']); // Postal Code
					$sheet->setCellValue('N'.$row, $this->format_phone($plan['contact_phone'])); // Contact Phone
					$sheet->setCellValue('O'.$row, $plan['contact_email']); // Contact Email
					$sheet->setCellValue('P'.$row, $note); // Notes
					$sheet->setCellValue('Q'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['arrival_date'] . ' 00:00:00 UTC'))); // Arrival Date
					$sheet->getStyle('Q'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('R'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['apply_date'] . ' 00:00:00 UTC'))); // Application Date
					$sheet->getStyle('R'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('S'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['effective_date'] . ' 00:00:00 UTC'))); // Effective Date
					$sheet->getStyle('S'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('T'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['expiry_date'] . ' 00:00:00 UTC'))); // Expiry Date
					$sheet->getStyle('T'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('U'.$row, $plan['totaldays']); // Trip  Length
					$sheet->setCellValue('V'.$row, sprintf("%0.2f",$plan['sum_insured'])); // Sum  Insured
					$sheet->getStyle('V'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('W'.$row, sprintf("%0.2f",$plan['deductible_amount'])); // Deductible Amout
					$sheet->getStyle('W'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('X'.$row, empty($premium) ? 0 : sprintf("%0.2f",$plan['commission_amount'] * 100 / $premium)); // Commission Rate
					$sheet->getStyle('X'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('Y'.$row, sprintf("%0.2f",$plan['commission_amount'])); // Commission Amout
					$sheet->getStyle('Y'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('Z'.$row, $plan['dailyrate']); // Daily Rate
					$sheet->setCellValue('AA'.$row, sprintf("%0.2f",$premium)); // Gross Premium
					$sheet->getStyle('AA'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('AB'.$row, sprintf("%0.2f",$premium)); // Net Premium
					$sheet->getStyle('AB'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
					$sheet->setCellValue('AC'.$row, 0); // Fee1
					$sheet->setCellValue('AD'.$row, 0); // Fee2
					$sheet->setCellValue('AE'.$row, 0); // Amount Due
					$sheet->setCellValue('AF'.$row, $c['firstname']); // Insured First Name
					$sheet->setCellValue('AG'.$row, $c['lastname']); // Insured Last Name
					$sheet->setCellValue('AH'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($c['birthday'] . ' 00:00:00 UTC'))); // Birthdate
					$sheet->getStyle('AH'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$sheet->setCellValue('AI'.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($plan['last_update'] . ' UTC'))); // Update Date
					$sheet->getStyle('AI'.$row)->getNumberFormat()->setFormatCode('m/d/yyyy h:mm:ss AM/PM');
					$row++;
				}
			}
		}
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($outfile);
		echo "Save to : " . $outfile . "\n";
		$uploaded = FALSE;
		for ($i = 0; $i < 5; $i++) {
			$uploaded = $this->ftp($outfile, $uploadFilename);
			if ($uploaded) {
				// unlink($outfile);
				break;
			}
			sleep(60); // wait 1 minute too retry
		}

		if (!$uploaded) {
			$this->load->model("mymail_model");
			$this->mymail_model->send_mymail('wqjyhggg@gmail.com', 'JF upload error', "File: " . $outfile);
			$this->mymail_model->send_mymail('IT@jfgroup.ca', 'JF upload error', "File: " . $outfile, array($outfile));
		}
	}

	public function delbatch() {
		$batch = 1084;
		$sql = "SELECT * FROM plan WHERE batch_number='".(int)$batch."'";
		echo $sql."\n";
		$rt = $this->db->query($sql)->result_array();
		foreach ($rt  as $rc) {
			$sql = "DELETE FROM payment WHERE plan_id='".(int)$rc['plan_id']."'";
			echo $sql."\n";
			$this->db->query($sql);
		}
		$sql = "DELETE FROM plan WHERE batch_number='".(int)$batch."'";
		echo $sql."\n";
		$this->db->query($sql);
	}

  public function gerenate_pdf()
	{
		$this->valid();
		set_time_limit(0);

    $batchArr = array("3251","3294","3295","3296","3297","3298");
    $beuser = $this->session->userdata('beuser');
		$this->load->model('plan_model');
    $this->load->model('customer_model');
    $this->load->model('product_model');
    $this->load->model('paytype_model');
    $this->load->model('status_model');
    $this->load->model('payment_model');

    foreach ($batchArr as $batchno) {
      if ($plans = $this->plan_model->plan_search(array("batch_number"=>$batchno))) {
        foreach ($plans as $plan) {
          $plan_id = $plan['plan_id'];
          $data['beuser'] = $beuser;
          $data['plan'] = $plan;
          $data['pdf_enable'] = empty($beuser['pdf_product']) ? array() : json_decode($beuser['pdf_product']);
          $data['emailaddr'] = $plan['contact_email'];
          $data['withlogo'] = 1;
          $data['withprice'] = 0;
		
          $product = $this->product_model->get_product($plan['product_short']);
          $data['payment'] = '';
          $data['plan_full_name'] = $product ? $product['full_name'] : '';
          $data['customer'] = $this->customer_model->get_customer_by_id($data['plan']['customer_id']);
          $data['customers'] = $this->customer_model->get_customer_by_parent_id($data['plan']['customer_id']);
          $data['paytype_list'] = $this->paytype_model->paytype_list();
          $data['status_list'] = $this->status_model->status_list();
          $data['html_model'] = $this->html_model;
          
          if ($data['plan']['product_short'] == 'OPL') {
            $data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_opl',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFVTC') {
            $data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFR') {
            $data['insurable_options'] = $this->load->view('plan/detail_opl', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jfr',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JUS') {
            $data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jus',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'NUS') {
            $data['insurable_options'] = $this->load->view('plan/detail_jus', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_nus',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFS') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFE') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'BHS') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JES') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFPL') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFSL') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFGD') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JESP') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jes',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFC') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFP') {
            $data['insurable_options'] = $this->load->view('plan/detail_jes', $data, TRUE);
            $data['special_note'] = $this->load->view('plan/pdf_note_jfc',$data, TRUE);
          } else if ($data['plan']['product_short'] == 'TOP') {
            $data['insurable_options'] = '';
            $data['special_note'] = $this->load->view('plan/top/pdf_note_top',$data, TRUE);
            $files = array(
            'TOP_Policy.pdf' => DOWNLOADDIR . 'TOP_Policy.pdf',
            'TOP_Baggage_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Baggage_Claim_Form.pdf',
            'TOP_Cancellation_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Cancellation_Claim_Form.pdf',
            'TOP_Medical_Claim_Form.pdf' => DOWNLOADDIR . 'TOP_Medical_Claim_Form.pdf',
            'TOP_Benefit_Summary.pdf' => DOWNLOADDIR . 'TOP_Benefit_Summary.pdf'
            );
          } else {
            $data['insurable_options'] = $this->load->view('plan/detail_other', $data, TRUE);
          }
          
          $policy_file = "confirm_".$batchno.$plan_id.".pdf";
          //$policy_file = "C:\Users\Administrator\AppData\Local\Temp\Policy";
          $data['title_txt'] = 'Policy';
          $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
          $data['hadheaderfooter'] = 0;
          if ($data['plan']['product_short'] == 'JFVTC') {
            $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
            if ($data['withlogo']) {
              $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
            }
            $data['hadheaderfooter'] = 1;
            $html = $this->load->view('plan/pdf_jfvtc', $data, TRUE);
          } else if (($data['plan']['product_short'] == 'JFSL') || ($data['plan']['product_short'] == 'JFGD')) {
            $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
            if ($data['withlogo']) {
              $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
            }
            $data['hadheaderfooter'] = 1;
            $html = $this->load->view('plan/pdf_jfsl', $data, TRUE);
          } else if ($data['plan']['product_short'] == 'JFPL') {
            $mpdf = new mPDF('c', 'A4', 0, '', $mgl = 0, $mgr = 0, $mgt = 15, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P');
            if ($data['withlogo']) {
              $mpdf->SetHTMLHeader('<img style="width:100%;" src="'.base_url().'image/pdf_header.png" />');
            }
            // $mpdf->SetHTMLFooter('<img style="width:100%;" src="'.base_url().'image/pdf_footer.png" />');
            $data['hadheaderfooter'] = 1;
            $html = $this->load->view('plan/pdf', $data, TRUE);
          } else {
            $mpdf = new mPDF('c');
            $html = $this->load->view('plan/pdf', $data, TRUE);
          }
          $mpdf->writeHTML($html);
          $mpdf->Output(DOWNLOADDIR . "tmppdf/" . $policy_file, 'F');
          echo "https://agent.jfgroup.ca/tmppdf/".$policy_file."\n";
        }
			}
		}
	}

	public function test() {
    //$this->load->model('plan_history_model');
    //$sql = "SELECT * FROM `plan` WHERE batch_number=3357";
    $sql = "SELECT * FROM `user` WHERE user_id>2810 AND user_group_id=105";
    //$sql = "SELECT * FROM `plan` WHERE batch_number=3321";
    //$sql = "SELECT * FROM `plan` WHERE batch_number=3346";
    $rt = $this->db->query($sql)->result_array();
    foreach ($rt as $rc) {
      $sql = "INSERT ignore INTO user_product (user_id, product_short, commission) values (".$rc["user_id"].",'TOP', 30)";
      echo $sql."\n";
      if ($this->db->query($sql)) {
	;
      } else {
	echo "Fail\n";
      }
    }
    exit;
	}
}
