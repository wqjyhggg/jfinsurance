<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mymail_model extends CI_Model {
	public $config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',	// 'smtp_host' => 'ssl://mail.johnsonfu.com',
			'smtp_port' => 465,
			'smtp_user' => 'confirmation@jfgroup.info',	// 'smtp_user' => 'confirmation@johnsonfu.com',
			'smtp_pass' => 'Johnson2014',				// 'smtp_pass' => 'Confirmation@5792',
			'mailtype'  => 'html',
			'charset'   => 'UTF-8'
			);
	public $myemails = array('confirmation@jfgroup.info','confirmation2@jfgroup.info','confirmation3@jfgroup.info');

	/**
	 * Send email
	 * 
	 * @param	string	$to			to email address
	 * @param	string	$subject	email subject
	 * @param	string	$body		email body
	 * @param	string	$from		from email name (not email address)
	 * @param	array	$attach		attachment file
	 * @return	boolean
	 */
	public function send_mymail($to, $subject, $body, $attach=array(), $from='') {
		shuffle($this->myemails);
		$email = array_shift($this->myemails);
		$this->config['smtp_user'] = $email;
		
		$this->load->library('email', $this->config);
		$this->email->set_newline("\r\n");
		if (empty($from)) {
			$this->email->from($email, 'Support');
		} else {
			$this->email->from($email, $from);
		}
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($body);
		if (!empty($attach)) {
			foreach ($attach as $name => $file) {
				$this->email->attach($file, '', $name);
			}
		}
		return $this->email->send();
	}
}
