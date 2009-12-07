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
			redirect('/');
		}
		
		$this->load->helper('html'); // for package list
		$this->load->model('Meal_model');
		$user_id = $this->session->userdata('id');
				
		// Is there an unfinished meal for this user id?
		if ($meal_id = $this->Meal_model->get_unfinished_meal($user_id)) // yes
		{
			// Check where they are at in the meal and pass data
			// The order of the meal workflow is currently hard coded here.
			// Could use the workflow_order value to define workflow more
			// dynamically.
			switch (false)
			{
				case ($this->Meal_model->vendor_order_exists($meal_id, 'host')):
					$data['type'] = 'host';
					$data['package'] = array(
						'Seat you at your choice of table or booth.', 
						'Provide menus.'
						);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'waiter')):
					$data['type'] = 'waiter';
					$data['package'] = array(
						'Take your order',
						'Make sure your glass is full.',
						'Bring you your order.'
						);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'cook')):
					$data['type'] = 'cook';
					$data['package'] = array(
						'Cook tonight’s meal.'
						);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'busboy')):
					$data['type'] = 'busboy';
					$data['package'] = array(
						'Clean up your mess.',
						'Take the tip.'
						);
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