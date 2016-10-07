<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_Commission extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
        $beuser = $this->func_model->verify_login();
        $data = $this->set_data();
		$this->load->common('reports/agent_commission', $data);
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
        $this->load->model('product_model');
        $this->load->model('report_model');

        $data ['csrf'] = array (
            'name' => $this->security->get_csrf_token_name (),
            'value' => $this->security->get_csrf_hash ()
        );
        $data['title_txt'] = 'Agent Commission Report';
        $data['top_menu'] = $this->menu_model->load_top_menu();
        $data['menu'] = $this->menu_model->load_meun();
        $data['action_url'] = current_url();

        $data['agent_id'] = $this->input->post('agent_id');
        $data['payment_method'] = $this->input->post('payment_method');
        $data['payment_date_from'] = $this->input->post('payment_date_from');
        $data['payment_date_to'] = $this->input->post('payment_date_to');
        $data['paied'] = $this->input->post('paied');
        $data['minvalue'] = $this->input->post('minvalue');
        
        $data['user_list'] = $this->user_model->get_available_user_list();
        $data['report_data'] = empty($_POST) ? array() : $this->report_model->get_agent_commission_report($data);
        return $data;
	}
}
