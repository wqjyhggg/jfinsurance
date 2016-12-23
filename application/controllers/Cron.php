<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Cron extends MY_Controller {
	public $error;
	const FTP_HOST="72.142.65.148";
	const FTP_PORT=7790;
	const FTP_USER='jfu';
	const FTP_PASS='!Tgu7oPb';
	
	private function valid() {
		$this->error = '';

		if ((php_sapi_name() === 'cli')) {
			$this->load->model('setting_model');
			$this->setting_model->set_default_user();
			return TRUE;
		}
		show_error("ERROR", 404);
	}
	
	public function index() {
		if ($this->valid()) {
			die("OK\n");
		} else {
			die($this->error."\n");
		}
	}
	
	public function ftp() {
		$this->valid();
		$conn = ftp_connect(self::FTP_HOST, self::FTP_PORT) or die("Couldn't connect to " . self::FTP_HOST);
		$login_result = ftp_login($conn, self::FTP_USER, self::FTP_PASS);
		
		if (!$login_result) {
			die("can't login");
		}
		
		echo ftp_pwd($conn); // /
		
		// close the ssl connection
		ftp_close($conn);
	}

	public function import() {
		$this->valid();

		$filename = '/home/jackw/Downloads/agent_2016_12_19.xls';
		
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
			print_r($rowData);
			
			$data = array();
			if (empty($rowData[1])) {
				echo "Dactive user, skip\n";
				continue;
			}
			if ($this->user_model->check_username(0, $rowData[2])) {
				echo "This user are existed in system\n";
				continue;
			}
			$data['username'] = trim($rowData[2]);
			$data['password'] = trim($rowData[3]);
			switch(trim($rowData[4])) {
				case 'F': $data['user_group_id'] = 1; break;	// System Admin
				case 'J': $data['user_group_id'] = 2; break;	// Staff
				case 'A': $data['user_group_id'] = 3; break;	// Accounting
				case 'E': $data['user_group_id'] = 105; break;	// Agent
				case 'S': $data['user_group_id'] = 103; break;	// School
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
				echo "Unknown user region: ".$rowData[6]."\n";
				exit;
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
			if (trim(strtoupper($rowData[27])) == 'Y') $data['paytype_list'] = array('Credit Card');
			else                                       $data['paytype_list'] = array('Credit Card','Cash','Cheque');

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
			foreach($data['product_list'] as $p) {
				$data['product_commission_'.$p] = $product_list[$p]['commission'];
			}
			$data['licence_number'] = trim($rowData[29]);
			$tm = trim($rowData[30]);
			if (empty($tm) || ($tm == 'NULL')) {
				$data['licence_expire'] = '2016-01-01';		// Expired
			} else {
				$data['licence_expire'] = date("Y-m-d", $this->batch_model->unixstamp($tm));
			}

			$data['parent_user_id'] = 0;
			$data['receive_type'] = 'Cheque';
			$data['ip'] = '';
			$data['status'] = '1';
			$data['date_added'] = '';
			$data['note'] = 'Imported User';
			$user_id = $this->user_model->update(0, $data, 0);
			echo "================================>>>>>>> " .$user_id. "\n";
		}
	}

	public function export() {
		$this->valid();
		set_time_limit(0);
		$this->load->model ( 'product_model' );
		$this->load->model ( 'user_model' );
		$this->load->model ( 'batch_model' );
		$this->load->model ( 'plan_model' );
		$this->load->model ( 'status_model' );
		$outdir = '/tmp/';
		
		$filename = '/home/jackw/Downloads/OPL_Sales_Report.xls';
		if (!file_exists($filename)) {
			exit("Can't find file: ".$filename."\n");
		}
		
		//$product_list = $this->product_model->product_list(1);
		$outfile = $outdir . "OPL.xls";
		
		$fn = fopen($outfile, "w");
		
		if (!$fn) {
			die("Can't open file: ".$outfile." for output!\n");
		}

		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		
		$para = array();
		$para['apply_date'] = '2016-01-01';
		//$para['apply_date2'] = '2016-01-01';
		$para['apply_date'] = '2016-01-01';
		$para['product_short'] = 'OPL';
		
		$product_list = $this->product_model->product_list(1);
		$status_list = $this->status_model->status_list();
		$plans = $this->plan_model->plan_search($para, 3);
		$sz = sizeof($plans);

		$sheet = $objPHPExcel->setActiveSheetIndex(0);
//		print_r(get_class_methods(get_class($objPHPExcel)));
//		die("XX"); //XXXXXXXXXXXXXXXXXXXXXXX
		$row = 2;
		foreach ($plans as $plan) {
			$sheet->setCellValue('A'.$row, $plan['policy']);
			$sheet->setCellValue('B'.$row, $product_list[$plan['product_short']]['full_name']);
			$sheet->setCellValue('C'.$row, $status_list[$plan['status_id']]['name']);
			//$sheet->setCellValue('D'.$row, $status_list[$plan['status']]['name']);
			$sheet->setCellValue('E'.$row, $plan['firstname']);
			$sheet->setCellValue('F'.$row, $plan['lastname']);
			$sheet->setCellValue('G'.$row, $plan['gender']);
			$sheet->setCellValue('H'.$row, $plan['birthday']);
			$sheet->setCellValue('I'.$row, $plan['street_number'] . " " . $plan['street_name'] . ($plan['suite_number'] ? " Suite" . $plan['suite_number'] : "")); // Address1
			$sheet->setCellValue('J'.$row, ""); // Address2
			$sheet->setCellValue('K'.$row, $plan['city']); // City
			$sheet->setCellValue('L'.$row, $plan['province2']); // Provincec
			$sheet->setCellValue('M'.$row, $plan['postcode']); // Postal Code
			$sheet->setCellValue('N'.$row, $plan['contact_phone']); // Contact Phone
			$sheet->setCellValue('O'.$row, $plan['contact_email']); // Contact Email
			$sheet->setCellValue('P'.$row, $plan['note']); // Notes
			$sheet->setCellValue('Q'.$row, $plan['arrival_date']); // Arrival Date
			$sheet->setCellValue('R'.$row, $plan['apply_date']); // Application Date
			$sheet->setCellValue('S'.$row, $plan['effective_date']); // Effective Date
			$sheet->setCellValue('T'.$row, $plan['expiry_date']); // Expiry Date
			$sheet->setCellValue('U'.$row, $plan['totaldays']); // Trip  Length
			$sheet->setCellValue('V'.$row, $plan['sum_insured']); // Sum  Insured
			$sheet->setCellValue('W'.$row, $plan['deductible_amount']); // Deductible Amout
			// $sheet->setCellValue('X'.$row, $plan['gender']); // Commission Rate
			$sheet->setCellValue('Y'.$row, $plan['commission_amount']); // Commission Amout
			$sheet->setCellValue('Z'.$row, $plan['dailyrate']); // Daily Rate
			$sheet->setCellValue('AA'.$row, $plan['premium']); // Gross Premium
			// $sheet->setCellValue('AB'.$row, $plan['policy']); // Net Premium
			// $sheet->setCellValue('AC'.$row, $plan['policy']); // Fee1
			// $sheet->setCellValue('AD'.$row, $plan['policy']); // Fee2
			// $sheet->setCellValue('AE'.$row, $plan['policy']); // Amount Due
			$sheet->setCellValue('AF'.$row, $plan['firstname']); // Insured First Name
			$sheet->setCellValue('AG'.$row, $plan['lastname']); // Insured Last Name
			$sheet->setCellValue('AH'.$row, $plan['birthday']); // Birthdate
			$sheet->setCellValue('AI'.$row, $plan['last_update']); // Update Date
			$row++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($outfile);
		echo "Save to : " . $outfile . "\n";
	}
}
