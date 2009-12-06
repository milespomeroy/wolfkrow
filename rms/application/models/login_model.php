<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login_model
 *
 **/
class Login_model extends Model {

	/**
	 * get_num_by_email(string)
	 * string = email address
	 * 
	 * returns number of rows
	 *
	 * look up the number of user accounts that have a certain email address.
	**/
	function get_num_by_email($email)
	{
		$query = $this->db->get_where('users', array('email' => $email));
		return $query->num_rows();
	}
	
	// get_user(string)
	// returns row array
	function get_user($email)
	{
		$query = $this->db->get_where('users', array('email' => $email));
		return $query->row_array();
	}
	
	/**
	 * insert_account(array)
	 *  assoc array = (cc_number, cc_type, cc_security_code, billing_name, 
	 *  billing_address, cc_exp_month, cc_exp_year)
	 *
	 * returns account id
	 * returns false if unsuccessful insertion
	**/
	function insert_account($account)
	{
		$this->db->set($account);
		if (!$this->db->insert('accounts'))
		{
			return false;
		}
		
		return $this->db->insert_id();
	}
	
	/**
	 * insert_user(array)
	 *  assoc array = (full_name, email, password, user_type)
	 *
	 * returns user id
	 * returns false if unsuccessful insertion
	**/
	function insert_user($user)
	{
		$this->db->set($user);
		if (!$this->db->insert('users'))
		{
			return false;
		}
		
		return $this->db->insert_id();
	}

}
// End File login_model.php
// File Source /system/application/models/login_model.php