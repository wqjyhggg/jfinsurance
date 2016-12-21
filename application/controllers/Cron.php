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
}
