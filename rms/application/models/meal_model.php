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
	
	// get_std_package(string)
	// get the standard services package for a vendor type
	//
	// @param (string) vendor type
	// @return (array of objects) name (string)
	function get_std_package($vendor_type)
	{
		$query = $this->db->query("SELECT name FROM services 
			WHERE vendor_type_id = (SELECT id FROM vendor_types 
			WHERE name = '$vendor_type') AND standard = 1");
		return $query->result();
	}
	
	// get_vendor_services(string)
	// get additional services that the vendor could do
	//
	// @param (string) vendor type like 'host' or 'waiter'
	// @return (array of objects) each object has service info: 
	//   id (int), name (string), description (string), (decimal) price
	function get_vendor_services($vendor_type)
	{
		$query = $this->db->query("SELECT id, name, description, price 
			FROM services WHERE vendor_type_id = (SELECT id FROM vendor_types 
			WHERE name = '$vendor_type') AND standard = 0");
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
	//   id, name, type_id, qualifications, price, user_id, deactive, type
	// @return FALSE if vendor not found
	function get_vendor($vendor_id)
	{
		// vendor info
		$vquery = $this->db->query("SELECT vendors.*, vendor_types.name AS type 
			FROM vendors, vendor_types 
			WHERE vendors.id = $vendor_id AND vendors.type_id = vendor_types.id"
			);
		
		if ($vquery->num_rows() > 0)
		{
			// orders up - for currently serving data
			$oquery = $this->db->query("SELECT id FROM orders 
				WHERE vendor_id = $vendor_id AND filled = 0");

			$data = $vquery->row_array();
			$data['orders'] = $oquery->num_rows();
			return $data;
		}
		else // vendor not found
		{
			return false;
		}
	}
	
	function get_meal_id()
	{
		$user_id = $this->session->userdata('id');
		
		$mquery = $this->db->query("SELECT id FROM meals 
			WHERE user_id = $user_id AND time_finished IS NULL");
		return $mquery->row()->id;
	}
	
	// get_order_id(int)
	//
	// @param vendor id 
	// @return order id OR FALSE if no order found
	function get_order_id($vendor_id)
	{
		// get meal id 
		$meal_id = $this->get_meal_id();
		
		// check for existing orders
		$query = $this->db->query("SELECT id FROM orders 
			WHERE vendor_id = $vendor_id AND meal_id = $meal_id");
	
		if ($query->num_rows() > 0)
		{
			// have existing order
			return $query->row()->id;
		}
		else
		{
			// no order found
			return false;
		}
		
	}
	
	// make_order(int)
	// 
	// @param (int) vendor id number
	// @return order id OR FALSE if insertion failed
	function make_order($vendor_id)
	{
		// get user id from session data
		$user_id = $this->session->userdata('id');
		
		// get meal id 
		$meal_id = $this->get_meal_id();
		
		// get price from vendor table
		$pquery = $this->db->query("SELECT price FROM vendors 
			WHERE id = $vendor_id");
		$total_price = $pquery->row()->price;
		
		// set up array for insertion
		$data = array(
				'user_id' => $user_id,
				'meal_id' => $meal_id,
				'vendor_id' => $vendor_id,
				'total_price' => $total_price
			);
		
		// insert into orders table
		if ($this->db->insert('orders', $data))
		{
			$order_id = $this->db->insert_id();
			
			// get std services
			$squery = $this->db->query("SELECT id FROM services 
				WHERE vendor_type_id = 
				(SELECT type_id FROM vendors WHERE id = $vendor_id)
				AND standard = 1");
			// TODO: get additional services		
			// insert each service into services_for_orders table
			foreach ($squery->result() as $service)
			{
				$this->db->query("INSERT INTO services_for_orders 
					(order_id, service_id) VALUES 
					($order_id, $service->id)");
			}
			
			return $order_id;
		}
		else // insertion failed
		{
			return false;
		}
		
	}
	
	// make_transaction(int, int)
	//
	// @param1 vendor id number
	// @param2 order id number
	// @return TRUE/FALSE based on insertion success
	function make_transaction($vendor_id, $order_id)
	{
		/*
			TODO transaction to manager's account
		*/
		// get giver (user) account id from session data
		$giver_account = $this->session->userdata('account_id');
		
		// get recipient (vendor) account id from query
		$rquery = $this->db->query("SELECT account_id FROM users 
			WHERE id = (SELECT user_id FROM vendors WHERE id = $vendor_id)");
		$recipient_account = $rquery->row()->account_id;
		
		// get price paid from query
		$pquery = $this->db->query("SELECT total_price FROM orders 
			WHERE id = $order_id");
		$amount = $pquery->row()->total_price;
		
		// get sale time from php date
		$sale_time = date("Y-m-d H:i:s");
		
		// set up array for insertion
		$data = array(
			'giver_account' => $giver_account,
			'recipient_account' => $recipient_account,
			'order_id' => $order_id,
			'amount' => $amount,
			'sale_time' => $sale_time
			);
			
		// insert into transactions table
		if ($this->db->insert('transactions', $data))
		{
			return true;
		}
		else // insertion failed
		{
			return false;
		}
	}
	
	// get_order_details(int)
	//
	// @param (int) order id
	// @return (array) vendor_id, vendor_name, vendor_type, total_price, 
	//   services (array) 
	function get_order_details($order_id)
	{
		// get vendor info
		$vquery = $this->db->query("SELECT vendors.id AS vendor_id, 
			vendors.name AS vendor_name, 
			vendor_types.name AS vendor_type 
			FROM vendors, vendor_types 
			WHERE vendors.id = 
			(SELECT vendor_id FROM orders WHERE id = $order_id) 
			AND type_id = vendor_types.id");
		$data = $vquery->row_array();
		
		// get price info
		$pquery = $this->db->query("SELECT total_price FROM orders 
			WHERE id = $order_id");
		$data['total_price'] = $pquery->row()->total_price;
		
		// get services
		$serv_query = $this->db->query("SELECT name
			FROM services_for_orders, services 
			WHERE service_id = services.id AND order_id = $order_id");
		$data['services'] = $serv_query->result_array();
		
		return $data;
	}
	
	// finish_meal()
	// mark the current meal as finished
	//
	// @return TRUE/FALSE depending on status of query execution
	function finish_meal()
	{
		$meal_id = $this->get_meal_id();
		$time_finished = date("Y-m-d H:i:s");
		
		return $this->db->query("UPDATE meals 
			SET time_finished = '{$time_finished}' WHERE id = '{$meal_id}'");
	}

}
// End File classname.php
// File Source /system/application/models/classname.php