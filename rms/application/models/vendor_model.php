<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vendor Model
 *
 **/
class Vendor_model extends Model {

	// get_vendor(int)
	//
	// @param (int) user id number
	// @return (array) vendor_id (int), vendor_name (string)
	function get_vendor($user_id)
	{
		$query = $this->db->query("SELECT id AS vendor_id, name AS vendor_name 
			FROM vendors WHERE user_id = $user_id");
		return $query->row_array();
	}
	
	function get_orders($vendor_id)
	{
		$query = $this->db->query("SELECT * FROM orders WHERE vendor_id = $vendor_id AND filled = 0");
		// working here
	}

}
// End File vendor_model.php
// File Source /system/application/models/vendor_model.php