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

}
// End File admin_model.php
// File Source /system/application/models/admin_model.php