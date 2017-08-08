<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Psigate_model extends CI_Model {
	public function add($data) {
		$this->db->insert('psigate', $data);
		return $this->db->insert_id();
	}
}
