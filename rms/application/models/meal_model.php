<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Meal_model
 *
 **/
class Meal_model extends Model {

	// get_unfinished_meal()
	//
	// @return (int) meal id OR false if none
	function get_unfinished_meal() 
	{
		$user_id = $this->session->userdata('id');

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
	
	// get_meal()
	//
	// @return array of objects: order_id, price, activated_date 
	//  (null if not active), filled (null if not active),
	//   vendor_name, vendor_type, rating
	function get_meal()
	{
		$meal_id = $this->get_unfinished_meal();
		
		$query = $this->db->query("SELECT orders.id AS order_id, 
					total_price AS price, activated_date, filled, 
					vendors.name AS vendor_name, vendor_types.name AS vendor_type,
					vendors.id AS vendor_id, ratings.rating
					FROM vendors, vendor_types, 
					orders LEFT JOIN ratings ON orders.id = ratings.order_id
					WHERE meal_id = $meal_id 
					AND orders.vendor_id = vendors.id 
					AND vendors.type_id = vendor_types.id 
					ORDER BY vendors.type_id");
			
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		return false;
	}
	
	// insert_new_meal(int)
	//
	// @param (int) user id
	function insert_new_meal()
	{
		$user_id = $this->session->userdata('id');

		$mdate = date("Y-m-d H:i:s");
		
		$this->db->insert('meals', array(
			'user_id' => $user_id, 
			'time_started' => $mdate
			));
	}
	
	// get_meal_stats()
	// for admin dashboard
	//
	// return array of numbers: new_meals, host, waiter, cook, busboy
	function get_meal_stats()
	{
		// get new meals (no active orders)
		// this query might not scale well
		$mquery = $this->db->query("SELECT COUNT(id) AS count 
			FROM meals WHERE time_finished IS NULL 
			AND id NOT IN (SELECT meal_id FROM orders)");
		$data[] = $mquery->row()->count;
			
		$tquery = $this->db->get('vendor_types');
		$types = $tquery->result();
		
		foreach ($types as $type)
		{
			$query = $this->db->query("SELECT COUNT(id) AS count 
				FROM orders WHERE activated_date IS NOT NULL 
				AND filled IS NULL AND vendor_id IN 
				(SELECT id FROM vendors WHERE type_id = $type->id)");
			$data[] = $query->row()->count;
		}
		
		return $data;		
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
				WHERE vendor_id = $vendor_id AND filled IS NULL");

			$data = $vquery->row_array();
			$data['orders'] = $oquery->num_rows();
			return $data;
		}
		else // vendor not found
		{
			return false;
		}
	}
	
	// get_order_id(int)
	//
	// @param vendor id 
	// @return order id OR FALSE if no order found
	function get_order_id($vendor_id)
	{
		// get meal id 
		if (! ($meal_id = $this->get_unfinished_meal()) )
		{
			// FIX: this isn't an elegant way of doing this
			// really shouldn't happen though
			echo "No meals found.";
			exit;
		}
		
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
		$meal_id = $this->get_unfinished_meal();
		
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
			
		// set as active if no active, non-filled order for meal
		$aquery = $this->db->query("SELECT id FROM orders 
			WHERE meal_id = $meal_id 
			AND activated_date IS NOT NULL 
			AND filled IS NULL");
		if ($aquery->num_rows() == 0)
		{
			$data['activated_date'] = date("Y-m-d H:i:s");
		}
		
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
	
	// get_order_details(int)
	//
	// @param (int) order id
	// @return (array) vendor_id, vendor_name, vendor_type, total_price, 
	//   services (array), rating (NULL if there is none)
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
}
// End File classname.php
// File Source /system/application/models/classname.php