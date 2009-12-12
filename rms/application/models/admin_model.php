<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin_model
 *
 **/
class Admin_model extends Model {

	// get_vendor_apps()
	//
	// @return array of all vendors information objects from vendor_applications
	// table where there is no offer
	function get_vendor_apps()
	{
		$query = $this->db->query("SELECT 
			vendor_applications.id, full_name, email, vendor_name, 
			vendor_types.name AS vendor_type_name, vendor_qualifications 
			FROM vendor_applications, vendor_types 
			WHERE offer IS NULL AND vendor_type_id = vendor_types.id");
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	// make_offer(string, int)
	//
	// @param1 (string) the offer (Hire or Fire)
	// @param2 (int) vendor application id number
	// @return TRUE/FALSE query status
	function make_offer($offer, $app_id)
	{
		$offer_date = date("Y-m-d H:i:s");
		$data = array('offer' => $offer, 'offer_date' => $offer_date);
		$this->db->where('id', $app_id);
		return $this->db->update('vendor_applications', $data);
	}
	
	// get_fees()
	//
	// @return array of objects: type_id, vendor_type, percent_fee
	function get_fees()
	{
		$query = $this->db->query("SELECT id AS type_id, 
			name AS vendor_type, percent_fee 
			FROM vendor_types ORDER BY workflow_order");
		return $query->result();
	}
	
	// set_fees(array)
	//
	// @param array of fees to be changed as an array of fields:
	//   type_id, fee
	function set_fees($fees)
	{
		foreach ($fees as $fee)
		{
			$this->db->set('percent_fee', $fee['fee']);
			$this->db->where('id', $fee['type_id']);
			$this->db->update('vendor_types');
		}
	}
}
// End File admin_model.php
// File Source /system/application/models/admin_model.php