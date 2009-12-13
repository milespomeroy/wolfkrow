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
	
	// get_order(int)
	//
	// @param order id number
	// @return object of fields from the orders table for that order id number
	//  and vendor_type and percent_fee for vendor
	function get_order($order_id)
	{
		$query = $this->db->query("SELECT orders.*, 
			vendor_types.name AS vendor_type, 
			vendor_types.percent_fee AS percent_fee 
			FROM orders, vendors, vendor_types 
			WHERE orders.id = $order_id AND orders.vendor_id = vendors.id 
			AND vendors.type_id = vendor_types.id");
		return $query->row();
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
	
	// insert_rating(array)
	//
	// @param array all all element to be inserted into ratings table
	// @return TRUE/FALSE based on insertion success
	function insert_rating($rating)
	{
		if ($this->db->insert('ratings', $rating))
		{
			return true;
		}
		return false;
	}
	
	// get_rating(int)
	//
	// @param order id number
	// @return row object from ratings table, or false if not found
	function get_rating($order_id)
	{
		$query = $this->db->get_where('ratings', array('order_id' => $order_id));
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		return false;
	}
	
	// mark_as_filled(array)
	// also activate next order in the meal if one exists
	//
	// @param (array) order id numbers to be marked fulfilled
	function mark_as_filled($orders)
	{
		$current_time = date("Y-m-d H:i:s");
		
		// FIX: most of this probably belongs in the controller
		foreach ($orders as $order)
		{
			// make transactions, i.e. payments for services
			$order_r = $this->get_order($order);
			$this->make_transactions($order_r->id, $order_r->user_id, 
				$order_r->vendor_id, $order_r->total_price, 
				$order_r->percent_fee);
			
			// set filled time (READ: mark filled)
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
			
			// Mark meal as finished if the busboy order is filled
			if ($order_r->vendor_type == 'busboy')
			{
				$meal_id = $order_r->meal_id;
				$time_finished = date("Y-m-d H:i:s");

				$this->db->query("UPDATE meals 
					SET time_finished = '{$time_finished}' 
					WHERE id = '{$meal_id}'");
			
			}
		}
	}
	
	// make_transactions(int, int)
	//
	// @params order_id, guest_id, vendor_id, price, percent_fee
	// @return TRUE/FALSE based on insertion success
	function make_transactions($order_id, $guest_id, $vendor_id, $price, $fee)
	{
		/*
`			Would have to do some real credit card transactions if real
		*/
		// get giver (user) account id from session data
		$gquery = $this->db->query("SELECT account_id FROM users 
			WHERE id = $guest_id");
		$guest_account = $gquery->row()->account_id;
		
		// get vendor account id from query
		$vquery = $this->db->query("SELECT account_id FROM users 
			WHERE id = (SELECT user_id FROM vendors WHERE id = $vendor_id)");
		$vendor_account = $vquery->row()->account_id;
		
		// get manager's account id
		$mquery = $this->db->query("SELECT account_id FROM users
			WHERE user_type = 'manager' LIMIT 1");
		$man_account = $mquery->row()->account_id;
		
		// get sale time from php date
		$sale_time = date("Y-m-d H:i:s");
		
		// figure out amount break down of check to vendor and manager
		$to_man = $price * ($fee / 100);
		$to_vendor = $price - $to_man;
		
		// set up arrays for insertion
		$guest2vendor = array(
			'giver_account' => $guest_account,
			'recipient_account' => $vendor_account,
			'order_id' => $order_id,
			'amount' => $to_vendor,
			'sale_time' => $sale_time
			);
		
		$guest2manager = array(
			'giver_account' => $guest_account,
			'recipient_account' => $man_account,
			'order_id' => $order_id,
			'amount' => $to_man,
			'sale_time' => $sale_time
			);
			
		// insert arrays into transactions table
		if ($this->db->insert('transactions', $guest2vendor) 
			AND $this->db->insert('transactions', $guest2manager))
		{
			return true;
		}
		else // insertion failed
		{
			return false;
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
	
	// get_hired_app()
	// get the vendor applications that have been marked as 'Hire' by the 
	// manager
	//
	// @return array of objects: app_id, vendor_name, type_name, email
	function get_hired_apps()
	{
		$query = $this->db->query("SELECT vendor_applications.id AS app_id, 
			vendor_name, vendor_types.name AS type_name, email
			FROM vendor_applications, vendor_types WHERE offer = 'Hire' 
			AND activated IS NULL 
			AND vendor_applications.vendor_type_id = vendor_types.id");
			
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		return false;
	}
	
	// get_application(int)
	// get a specific application from the vendor_applications table using id
	//
	// @param id number of application
	// @return row as object
	function get_application($app_id)
	{
		$query = 
		$this->db->get_where('vendor_applications', array('id' => $app_id, 
			'activated' => NULL), 1);
		
		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		return false; // not found
	}
	
	// insert_vendor(array)
	//
	// @param array with all the info needed to insert into the vendors table
	// @return vendor id, FALSE if no insertion happened
	function insert_vendor($vendor)
	{
		$this->db->set($vendor);
		if (!$this->db->insert('vendors'))
		{
			return false;
		}
		return $this->db->insert_id();
	}
	
	function mark_app_activated($app_id)
	{
		$act_date = date("Y-m-d H:i:s");
		$this->db->query("UPDATE vendor_applications SET activated = '{$act_date}' 
			WHERE id = $app_id");
	}
}
// End File vendor_model.php
// File Source /system/application/models/vendor_model.php