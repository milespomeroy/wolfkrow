<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Meal_model
 *
 **/
class Meal_model extends Model {

	// get_unfinished_meal()
	//
	// @param (int) user id
	// @return (int) meal id OR false if none
	//
	function get_unfinished_meal($user_id) 
	{
		$query = $this->db->query("SELECT id FROM meals 
			WHERE user_id = $user_id AND time_finished IS NULL");
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->id;
		}
		else
		{
			return false;
		}
		
	}
	
	// insert_new_meal(int)
	//
	// @param (int) user id
	function insert_new_meal($user_id)
	{
		$mdate = date("Y-m-d H:i:s");
		
		$this->db->insert('meals', array(
			'user_id' => $user_id, 
			'time_started' => $mdate
			));
	}
	
	// vendor_order_exists(int, string)
	// 
	// @param1 (int) meal id
	// @param2 (string) vendor type: host, waiter, cook, or busboy
	// @return TRUE if order found
	function vendor_order_exists($meal_id, $vendor_type)
	{
		$query = $this->db->query("SELECT id FROM orders 
			WHERE meal_id = $meal_id AND vendor_id 
				IN (SELECT id FROM vendors 
					WHERE type_id = (SELECT id FROM vendor_types 
						WHERE name = '$vendor_type'))");
		if ($query->num_rows() > 0 )
		{
			return TRUE; // order exists for vendor type
		}
		else
		{
			return FALSE;
		}
	}
	
	// get_vendor_services(string)
	//
	// @param (string) vendor type like 'host' or 'waiter'
	// @return (array of objects) each object has service info: 
	//   id (int), name (string), description (string), (decimal) price
	function get_vendor_services($vendor_type)
	{
		$query = $this->db->query("SELECT id, name, description, price 
			FROM services WHERE vendor_type_id = (SELECT id FROM vendor_types 
			WHERE name = '$vendor_type')");
		return $query->result();
	}
	
	// get_vendors(string)
	//
	// @param (string) vendor type like 'host'
	// @return (array of objects) from vendors tables with average rating
	function get_vendors($vendor_type)
	{
		$query = $this->db->query("SELECT * FROM vendors WHERE type_id = 
		(SELECT id FROM vendor_types WHERE name = '$vendor_type')");
		return $query->result();
	}
	
	// get vendor(int)
	//
	// @param (int) vendor id number
	// @return (array) vendor info:
	//   id, name, type_id, qualifications, price, user_id, deactive
	// @return FALSE if vendor not found
	function get_vendor($vendor_id)
	{
		$query = $this->db->query("SELECT * FROM vendors WHERE id = $vendor_id");
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else // vendor not found
		{
			return false;
		}
	}

}
// End File classname.php
// File Source /system/application/models/classname.php