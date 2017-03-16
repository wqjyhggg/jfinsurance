<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {
    const ADMIN = 1;
    const STAFF = 2;
    const ACCOUNTING = 3;
    const SCHOOL = 103;
    const BROKERAGE = 104;
    const AGENT = 105;
	
    public $logstr;
	public $sqlstr;
	
    /**
     *  Check matched username and passwrd in user table
     *  
     *  @param	string	$username
     *  @param	string	$password
     *  @return null / array on find.     
     */
	public function login($username, $password) {
		$sql = "SELECT * FROM user WHERE username=" . $this->db->escape($username) . " AND status='1'";
		$rc = $this->db->query($sql)->row_array();
		if ($rc && password_verify($password, $rc['password']) && $rc['status']) {
			$this->logstr = $username . " login";
			$this->sqlstr = $this->db->last_query();
			if ($rc['user_group_id'] > 103) {
				$tm = strtotime($rc['licence_expire']) + 86400;
				$now = time();
				if ($now > $tm) {
					return 'licence Expired';
				}
			}
			return $rc;
		}
		return NULL;
	}
	
	/**
	 * Get user list
	 * 
	 * @param	none
	 * @return	string			result message
	 */
	public function installadmin() {
		$sql = "SELECT * FROM user limit 1";
		$rt = $this->db->query($sql);
		if ($rt->row()) {
			return "Installed";
		}
		$pw = password_hash('jfpassword', PASSWORD_DEFAULT);
		$sql = "INSERT INTO user SET user_group_id='1', username='admin', password=" . $this->db->escape($pw) . ", status='1', note='default user'";
		$this->db->query($sql);
		if ($this->db->affected_rows()) {
			return "Install OK";
		}
		return "Install Failed";
	}
	
	/**
	 * Get user list
	 * 
	 * @param	integer	$user_group_id
	 * @param	array	$para			search conditions
	 * @return	array					user table search result
	 */
	public function get_user_list($user_group_id, $user_id, $para=array(), $limit=0, $start=0) {
		if ($user_group_id <= 1) {
			// Admin
			$sql = "SELECT * FROM user WHERE user_group_id >= '0'";
		} else if ($user_group_id < 100) {
			// Staff
			$sql = "SELECT * FROM user WHERE user_group_id >= '100'";
		} else {
			$sql = "SELECT * FROM user WHERE parent_user_id = '" . (int)$user_id . "'";
		}
		if (!empty($para['user_id'])) {
			$sql .= " AND user_id = '" . (int)$para['user_id'] . "'";
		}
		if (!empty($para['user_group_id'])) {
			$sql .= " AND user_group_id = '" . (int)$para['user_group_id'] . "'";
		}
		if (!empty($para['region_id'])) {
			$sql .= " AND region_id = '" . (int)$para['region_id'] . "'";
		}
		if (!empty($para['username'])) {
			$sql .= " AND username LIKE " . $this->db->escape($para['username'] . "%");
		}
		if (!empty($para['firstname'])) {
			$sql .= " AND firstname LIKE " . $this->db->escape($para['firstname'] . "%");
		}
		if (!empty($para['lastname'])) {
			$sql .= " AND lastname LIKE " . $this->db->escape($para['lastname'] . "%");
		}
		if (!empty($para['email'])) {
			$sql .= " AND email LIKE " . $this->db->escape($para['email'] . "%");
		}
		if (!empty($para['business'])) {
			$sql .= " AND business LIKE " . $this->db->escape($para['business'] . "%");
		}
		if (!empty((int)$limit)) {
			if (empty((int)$start)) {
				$sql .= " LIMIT " . (int)$limit;
			} else {
				$sql .= " LIMIT " . (int)$start .  "," . (int)$limit;
			}
		}
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Get user list Total
	 * 
	 * @param	integer	$user_group_id
	 * @param	array	$para			search conditions
	 * @return	array					user table search result
	 */
	public function get_user_list_total($user_group_id, $user_id, $para=array()) {
		if ($user_group_id <= 1) {
			// Admin
			$sql = "SELECT * FROM user WHERE user_group_id >= '0'";
		} else if ($user_group_id < 100) {
			// Staff
			$sql = "SELECT * FROM user WHERE user_group_id >= '100'";
		} else {
			$sql = "SELECT * FROM user WHERE parent_user_id = '" . (int)$user_id . "'";
		}
		if (!empty($para['user_group_id'])) {
			$sql .= " AND user_group_id = '" . (int)$para['user_group_id'] . "'";
		}
		if (!empty($para['region_id'])) {
			$sql .= " AND region_id = '" . (int)$para['region_id'] . "'";
		}
		if (!empty($para['username'])) {
			$sql .= " AND username LIKE " . $this->db->escape($para['username'] . "%");
		}
		if (!empty($para['firstname'])) {
			$sql .= " AND firstname LIKE " . $this->db->escape($para['firstname'] . "%");
		}
		if (!empty($para['lastname'])) {
			$sql .= " AND lastname LIKE " . $this->db->escape($para['lastname'] . "%");
		}
		if (!empty($para['email'])) {
			$sql .= " AND email LIKE " . $this->db->escape($para['email'] . "%");
		}
		if (!empty($para['business'])) {
			$sql .= " AND business LIKE " . $this->db->escape($para['business'] . "%");
		}
		return $this->db->query($sql)->num_rows();
	}

	/**
     * Get Available user list of current user
     *
     * @return array available user list
     **/
    public function get_available_user_list()
    {
        $beuser = $this->session->beuser;
        if (($beuser['user_group_id'] > 100) && ($beuser['user_group_id'] != 104)) {
            return array(
                $beuser['user_id'] => array(
                    'user_id' => $beuser['user_id'],
                    'username' => $beuser['username'],
                    'full_name' => $beuser['firstname'] . ' ' . $beuser['lastname']
                )
            );
        }
        $this->db->distinct();
        $this->db->select('
            u.user_id,
            u.username,
            concat(u.firstname, " ", u.lastname) as full_name
        ');
        if (!empty($beuser['region_id'])) {
        	$this->db->where('u.region_id', $beuser['region_id']);
        }
        if ($beuser['user_group_id'] == 104){
            $this->db->from('user u, user u2');
            $this->db->where('u.parent_user_id = ' . ((int) $beuser['user_id']));
            $this->db->or_where('u.user_id= ' . ((int) $beuser['user_id']));
        } else {
            $this->db->from('user u');
            if ($beuser['user_group_id'] >= 2){
                $this->db->where('u.user_group_id >= 2');
            }
        }
        $this->db->order_by('u.username');
        $results = $this->db->get()->result_array();
        $records = array();
        foreach ($results as $row) {
            $records[$row['user_id']] = $row;
        }
        return $records;
    }

	/**
	 * Get user By ID
	 *
	 * @param	integer	$user_id
	 * @return	array					user table search result
	 */
	public function get_user_by_id($user_id) {
		$sql = "SELECT * FROM user WHERE user_id = '" . (int)$user_id . "'";
		return $this->db->query($sql)->row_array();
	}

	/**
	 * Get user By ID
	 *
	 * @param	integer	$user_id
	 * @param	integer	$username
	 * @return	array					user table search result
	 */
	public function check_username($user_id, $username) {
		$sql = "SELECT * FROM user WHERE user_id != '" . (int)$user_id . "' and username = " . $this->db->escape($username) ;
		if ($this->db->query($sql)->row_array()) {
			return 1;
		}
		return 0;
	}

	/**
	 * Get brokerage user list
	 *
	 * @return	array					user table search result
	 */
	public function get_school_id_list() {
		$sql = "SELECT user_id,business FROM user WHERE user_group_id = '103'";
		$rt = $this->db->query($sql)->result_array();
		$rtArr = array();
		foreach ($rt as $rc) {
			$rtArr[$rc['user_id']] = $rc['business'];
		}
		return $rtArr;
	}
	
	/**
	 * Get all agent list
	 *
	 * @return	array					user table search result
	 */
	public function get_all_agent_list() {
		$sql = "SELECT user_id,concat(username, ' - ', firstname, ' ', lastname) as full_name FROM user WHERE user_group_id > '100' ORDER BY username ASC";
		$rt = $this->db->query($sql)->result_array();
		$rtArr = array();
		foreach ($rt as $rc) {
			$rtArr[$rc['user_id']] = $rc['full_name'];
		}
		return $rtArr;
	}
	
	/**
	 * Get brokerage user list
	 * 
	 * @return	array					user table search result
	 */
	public function get_broker_id_list() {
		$sql = "SELECT user_id,business FROM user WHERE user_group_id = '104'";
        if ($this->session->beuser['region_id'] != 0) {
        	$sql .= " AND region_id='" . $this->session->beuser['region_id'] . "'";
        }
		$rt = $this->db->query($sql)->result_array();
		$rtArr = array();
		foreach ($rt as $rc) {
			$rtArr[$rc['user_id']] = $rc['business'];
		}
		return $rtArr;
	}
	
	/**
	 * Get user product list
	 * 
	 * @param	integer	$user_id
	 * @return	array					user table search result
	 */
	public function get_user_product_list($user_id) {
		$sql = "SELECT * FROM user_product WHERE user_id = '" . (int)$user_id . "'";
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Create / update user information
	 * 
	 * @param	integer	$user_id
	 * @para	array	$post			post input
	 * @return	integer user_id
	 */
	public function update($user_id, $post, $forcepw=1, $checkboxArr=array()) {
		$this_user = array();
		$this->logstr = '';
		$this->sqlstr = '';
		if ($user_id) {
			$sql = "SELECT * FROM user WHERE user_id = '" . (int)$user_id . "'";
			$this_user = $this->db->query($sql)->row_array();
			if (empty($this_user)) {
				log_message('error', "Can't find user: ($user_id)");
				return;
			}
		}
		$para = array();
		if (!empty($post['user_group_id'])) {
			if ($this_user) {
				if ($this_user['user_group_id'] != (int)$post['user_group_id']) {
					$this->logstr .= "UserGroup[".$this_user['user_group_id']."]=>[".(int)$post['user_group_id']."],";
					$para['user_group_id'] = (int)$post['user_group_id'];
				}
			} else {
				$para['user_group_id'] = (int)$post['user_group_id'];
			}
		}
		if (isset($post['region_id'])) {
			if ($this_user) {
				if ($this_user['region_id'] != (int)$post['region_id']) {
					$this->logstr .= "region_id[".$this_user['region_id']."]=>[".(int)$post['region_id']."],";
					$para['region_id'] = (int)$post['region_id'];
				}
			} else {
				$para['region_id'] = (int)$post['region_id'];
			}
		}
        if (!empty($post['parent_user_id'])) {
			if ($this_user) {
				if ($this_user['parent_user_id'] != (int)$post['parent_user_id']) {
					$this->logstr .= "UserParentGroup[".$this_user['parent_user_id']."]=>[".(int)$post['parent_user_id']."],";
					$para['parent_user_id'] = (int)$post['parent_user_id'];
				}
			} else {
				$para['parent_user_id'] = (int)$post['parent_user_id'];
			}
		}
		if (!empty($post['username'])) {
			if ($this_user) {
				if ($this_user['username'] != $post['username']) {
					$this->logstr .= "username[".$this_user['username']."]=>[".$post['username']."],";
					$para['username'] = trim($post['username']);
				}
			} else {
				$this->logstr .= "username[".$post['username']."],";
				$para['username'] = trim($post['username']);
			}
		}
		if (!empty($post['password'])) {
			$password = trim($post['password']);
			$pw = password_hash($password, PASSWORD_DEFAULT);
			if ($this_user) {
				if ($this_user['password'] != $post['password']) {
					$this->logstr .= "password => * ,";
					$para['password'] = $pw;
					$para['forcepw'] = $forcepw;
				}
			} else {
				$para['password'] = $pw;
				$para['forcepw'] = $forcepw;
			}
		}
		if (!empty($post['region'])) {
			if ($this_user) {
				if ($this_user['region'] != $post['region']) {
					$this->logstr .= "region[".$this_user['region']."]=>[".$post['region']."],";
					$para['region'] = trim($post['region']);
				}
			} else {
				$para['region'] = trim($post['region']);
			}
		}
		if (!empty($post['business'])) {
			if ($this_user) {
				if ($this_user['business'] != $post['business']) {
					$this->logstr .= "business[".$this_user['business']."]=>[".$post['business']."],";
					$para['business'] = trim($post['business']);
				}
			} else {
				$para['business'] = trim($post['business']);
			}
		}
		if (!empty($post['gender'])) {
			if ($this_user) {
				if ($this_user['gender'] != $post['gender']) {
					$this->logstr .= "gender[".$this_user['gender']."]=>[".$post['gender']."],";
					$para['gender'] = trim($post['gender']);
				}
			} else {
				$para['gender'] = trim($post['gender']);
			}
		}
		if (!empty($post['firstname'])) {
			if ($this_user) {
				if ($this_user['firstname'] != $post['firstname']) {
					$this->logstr .= "firstname[".$this_user['firstname']."]=>[".$post['firstname']."],";
					$para['firstname'] = trim($post['firstname']);
				}
			} else {
				$para['firstname'] = trim($post['firstname']);
			}
		}
		if (!empty($post['lastname'])) {
			if ($this_user) {
				if ($this_user['lastname'] != $post['lastname']) {
					$this->logstr .= "lastname[".$this_user['lastname']."]=>[".$post['lastname']."],";
					$para['lastname'] = trim($post['lastname']);
				}
			} else {
				$para['lastname'] = trim($post['lastname']);
			}
		}
		if (!empty($post['email'])) {
			if ($this_user) {
				if ($this_user['email'] != $post['email']) {
					$this->logstr .= "email[".$this_user['email']."]=>[".$post['email']."],";
					$para['email'] = strtolower(trim($post['email']));
				}
			} else {
				$para['email'] = strtolower(trim($post['email']));
			}
		}
		if (!empty($post['address'])) {
			if ($this_user) {
				if ($this_user['address'] != $post['address']) {
					$this->logstr .= "address[".$this_user['address']."]=>[".$post['address']."],";
					$para['address'] = trim($post['address']);
				}
			} else {
				$para['address'] = trim($post['address']);
			}
		}
		if (!empty($post['city'])) {
			if ($this_user) {
				if ($this_user['city'] != $post['city']) {
					$this->logstr .= "city[".$this_user['city']."]=>[".$post['city']."],";
					$para['city'] = trim($post['city']);
				}
			} else {
				$para['city'] = trim($post['city']);
			}
		}
		if (!empty($post['province2'])) {
			if ($this_user) {
				if ($this_user['province2'] != $post['province2']) {
					$this->logstr .= "province[".$this_user['province2']."]=>[".$post['province2']."],";
					$para['province2'] = trim($post['province2']);
				}
			} else {
				$para['province2'] = trim($post['province2']);
			}
		}
		if (!empty($post['country2'])) {
			if ($this_user) {
				if ($this_user['country2'] != $post['country2']) {
					$this->logstr .= "country[".$this_user['country2']."]=>[".$post['country2']."],";
					$para['country2'] = trim($post['country2']);
				}
			} else {
				$para['country2'] = trim($post['country2']);
			}
		}
		if (!empty($post['postcode'])) {
			if ($this_user) {
				if ($this_user['postcode'] != $post['postcode']) {
					$this->logstr .= "postcode[".$this_user['postcode']."]=>[".$post['postcode']."],";
					$para['postcode'] = trim(strtoupper($post['postcode']));
				}
			} else {
				$para['postcode'] = trim(strtoupper($post['postcode']));
			}
		}
		if (!empty($post['mail_address'])) {
			if ($this_user) {
				if ($this_user['mail_address'] != $post['mail_address']) {
					$this->logstr .= "mail_address[".$this_user['mail_address']."]=>[".$post['mail_address']."],";
					$para['mail_address'] = trim($post['mail_address']);
				}
			} else {
				$para['mail_address'] = trim($post['mail_address']);
			}
		}
		if (!empty($post['mail_city'])) {
			if ($this_user) {
				if ($this_user['mail_city'] != $post['mail_city']) {
					$this->logstr .= "mail_city[".$this_user['mail_city']."]=>[".$post['mail_city']."],";
					$para['mail_city'] = trim($post['mail_city']);
				}
			} else {
				$para['mail_city'] = trim($post['mail_city']);
			}
		}
		if (!empty($post['mail_province2'])) {
			if ($this_user) {
				if ($this_user['mail_province2'] != $post['mail_province2']) {
					$this->logstr .= "mail_province[".$this_user['mail_province2']."]=>[".$post['mail_province2']."],";
					$para['mail_province2'] = trim($post['mail_province2']);
				}
			} else {
				$para['mail_province2'] = trim($post['mail_province2']);
			}
		}
		if (!empty($post['mail_country2'])) {
			if ($this_user) {
				if ($this_user['mail_country2'] != $post['mail_country2']) {
					$this->logstr .= "mail_country[".$this_user['mail_country2']."]=>[".$post['mail_country2']."],";
					$para['mail_country2'] = trim($post['mail_country2']);
				}
			} else {
				$para['mail_country2'] = trim($post['mail_country2']);
			}
		}
		if (!empty($post['mail_postcode'])) {
			if ($this_user) {
				if ($this_user['mail_postcode'] != $post['mail_postcode']) {
					$this->logstr .= "mail_postcode[".$this_user['mail_postcode']."]=>[".$post['mail_postcode']."],";
					$para['mail_postcode'] = trim(strtoupper($post['mail_postcode']));
				}
			} else {
				$para['mail_postcode'] = trim(strtoupper($post['mail_postcode']));
			}
		}
		if (!empty($post['website'])) {
			if ($this_user) {
				if ($this_user['website'] != $post['website']) {
					$this->logstr .= "website[".$this_user['website']."]=>[".$post['website']."],";
					$para['website'] = trim($post['website']);
				}
			} else {
				$para['website'] = trim($post['website']);
			}
		}
		if (!empty($post['licence_number'])) {
			if ($this_user) {
				if ($this_user['licence_number'] != $post['licence_number']) {
					$this->logstr .= "licence_number[".$this_user['licence_number']."]=>[".$post['licence_number']."],";
					$para['licence_number'] = trim($post['licence_number']);
				}
			} else {
				$para['licence_number'] = trim($post['licence_number']);
			}
		}
		if (!empty($post['licence_expire'])) {
			if ($this_user) {
				if ($this_user['licence_expire'] != $post['licence_expire']) {
					$this->logstr .= "licence_expire[".$this_user['licence_expire']."]=>[".$post['licence_expire']."],";
					$para['licence_expire'] = trim($post['licence_expire']);
				}
			} else {
				$para['licence_expire'] = trim($post['licence_expire']);
			}
		}
		if (!empty($post['business_phone'])) {
			if ($this_user) {
				if ($this_user['business_phone'] != $post['business_phone']) {
					$this->logstr .= "business_phone[".$this_user['business_phone']."]=>[".$post['business_phone']."],";
					$para['business_phone'] = trim($post['business_phone']);
				}
			} else {
				$para['business_phone'] = trim($post['business_phone']);
			}
		}
		if (!empty($post['mobile_phone'])) {
			if ($this_user) {
				if ($this_user['mobile_phone'] != $post['mobile_phone']) {
					$this->logstr .= "mobile_phone[".$this_user['mobile_phone']."]=>[".$post['mobile_phone']."],";
					$para['mobile_phone'] = trim($post['mobile_phone']);
				}
			} else {
				$para['mobile_phone'] = trim($post['mobile_phone']);
			}
		}
		if (!empty($post['fax_number'])) {
			if ($this_user) {
				if ($this_user['fax_number'] != $post['fax_number']) {
					$this->logstr .= "fax_number[".$this_user['fax_number']."]=>[".$post['fax_number']."],";
					$para['fax_number'] = trim($post['fax_number']);
				}
			} else {
				$para['fax_number'] = trim($post['fax_number']);
			}
		}
		if (!empty($post['toll_free'])) {
			if ($this_user) {
				if ($this_user['toll_free'] != $post['toll_free']) {
					$this->logstr .= "toll_free[".$this_user['toll_free']."]=>[".$post['toll_free']."],";
					$para['toll_free'] = trim($post['toll_free']);
				}
			} else {
				$para['toll_free'] = trim($post['toll_free']);
			}
		}
		if (!empty($post['mobile_phone'])) {
			if ($this_user) {
				if ($this_user['mobile_phone'] != $post['mobile_phone']) {
					$this->logstr .= "mobile_phone[".$this_user['mobile_phone']."]=>[".$post['mobile_phone']."],";
					$para['mobile_phone'] = trim($post['mobile_phone']);
				}
			} else {
				$para['mobile_phone'] = trim($post['mobile_phone']);
			}
		}
		if (isset($post['paytype_list'])) {
			$pay_type = " " . join(",", $post['paytype_list']);
			if ($this_user) {
				if ($this_user['pay_type'] != $pay_type) {
					$this->logstr .= "pay_type[".$this_user['pay_type']."]=>[".$pay_type."],";
					$para['pay_type'] = $pay_type;
				}
			} else {
				$para['pay_type'] = $pay_type;
				$this->logstr .= "pay_type[".$pay_type."],";
				$para['ip'] = $this->input->ip_address();
			}
		}
		$status = empty($post['status']) ? 0 : 1;
		if ($this_user) {
			if ($this_user['status'] != $status) {
				$this->logstr .= "status[".$this_user['status']."]=>[".$status."],";
				$para['status'] = $status;
			}
		} else {
			$para['status'] = $status;
		}
		if (!empty($post['receive_type'])) {
			if ($this_user) {
				if ($this_user['receive_type'] != $post['receive_type']) {
					$this->logstr .= "note[".$this_user['receive_type']."]=>[".$post['receive_type']."],";
					$para['receive_type'] = trim($post['receive_type']);
				}
			} else {
				$para['receive_type'] = trim($post['receive_type']);
			}
		}
		if (!empty($post['note'])) {
			if ($this_user) {
				if ($this_user['note'] != $post['note']) {
					$this->logstr .= "note[".$this_user['note']."]=>[".$post['note']."],";
					$para['note'] = trim($post['note']);
				}
			} else {
				$para['note'] = trim($post['note']);
			}
		}

		$enable_pdf = 0;
		if (!empty($post['enable_pdf'])) $enable_pdf = 1;
		
		$pdf_product_arr = array();
		if (isset($post['pdf_product_list'])) $pdf_product_arr = $post['pdf_product_list']; 
		$pdf_product = json_encode($pdf_product_arr);
		if ($this_user) {
			if ($this_user['pdf_product'] != $pdf_product) {
				$this->logstr .= "pdf_product[".$this_user['pdf_product']."]=>[".$pdf_product."],";
				$para['pdf_product'] = $pdf_product;
			}
		} else {
			$para['pdf_product'] = $pdf_product;
		}
		
		if ($this_user) {
			if ($this_user['enable_pdf'] != $enable_pdf) {
				$this->logstr .= "enable_pdf[".$this_user['enable_pdf']."]=>[".$enable_pdf."],";
				$para['enable_pdf'] = $enable_pdf;
			}
		} else {
			$para['enable_pdf'] = $enable_pdf;
		}
		if (!empty($post['pdf_logo'])) {
			if ($this_user) {
				if ($this_user['pdf_logo'] != $post['pdf_logo']) {
					$this->logstr .= "pdf_logo[".$this_user['note']."]=>[".$post['pdf_logo']."],";
					$para['pdf_logo'] = trim($post['pdf_logo']);
				}
			} else {
				$para['pdf_logo'] = trim($post['pdf_logo']);
			}
		}
		if (!empty($post['pdf_qr'])) {
			if ($this_user) {
				if ($this_user['pdf_qr'] != $post['pdf_qr']) {
					$this->logstr .= "pdf_qr[".$this_user['pdf_qr']."]=>[".$post['pdf_qr']."],";
					$para['pdf_qr'] = trim($post['pdf_qr']);
				}
			} else {
				$para['pdf_qr'] = trim($post['pdf_qr']);
			}
		}
		if (!empty($post['pdf_qr2'])) {
			if ($this_user) {
				if ($this_user['pdf_qr2'] != $post['pdf_qr2']) {
					$this->logstr .= "pdf_qr2[".$this_user['pdf_qr2']."]=>[".$post['pdf_qr2']."],";
					$para['pdf_qr2'] = trim($post['pdf_qr2']);
				}
			} else {
				$para['pdf_qr2'] = trim($post['pdf_qr2']);
			}
		}
		foreach (array('left', 'right') as $str) {
			for ($i = 1; $i < 7; $i++) {
				$thename = 'pdf_f_' . $str . $i;
				if (!empty($post[$thename])) {
					if ($this_user) {
						if ($this_user[$thename] != $post[$thename]) {
							$this->logstr .= $thename . "[".$this_user[$thename]."]=>[".$post[$thename]."],";
							$para[$thename] = trim($post[$thename]);
						}
					} else {
						$para[$thename] = trim($post[$thename]);
					}
				}
			}
		} 
		if (!empty($post['date_added'])) {
			if ($this_user) {
			} else {
				$para['date_added'] = trim($post['date_added']);
			}
		}
		if ($user_id) {
			if (!empty($para)) {
				$this->db->where('user_id', $user_id);
				$this->db->update('user', $para);
				$this->logstr = "Update user: ".$this->logstr;
				$this->sqlstr = $this->db->last_query();
			}
		} else {
			$this->db->insert('user', $para);
			$user_id = $this->db->insert_id();
			$this->logstr = "Add user(" . $user_id . "): " . $this->logstr;
			$this->sqlstr = $this->db->last_query();
		}
		
		if (isset($checkboxArr['product_list'])) {
		// user_product
			$this->db->where('user_id', $user_id);
			$rt = $this->db->get('user_product');
			$user_products = $rt->result_array();
			$now_array = array();
			$now_prods = '';
			$new_array = array();
			$new_prods = '';
			$prchg = '';
			foreach ($user_products as $p) {
				$now_prods .= ' ' . $p['product_short'];
				$now_array[$p['product_short']] = $p;
			}
			foreach ($post['product_list'] as $p) {
				$new_prods .= ' ' . $p;
				if (array_key_exists($p, $now_array)) {
					if ((float)$now_array[$p]['commission'] != (float)$post['product_commission_'.$p]) {
						$prchg .= $p . "[" . $now_array[$p]['commission'] . "]=>[" . $post['product_commission_'.$p] . "]";
					}
				} else {
					$prchg .= $p . "[add:" . $post['product_commission_'.$p] . "]";
				}
				$new_array[] = array('user_id' => $user_id, 'product_short' => $p, 'commission' => $post['product_commission_'.$p]);
			}
			if (($now_prods != $new_prods) || $prchg) {
				$this->logstr .= "; user products: " . $now_prods . " => " . $new_prods . "; " . $prchg;
				$this->db->delete('user_product', array('user_id' => $user_id));
				$this->sqlstr .= "; " . $this->db->last_query();
				foreach ($new_array as $up) {
					$this->db->insert('user_product', $up);
					$this->sqlstr .= "; " . $this->db->last_query();
				}
			}
		}
		
		return $user_id;
	}
	
	public function check_sub_user($user_id, $sub_user_id) {
		$this->db->where('parent_user_id', $user_id);
		$this->db->where('user_id', $sub_user_id);
		return $this->db->get('user')->row_array();
	}
}
