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
	
	// get_orders(string)
	// get active orders for vendor dashboard
	//
	// @param (string) vendor id number
	// @return (array of arrays)
	// get order's id number (order_id), full name of customer (name)
	// and minutes the order has been active (mins_active)
	function get_orders($vendor_id)
	{
		$query = $this->db->query("SELECT orders.id AS order_id, 
			users.full_name AS name, 
			TIMESTAMPDIFF(MINUTE, orders.activated_date, NOW()) AS mins_active 
			FROM orders, users WHERE orders.vendor_id = $vendor_id 
			AND orders.filled IS NULL AND orders.activated_date IS NOT NULL 
			AND orders.user_id = users.id ORDER BY mins_active DESC");
			
		return $query->result_array();
	}
	
	// mark_as_filled(array)
	//
	// @param (array) order id numbers to be marked fulfilled
	function mark_as_filled($orders)
	{
		$filled_time = date("Y-m-d H:i:s");
		
		foreach ($orders as $order)
		{
			$this->db->query("UPDATE orders SET filled = '{$filled_time}' 
				WHERE id = $order");
		}
	}

}
// End File vendor_model.php
// File Source /system/application/models/vendor_model.php