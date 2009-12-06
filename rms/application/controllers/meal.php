<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Meal controller
 *
 **/
class Meal extends Controller {

	function index()
	{
		if(!$this->session->userdata('logged_in'))
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			return false;
		}
		
		$this->load->model('Meal_model');
		$user_id = $this->session->userdata('id');
				
		// Is there an unfinished meal for this user id?
		if ($this->Meal_model->get_unfinished_meals($user_id) > 0 ) // yes
		{
			
		}
		else // no
		{
			// Set up new meal
			$this->Meal_model->insert_new_meal($user_id);
		}
		
		
		$this->load->view('select-vendor');
		
		
	}
	
}
// End File meal.php
// File Source /system/application/controllers/meal.php