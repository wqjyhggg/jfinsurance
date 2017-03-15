<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {
    /**
     *  Return menu on left
     *  
     *  @return null / array on find.     
     */
	public function load_meun() {
		$meunArr = array(
			0 =>array(),
			1 => array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('batch/import') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					"<a href='" . base_url('batch/other') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_other_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_agent' ) . "</a>",
							"<a href='" . base_url('reports/jf') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_jf' ) . "</a>",
							"<a href='" . base_url('reports/insurer') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_insurer' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/claimreport') . "' class='leftmeun'>" . $this->lang->line ( 'text_claim_report' ) . "</a>",
							"<a href='" . base_url('reports/renewal') . "' class='leftmeun'>" . $this->lang->line ( 'text_renewal_report' ) . "</a>",
							"<a href='" . base_url('reports/refund') . "' class='leftmeun'>" . $this->lang->line ( 'text_refund_report' ) . "</a>",
							"<a href='" . base_url('reports/commission') . "' class='leftmeun'>" . $this->lang->line ( 'text_commission_report' ) . "</a>",
							"<a href='" . base_url('reports/agent_commission') . "' class='leftmeun'>" . $this->lang->line ( 'text_agent_commission_report' ) . "</a>",
						),
					
					"<a href='" . base_url('claim') . "' class='leftmeun'><i class='fa fa-files-o'></i>" . $this->lang->line ( 'text_claim' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'><i class='fa fa-shield'></i>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('user') . "' class='leftmeun'><i class='fa fa-users'></i>" . $this->lang->line ( 'text_agent' ) . "</a>",
					"<a href='" . base_url('pdf/pdflist') . "' class='leftmeun'><i class='fa fa-file-pdf-o'></i>" . $this->lang->line ( 'text_list_download_file' ) . "</a>",
					"<a href='" . base_url('setting') . "' class='leftmeun'><i class='fa fa-cog'></i>" . $this->lang->line ( 'text_api' ) . "</a>",
			),
			2 => array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('batch/import') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					"<a href='" . base_url('batch/other') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_other_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_agent' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/claimreport') . "' class='leftmeun'>" . $this->lang->line ( 'text_claim_report' ) . "</a>",
							"<a href='" . base_url('reports/renewal') . "' class='leftmeun'>" . $this->lang->line ( 'text_renewal_report' ) . "</a>",
							"<a href='" . base_url('reports/commission') . "' class='leftmeun'>" . $this->lang->line ( 'text_commission_report' ) . "</a>",
							"<a href='" . base_url('reports/agent_commission') . "' class='leftmeun'>" . $this->lang->line ( 'text_agent_commission_report' ) . "</a>",						
						),
					
					"<a href='" . base_url('claim') . "' class='leftmeun'><i class='fa fa-files-o'></i>" . $this->lang->line ( 'text_claim' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'><i class='fa fa-shield'></i>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('user') . "' class='leftmeun'><i class='fa fa-users'></i>" . $this->lang->line ( 'text_agent' ) . "</a>",
				),
			3 =>array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('batch/import') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					"<a href='" . base_url('batch/other') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_other_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/jf') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_jf' ) . "</a>",
							"<a href='" . base_url('reports/insurer') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_insurer' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/refund') . "' class='leftmeun'>" . $this->lang->line ( 'text_refund_report' ) . "</a>",
						),
					"<a href='" . base_url('user/logout') . "' class='leftmeun'><i class='fa fa-power-off'></i>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			103 =>array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					"<a href='" . base_url('batch/import') . "' class='leftmeun'><i class='fa fa-upload'></i>" . $this->lang->line ( 'text_upload_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_agent' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/renewal') . "' class='leftmeun'>" . $this->lang->line ( 'text_renewal_report' ) . "</a>",
						),
					"<a href='" . base_url('product') . "' class='leftmeun'><i class='fa fa-shield'></i>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'><i class='fa fa-power-off'></i>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			104 =>array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_agent' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/renewal') . "' class='leftmeun'>" . $this->lang->line ( 'text_renewal_report' ) . "</a>",
						),
					"<a href='" . base_url('product') . "' class='leftmeun'><i class='fa fa-shield'></i>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('user') . "' class='leftmeun'><i class='fa fa-users'></i>" . $this->lang->line ( 'text_agent' ) . "</a>",
				),
			105 =>array(
					"<a href='" . base_url('plan') . "' class='leftmeun'><i class='fa fa-file-text-o'></i>" . $this->lang->line ( 'text_policy' ) . "</a>",
					array(
							"<a class='leftmeun'><i class='fa fa-file-excel-o'></i>" . $this->lang->line ( 'text_report' ) . "<span class='fa fa-chevron-down'></span></a>",
							"<a href='" . base_url('reports/agent') . "' class='leftmeun'>" . $this->lang->line ( 'text_report_to_agent' ) . "</a>",
							"<a href='" . base_url('reports/receivable') . "' class='leftmeun'>" . $this->lang->line ( 'text_receivable_report' ) . "</a>",
							"<a href='" . base_url('reports/renewal') . "' class='leftmeun'>" . $this->lang->line ( 'text_renewal_report' ) . "</a>",
						),
					"<a href='" . base_url('product') . "' class='leftmeun'><i class='fa fa-shield'></i>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'><i class='fa fa-power-off'></i>" . $this->lang->line ( 'text_logout' ) . "</a>",

				),
		);
		$behalf = "<a href='" . base_url('behalf') . "' class='leftmeun'><i class='fa fa-user'></i>" . $this->lang->line ( 'text_behalf' ) . "</a>";
		$unbehalf = "<a href='" . base_url('behalf/undo') . "' class='leftmeun'><i class='fa fa-user'></i>" . $this->lang->line ( 'text_unbehalf' ) . "</a>";
		$logout = "<a href='" . base_url('user/logout') . "' class='leftmeun'><i class='fa fa-power-off'></i>" . $this->lang->line ( 'text_logout' ) . "</a>";


		$user = $this->session->userdata('user');
		$beuser = $this->session->userdata('beuser');
		$onbehalf = 0;
	   	if (!$user) {
    		$group = 0;
    	} else {
    		$group = $beuser['user_group_id'];
    		if ($user['user_id'] != $beuser['user_id']) {
    			$onbehalf = 1;
    		}
    	}
    	$menu = $meunArr[$group];
    	if ($user && ($user['user_group_id'] != 105) && ($user['user_group_id'] != 103) && ($user['user_group_id'] != 3 )) {
			if ($onbehalf) {
				array_push($menu, $unbehalf);
				array_push($menu, $logout);
			} else {
				array_push($menu, $behalf);
				array_push($menu, $logout);
			}
		}
		return $menu;
	}

    /**
     *  Return top menu
     *  
     *  @return null / array on find.     
     */
	public function load_top_menu() {
		$meunArr = array(
			0 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/login') . "' class='leftmeun'>" . $this->lang->line ( 'text_agent_login' ) . "</a>",
				),
			1 => array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			2 => array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			3 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			103 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			104 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
			105 =>array(
					"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_home' ) . "</a>",
					"<a href='" . base_url('product') . "' class='leftmeun'>" . $this->lang->line ( 'text_our_products' ) . "</a>",
					"<a href='" . base_url('downloads') . "' class='leftmeun'>" . $this->lang->line ( 'text_downloads' ) . "</a>",
					"<a href='" . base_url('myhome') . "' class='leftmeun'>" . $this->lang->line ( 'text_myhome' ) . "</a>",
					//"<a href='" . base_url() . "' class='leftmeun'>" . $this->lang->line ( 'text_languages' ) . "</a>",
					"<a href='" . base_url('user/logout') . "' class='leftmeun'>" . $this->lang->line ( 'text_logout' ) . "</a>",
				),
		);
		$user = $this->session->userdata('user');
		$beuser = $this->session->userdata('beuser');
		$behalf = "<a href='" . base_url('behalf') . "' class='leftmeun'>" . $this->lang->line ( 'text_behalf' ) . "</a>";
		$unbehalf = "<a href='" . base_url('behalf/undo') . "' class='leftmeun'>" . $this->lang->line ( 'text_unbehalf' ) . " (" . $beuser['username'] . ") </a>";
	
		$onbehalf = 0;
	   	if (!$user) {
    		$group = 0;
    	} else {
    		$group = $user['user_group_id'];
    		if ($user['user_id'] != $beuser['user_id']) {
    			$onbehalf = 1;
    		}
    	}
		$menu = $meunArr[$group];
		if (($group == 1) || ($group == 2) ||($group == 104)) {
			if ($onbehalf) {
				array_push($menu, $unbehalf);
			} else {
				array_push($menu, $behalf);
			}
		}
		$rarr = array('islogin' => $group, 'menu' => $menu);
    	return $rarr;
	}
}
