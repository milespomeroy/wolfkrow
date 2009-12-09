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
	
	// get_revenue(string)
	//
	// @param user id number of vendor
	// @return (array) today => decimal, yesterday => decimal
	function get_revenue($user_id)
	{
		// today
		$tquery = $this->db->query("SELECT SUM(amount) AS today 
			FROM transactions WHERE recipient_account = 
			(SELECT account_id FROM users WHERE id = $user_id) 
			AND DATE(sale_time) = CURRENT_DATE()"); 
		
		$rev['today'] = $tquery->row()->today;
			
		// yesterday
		$yquery = $this->db->query("SELECT SUM(amount) AS yesterday 
			FROM transactions WHERE recipient_account = 
			(SELECT account_id FROM users WHERE id = $user_id) 
			AND DATE(sale_time) = SUBDATE(CURRENT_DATE, 1)");
			
		$rev['yesterday'] = $yquery->row()->yesterday;
		
		return $rev;
	}
	
	// mark_as_filled(array)
	// also activate next order in the meal if one exists
	//
	// @param (array) order id numbers to be marked fulfilled
	function mark_as_filled($orders)
	{
		$current_time = date("Y-m-d H:i:s");
		
		foreach ($orders as $order)
		{
			// set filled time
			$this->db->query("UPDATE orders SET filled = '{$current_time}' 
				WHERE id = $order");
			
			// see if there is another order in the meal to activate
			$query = $this->db->query("SELECT id FROM orders 
			WHERE meal_id = (SELECT id FROM meals 
			WHERE user_id = (SELECT user_id FROM orders WHERE id = $order)
			AND time_finished IS NULL) AND activated_date IS NULL 
			ORDER BY id LIMIT 1");
			
			if ($query->num_rows() > 0)
			{
				$id_to_activate = $query->row()->id;
				
				// activate next order
				$this->db->query("UPDATE orders SET 
					activated_date = '{$current_time}' 
					WHERE id = $id_to_activate");
			}
		}
	}
	
	// app_exists(string)
	// check if a vendor application exists for an email
	//
	// @param (string) email address
	// @return TRUE if application does exists already for that email
	function app_exists($email)
	{
		$query = $this->db->query("SELECT id FROM vendor_applications 
			WHERE email = '{$email}'");
		
		if ($query->num_rows() > 0)
		{
			return true; // application already exists for this email
		}
		else
		{
			return false;
		}
	}

	// insert_app(array)
	// insert application data into vendor_applications table
	//
	// @param (array) data to be inserted with keys matching column names
	// @return TRUE/FALSE correlating with insertion status
	function insert_app($app)
	{
		return $this->db->insert('vendor_applications', $app);
	}

}
// End File vendor_model.php
// File Source /system/application/models/vendor_model.php