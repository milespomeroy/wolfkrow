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
		if ($meal_id = $this->Meal_model->get_unfinished_meal($user_id)) // yes
		{
			// Check where they are at in the meal and pass data
			switch (false)
			{
				case ($this->Meal_model->vendor_order_exists($meal_id, 'host')):
					$data['type'] = 'host';
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'waiter')):
					$data['type'] = 'waiter';
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'cook')):
					$data['type'] = 'cook';
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'busboy')):
					$data['type'] = 'busboy';
					break;
			}
		}
		else // no
		{
			// Set up new meal
			$this->Meal_model->insert_new_meal($user_id);
			
			// Set vendor type to host to select that vendor
			$data['type'] = 'host';
		}
		
		$this->load->view('select-vendor', $data);
		
	}
	
}
// End File meal.php
// File Source /system/application/controllers/meal.php