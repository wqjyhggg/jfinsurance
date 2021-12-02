<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class Annual extends MY_Controller
{
  public $annual = "annual/";
  public $uploadtype = array('pdf', 'xls', 'xlsx'/*, 'doc', 'docx'*/);
  public $data = array();

  public function index()
  {
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

    if ($beuser['user_group_id'] > 100) {
      $this->data['agent_id'] = $beuser['user_id'];
    } else {
      $this->data['agent_id'] = empty($this->input->post_get('agent_id')) ? "" : (int)$this->input->post_get('agent_id');
      // $this->data['user_list'] = $this->user_model->get_available_user_list();
    }
    $user = $this->session->userdata('user');
    $user_id = 0;
    if ($user) {
      $user_id = $user["user_id"];
    }
    $year = $this->input->post('year');
    if (empty($year)) {
      $year = date("Y") - 1;
    }
    $this->data['beuser'] = $beuser;
    $this->data['year'] = $year;

    if ($this->data['agent_id']) {
      if ($this->input->post('submit')) {
        if ($beuser['user_group_id'] < 100) {
          $post = $this->input->post();
          $post['user_id'] = $beuser['user_id'];
          $post['date_time'] = date("Y-m-d H:i:s");
          $this->report_model->save_annual($this->data['agent_id'], $year, $user_id, $post);
        }
      }
      $this->data['record'] = $this->report_model->get_annual($this->data['agent_id'], $year, $user_id);
      if ($this->input->get('export')) {
        return $this->export($this->data['agent_id'], $year, $this->data['record']);
      }

      $this->data['record']["premium"] = array();
      $this->data['record']["commission"] = array();
      for ($i = 1; $i <= 12; $i++) {
        $this->data['record']["premium"][$i] = $this->report_model->get_month_payment($this->data['agent_id'], $year, $i, 'premium');
        if (!isset($this->data['record']["premium2"][$i])) {
          $this->data['record']["premium2"][$i] = $this->data['record']["premium"][$i];
        }
        $this->data['record']["commission"][$i] = $this->report_model->get_month_payment($this->data['agent_id'], $year, $i, 'commission');
        if (!isset($this->data['record']["commission2"][$i])) {
          $this->data['record']["commission2"][$i] = $this->data['record']["commission"][$i];
        }
      }
    }
    $this->data['export_url'] = base_url("reports/annual?export=1&agent_id=" . $this->data['agent_id'] . "&year=" . $this->data['year']);
    $this->load->common('reports/annual', $this->data);
  }

  private function export($agent_id, $record)
  {
    $data['style'] = $this->load->view('common/pdf_style', array(), TRUE);
    $mpdf = new mPDF('c');
    //todo may need separate commission pdf view
    $premium = $commission = 0;
    for ($i = 1; $i <= 12; $i++) {
      $premium += $record['premium2'][$i];
      $commission += $record['commission2'][$i];
    }
    $data['premium'] = $premium;
    $data['commission'] = $commission;
    $data['agent'] = $this->user_model->get_user_by_id($agent_id);
    $html = $this->load->view('reports/annual_pdf', $data, TRUE);
    $mpdf->writeHTML($html);
    $mpdf->Output("Annual_report.pdf", "I");
    /*
        $w = WriterFactory::create(Type::XLSX); // for XLSX files
        $w->openToBrowser("Annual_Report_" . date('Ymd') . ".xlsx");
        //$w->openToFile($tmpfname);
        $w->addRow(array('Month', 'Premium', 'Commission', 'Payment'));

        $premium = $record['premium']['1']; $commission = $record['commission']['1']; $payment = $record['payment']['1'];
        $w->addRow(array('January', $record['premium']['1'], $record['commission']['1'], $record['payment']['1']));

        $premium += $record['premium']['2']; $commission += $record['commission']['2']; $payment += $record['payment']['2'];
        $w->addRow(array('February', $record['premium']['2'], $record['commission']['2'], $record['payment']['2']));

        $premium += $record['premium']['3']; $commission += $record['commission']['3']; $payment += $record['payment']['3'];
        $w->addRow(array('March', $record['premium']['3'], $record['commission']['3'], $record['payment']['3']));
        
        $premium += $record['premium']['4']; $commission += $record['commission']['4']; $payment += $record['payment']['4'];
        $w->addRow(array('April', $record['premium']['4'], $record['commission']['4'], $record['payment']['4']));
        
        $premium += $record['premium']['5']; $commission += $record['commission']['5']; $payment += $record['payment']['5'];
        $w->addRow(array('May', $record['premium']['5'], $record['commission']['5'], $record['payment']['5']));
        
        $premium += $record['premium']['6']; $commission += $record['commission']['6']; $payment += $record['payment']['6'];
        $w->addRow(array('June', $record['premium']['6'], $record['commission']['6'], $record['payment']['6']));
        
        $premium += $record['premium']['7']; $commission += $record['commission']['7']; $payment += $record['payment']['7'];
        $w->addRow(array('July', $record['premium']['7'], $record['commission']['7'], $record['payment']['7']));
        
        $premium += $record['premium']['8']; $commission += $record['commission']['8']; $payment += $record['payment']['8'];
        $w->addRow(array('August', $record['premium']['8'], $record['commission']['8'], $record['payment']['8']));
        
        $premium += $record['premium']['9']; $commission += $record['commission']['9']; $payment += $record['payment']['9'];
        $w->addRow(array('September', $record['premium']['9'], $record['commission']['9'], $record['payment']['9']));
        
        $premium += $record['premium']['10']; $commission += $record['commission']['10']; $payment += $record['payment']['10'];
        $w->addRow(array('October', $record['premium']['10'], $record['commission']['10'], $record['payment']['10']));
        
        $premium += $record['premium']['11']; $commission += $record['commission']['11']; $payment += $record['payment']['11'];
        $w->addRow(array('November', $record['premium']['11'], $record['commission']['11'], $record['payment']['11']));
        
        $premium += $record['premium']['12']; $commission += $record['commission']['12']; $payment += $record['payment']['12'];
        $w->addRow(array('December', $record['premium']['12'], $record['commission']['12'], $record['payment']['12']));

        $w->addRow(array('Total', $premium, $commission, $premium));
        $w->addRow(array('Previous year total sales :' . $record['last_year_total']));
        
        $w->close();
        */
  }
}
