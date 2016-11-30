<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model {
	public $logstr;
	public $sqlstr;
	public $error;
	
	/**
	 * Get setting list
	 * 
	 * @param	string	$type	String for type
	 * @return array
	 */
	public function get_settings($type=NULL) {
		$rt = array();
		if ($type) {
			$this->db->where('type', $type);
		}
		$this->db->where('parent_id', 0);
		$this->db->order_by('type', "ASC");
		$this->db->order_by('name', "ASC");
		$parents = $this->db->get('setting')->result_array();
		foreach ($parents as $p) {
			$rt[$p['setting_id']] = $p;
			if ($type) {
				$this->db->where('type', $type);
			}
			$this->db->where('parent_id', $p['setting_id']);
			$this->db->order_by('type', "ASC");
			$this->db->order_by('name', "ASC");
			$sons = $this->db->get('setting')->result_array();
			$rt[$p['setting_id']]['sons'] = array();
			if ($sons) {
				foreach ($sons as $s) {
					$rt[$p['setting_id']]['sons'][$s['setting_id']] = $s;
				}
			}
		}
		return $rt;
	}

	/**
	 * Get setting by name
	 *
	 * @param	string	$type	String for type
	 * @param	string	$name	String for key name
	 * @return array
	 */
	public function get_setting_by_name($type, $name) {
		$this->db->where('type', $type);
		$this->db->where('name', $name);
		return $this->db->get('setting')->row_array();
	}
	
	/**
	 * Get setting by ID
	 *
	 * @param integer $setting_id
	 * @return array
	 */
	public function get_setting_by_id($setting_id) {
		$this->db->where('setting_id', $setting_id);
		return $this->db->get('setting')->row_array();
	}
	
	/**
	 * Add setting data
	 * 
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function add($para) {
		$this->logstr = '';
		if ( ! $this->db->insert('setting', $para) ) {
			die($this->db->error());
		}
		$setting_id = $this->db->insert_id();
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Add setting[' . $setting_id . ']:' . join(', ', $para);
		return $setting_id;
	}

	/**
	 * Update setting data
	 *
	 * @para	integer	record ID
	 * @param	array	$para	Add payment record
	 * @return	integer	record ID
	 */
	public function update($setting_id, $para) {
		$this->logstr = '';
		$this->db->where('setting_id', $setting_id);
		$this->db->update('setting', $para);
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Update setting[' . $setting_id . ']:' . join(', ', $para);
		return $setting_id;
	}

	/**
	 * Delete setting data
	 * 
	 * @para	integer	record ID
	 * @return	none
	 */
	public function delete($setting_id) {
		$this->logstr = '';
		$this->db->where('setting_id', $setting_id);
		$this->db->delete('setting');
		$this->sqlstr = $this->db->last_query();
		$this->logstr = 'Delete setting[' . $setting_id . ']:' . join(', ', $para);
		return $setting_id;
	}
}
