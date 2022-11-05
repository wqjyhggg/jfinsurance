<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mymail_model extends CI_Model {
	public $config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.office365.com', // 'ssl://smtp.gmail.com',
			'smtp_crypto' => 'tls',
			'smtp_port' => 587,
			'smtp_user' => 'confirmation@jfgroup.info',
			'smtp_pass' => 'Johnson2014',
			'mailtype'  => 'html',
			'charset'   => 'UTF-8'
			);
	public $myemails = array('confirmation@jfgroup.ca','confirmation2@jfgroup.ca','confirmation3@jfgroup.ca');

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
	public function send_mymail($to, $subject, $body, $attach=array(), $from='', $charset='UTF-8') {
		shuffle($this->myemails);
		$email = array_shift($this->myemails);
		$this->config['smtp_user'] = $email;
    $this->config['charset'] = $charset;
		
		$this->load->library('email', $this->config);
		$this->email->set_newline("\r\n");
		if (empty($from)) {
			$this->email->from($email, 'JF Insurance');
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
		$r = $this->email->send(FALSE);
		log_message('debug', "EMAIL: ".$this->email->print_debugger(array('aa')));
		return $r;
	}
}
