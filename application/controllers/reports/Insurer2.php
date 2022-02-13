<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Insurer extends MY_Controller
{
  /**
   * Index Page for this controller.
   */
  public function index()
  {
    $beuser = $this->func_model->verify_login();
    $data = array();
    $data['beuser'] = $beuser;

    $this->load->model('product_model');
    $this->load->model('report_model');

    $data['csrf'] = array(
      'name' => $this->security->get_csrf_token_name(),
      'value' => $this->security->get_csrf_hash()
    );
    $data['title_txt'] = 'Sales Report to Insurer';
    $data['top_menu'] = $this->menu_model->load_top_menu();
    $data['menu'] = $this->menu_model->load_meun();
    $data['action_url'] = current_url();

    $data['product_short'] = $this->input->post('product_short');
    $data['payment_added_from'] = empty($this->input->post('payment_added_from'))?date("Y-m-d"):$this->input->post('payment_added_from');
    $data['payment_added_to'] = empty($this->input->post('payment_added_to'))?date("Y-m-d"):$this->input->post('payment_added_to');

    $data['product_list'] = $this->product_model->get_available_product_list();
    $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_sales_report_insurer2($data);
    if ($this->input->post('export')) {
      return $this->export_list($data);
    }

    $data['export_list'] = base_url("reports/insurer/export_list");
    $this->load->model('region_model');
    $this->load->common('reports/insurer', $data);
  }

  private function export_list($data)
  {
    //echo "<pre>";
    //print_r($data['report_data']);die('============');

    $w = WriterFactory::create(Type::XLSX); // for XLSX files
    $kArr = array(
      'policy' => 'Policy Number',
      'firstname' => 'First Name',
      'lastname' => 'Last Name',
      'gender' => 'Gender',
      'status_id' => 'Status',
      'age' => 'Age',
      'birthday' => 'Birth Date',
      'address' => 'Address',
      'city' => 'City',
      'province' => 'Province',
      'postcode' => 'Postcode',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'total_days' => 'Number of Days',
      'sum_insured' => 'Sum Insured',
      'deductible_amount' => 'Deductible Amount',
      'daily_rate' => 'Daily Rate',
      'policy_premium' => 'Policy Premium',
      'commission_rate_jf' => 'Commission Rate to JF',
      'merchant_fee_per' => 'Merchant Fee(Credit Card Fee)%',
      'claims_handling_fee_per' => 'Claims Handling',
      'commission_amount' => 'Commission Amount',
      'merchant_fee' => 'Merchant Fee(Credit Card Fee)',
      'claims_handling_fee' => 'Claims Handling Fee',
      'net_premium' => 'Net Premium',
      'total_compensation_per' => 'Total Compensation Rate(%)',
      'total_compensation' => 'Total Compensation Amount',
      'coverage' => 'Coverage',
    );

    $tmpfname = "/tmp/jf_test.xlsx";

    $w->openToBrowser("Sales_Report_to_Insurer_" . date('Ymd') . ".xlsx");
    $arr = array();
    foreach ($kArr as $k => $v) {
      $arr[] = $v;
    }
    $w->addRow($arr);

    foreach ($data['report_data'] as $data) {
      $arr = array();
      foreach ($kArr as $k => $v) {
        $arr[] = $data[$k];
      }
      $w->addRow($arr);
    }
    $w->close();
  }
}
