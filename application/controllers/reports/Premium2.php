<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Premium2 extends MY_Controller
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
    $data['product_short'] = array_keys($data['product_short']);

    $data['report_data'] = $this->report_model->get_premium_report2($data);
    if ($this->input->post('export')) {
      return $this->export_list($data);
    }
    //echo "<pre>";
    //print_r($data); //XXXXXXXXXXXXX
    //echo "</pre>";
    //die("XXXX");
    $data['product_list'] = $this->product_model->get_available_product_list();

    $this->load->common('reports/premium2', $data);
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
      'add_time' => 'Sold Date',
      'effective_date' => 'Effective Date',
      'expiry_date' => 'Expire Date',
      'totaldays' => 'Number of Days',
      'days_used' => 'Days of Used',
      'sum_insured' => 'Sum Insured',
      'deductible_amount' => 'Deductible Amount',
      'sum_insured' => 'Total',
      'earned' => 'Earned',
      'unearned' => 'Unearned',
    );

    $w->openToBrowser("Premium_Report_" . date('Ymd') . ".xlsx");
    $w->addRow(array($data['payment_added_from']. " to " . $data['payment_added_to']));
    if (!empty($data['product_short'])) {
      $w->addRow($data['product_short']);
    }

    $arr = array();
    foreach ($kArr as $k => $v) {
      $arr[] = $v;
    }
    $w->addRow($arr);

    $total = $tearned = 0;
    foreach ($data['report_data'] as $record) {
      $earned = ($record['days_used']>0)? number_format(floatval($record['premium'])*floatval($record['days_used'])/floatval($record['totaldays']),2) : 0;
      $unearned = number_format(floatval($record['premium']) - $earned, 2);
      $total += floatval($record['premium']);
      $tearned += $earned;
      $arr = array();
      foreach ($kArr as $k => $v) {
        if ($k == "earned") {
          $arr[] = $earned;
        } else if ($k == "add_time") {
          $arr[] = ($record['ishead']==1)?substr($record[$k],0,10):'';
        } else if ($k == "days_used") {
          $arr[] = ($record[$k]>0)?$record[$k]:0;
        } else if ($k == "unearned") {
          $arr[] = $unearned;
        } else {
          $arr[] = $record[$k];
        }
      }
      $w->addRow($arr);
    }
    $arr = array();
    foreach ($kArr as $k => $v) {
      if ($k == "earned") {
        $arr[] = $tearned;
      } else if ($k == "unearned") {
        $arr[] = $total - $tearned;
      } else if ($k == "policy_premium") {
        $arr[] = $total;
      } else if ($k == "deductible_amount") {
        $arr[] = 'Total:';
      } else {
        $arr[] = '';
      }
    }
    $w->addRow($arr);
    $w->close();
  }
}
