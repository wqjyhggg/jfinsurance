<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mymail_model extends CI_Model {
	/*
	public $config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'jackauroratd@gmail.com',
			'smtp_pass' => '123123123s',
			'mailtype'  => 'html',
			'charset'   => 'UTF-8'
			);
	public $myemail = 'jackauroratd@gmail.com';
	/**/
	public $config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'tls://mail.auroratd.ca',
			'smtp_port' => 587,
			'smtp_user' => 'aurora1@auroratd.ca',
			'smtp_pass' => 'aaaaaaaa',
			'mailtype'  => 'html',
			'charset'   => 'UTF-8'
			);
	public $myemail = 'aurora1@auroratd.ca';
	/**/
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
		$this->load->library('email', $this->config);
		$this->email->set_newline("\r\n");
		if (empty($from)) {
			$this->email->from($this->myemail, 'Support');
		} else {
			$this->email->from($this->myemail, $from);
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
