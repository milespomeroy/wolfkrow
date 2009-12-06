<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Meal_model
 *
 **/
class Meal_model extends Model {

	// get_unfinished_meals()
	//
	// @param (int) user id
	// @return (int) number of meals
	//
	function get_unfinished_meals($user_id) 
	{
		$query = $this->db->query("SELECT id FROM meals 
			WHERE user_id = $user_id AND time_finished IS NULL");
		return $query->num_rows();
	}
	
	// insert_new_meal()
	//
	// @param (int) user id
	function insert_new_meal($user_id)
	{
		date_default_timezone_set('America/Los_Angeles'); 
		/* setting the timezone by hand for now, set in php.ini later */
		
		$mdate = date("Y-m-d H:i:s");
		
		$this->db->insert('meals', array(
			'user_id' => $user_id, 
			'time_started' => $mdate
			));
	}

}
// End File classname.php
// File Source /system/application/models/classname.php