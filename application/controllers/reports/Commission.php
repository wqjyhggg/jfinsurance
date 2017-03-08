<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
class Commission extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
        $data['beuser'] = $beuser;
        $this->load->model('region_model');
        $data['regions'] = $this->region_model->get_regions();
        $this->load->common('reports/commission', $data);
/*
        //todo when we need pdf, when we send out email? the logic is not clear yet
        $data['style'] = $this->load->view('common/pdf_style',$data, TRUE);
        $mpdf = new mPDF('c');
        //todo may need separate commission pdf view
        $html = $this->load->view('reports/commission', $data, TRUE);
        $mpdf->writeHTML($html);
        $mpdf->Output();
*/
    }

    private function set_data()
    {
    	$beuser = $this->session->userdata ( 'beuser' );
    	$this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Commission Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['region_id'] = empty($this->input->post('region_id')) ? $beuser['region_id'] : $this->input->post('region_id');
        $data['product_short'] = $this->input->post('product_short');
        $data['payment_added_from'] = $this->input->post('payment_added_from');
        $data['payment_added_to'] = $this->input->post('payment_added_to');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_commission_report($data);
       
        $data['export_list'] = base_url ( "reports/commission/export_list" );
        $data['export_form'] = $this->load->view ( 'reports/agent_export', $data, true);
        return $data;
	}

    function export_list() {
        $beuser = $this->func_model->verify_login(); 
        $this->load->model('product_model');
        $this->load->model('report_model');
        $data['agent_id'] = empty($this->input->get_post('agent_id')) ? 0 : (int)$this->input->get_post('agent_id');
        $data['region_id'] = empty($this->input->get_post('region_id')) ? $beuser['region_id'] : $this->input->get_post('region_id');
        $data['product_short'] = $this->input->get_post('product_short');
        $data['payment_added_from'] = $this->input->get_post('payment_added_from');
        $data['payment_added_to'] = $this->input->get_post('payment_added_to');
        $data['payment_date_from'] = $this->input->get_post('payment_date_from');
        $data['payment_date_to'] = $this->input->get_post('payment_date_to');
        
        $data['product_list'] = $this->product_model->get_available_product_list();
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = $this->report_model->get_commission_report($data);
        
        // echo "<pre>";
        // print_r($data['report_data']);die('============');

        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $kArr = array(
                'added' => 'Date',
                'policy' => 'Policy Number',
                'status' => 'Policy Status',
                'up_insuer' => 'Insurer',
                'customer_name' => 'Customer Name',
                'effective_date' => 'Effective Date',
                'expiry_date' => 'Expiry Date',
                'total_days' => 'Trip Length',
                'premium' => 'Premium Amount',
                'premiumispaid' => 'Premium Pay Status',
                'rate' => 'Commission Rate',
                'amount' => 'Commission Amount',
                'ispaid' => 'Commission Pay Status');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Sheet1');
        
        $col = 'A';
        $row = 1;
        foreach ($data['report_data'] as $datas) {
        	$sheet->setCellValue($col.$row, 'Agent Name:'); $col++;
        	$sheet->setCellValue($col.$row, $datas['agent']['firstname'] . ' ' . $datas['agent']['lastname']); $col++;
        	$col++;
        	$col++;
        	$sheet->setCellValue($col.$row, 'Payment Method:'); $col++;
        	$sheet->setCellValue($col.$row, $datas['agent']['receive_type']); $col++;
        	 
        	$row++; $col = 'A';
        	$col++;
        	$col++;
        	$col++;
        	$col++;
        	$sheet->setCellValue($col.$row, 'Mailling Addrerss: ' . ' ' . $datas['agent']['mail_city'] . ',' . $datas['agent']['mail_province2'] . ',' . $datas['agent']['mail_postcode']); $col++;
        	 
        	$row++; $col = 'A';
        	$sheet->setCellValue($col.$row, 'Commission Cheque Title: '); $col++;
        	$sheet->setCellValue($col.$row, $datas['agent']['note']); $col++;
        	 
        	$row++; $col = 'A';
        	
        	$row++; $col = 'A';
            foreach ($kArr as $k => $v) {
        		$sheet->setCellValue($col.$row, $v); $col++;
            }
        	
            $total_premium = 0; $total_commission = 0;
        	foreach ($datas['data'] as $record) {
        		$total_premium += $record['premium']; $total_commission += $record['amount'];
        		$row++; $col = 'A';
                
        		$sheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($record['added'] . ' EST')));
                $sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        		$col++;
        		
                $sheet->setCellValue($col.$row, $record['policy']); $col++;
                $sheet->setCellValue($col.$row, $record['status']); $col++;
                $sheet->setCellValue($col.$row, $record['up_insuer']); $col++;
                $sheet->setCellValue($col.$row, $record['customer_name']); $col++;
                $sheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($record['effective_date'] . ' 00:00:00 EST'))); $col++;
                $sheet->setCellValue($col.$row, PHPExcel_Shared_Date::PHPToExcel(strtotime($record['expiry_date'] . ' 00:00:00 EST'))); $col++;
                $sheet->setCellValue($col.$row, $record['total_days']); $col++;

                $sheet->setCellValue($col.$row, $record['premium']); 
                $sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $col++;
                
                $sheet->setCellValue($col.$row, $record['premiumispaid'] ? 'Paid' : '-'); $col++;

                $sheet->setCellValue($col.$row, $record['rate'] / 100.0);
                $sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
                $col++;
                
                $sheet->setCellValue($col.$row, $record['amount']);
                $sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                $col++;
                
                $sheet->setCellValue($col.$row, $record['ispaid'] ? 'Paid' : '-'); $col++;
        	}
        	
        	$row++;
        	$sheet->setCellValue('A'.$row, 'TOTAL');
        	$sheet->setCellValue('I'.$row, $total_premium);
        	$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        	$sheet->setCellValue('L'.$row, $total_commission);
        	$sheet->getStyle($col.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
        	
        	$col = 'A';
        	for($i = 0; $i < $col; $i++) {
        		$sheet->getColumnDimension($col++)->setAutoSize(true);
        	}
        }
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $outfile = 'Commission_Report_' . date('Ymd') . '.xlsx';
        if (0) {
        	$objWriter->save($outfile);
        } else {
	        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	        header('Content-Disposition: attachment;filename="' . $outfile . '"');
	        header('Cache-Control: max-age=0');
	        
	        $objWriter->save('php://output');
        }
    }
}
