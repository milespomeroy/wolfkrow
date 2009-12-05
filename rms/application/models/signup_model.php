<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Signup_model
 *
 **/
class Signup_model extends Model {

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

}
// End File classname.php
// File Source /system/application/models/classname.php