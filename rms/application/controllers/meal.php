<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Meal controller
 *
 **/
class Meal extends Controller {

	// figure out which part of the meal workflow you are at and then display
	// that select vendor page
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
					// Set up data for view
					$data['type'] = 'host';
					$data['package'] = array(
						'Seat you at your choice of table or booth.', 
						'Provide menus.'
						);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services('host');
					$data['vendors'] = 
						$this->Meal_model->get_vendors('host');
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'waiter')):
					// Set up data for view
					$data['type'] = 'waiter';
					$data['package'] = array(
						'Take your order',
						'Make sure your glass is full.',
						'Bring you your order.'
						);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services('waiter');
					$data['vendors'] = 
						$this->Meal_model->get_vendors('waiter');
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'cook')):
					// Set up data for view
					$data['type'] = 'cook';
					$data['package'] = array(
						'Cook tonight’s meal.'
						);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services('cook');
					$data['vendors'] = 
						$this->Meal_model->get_vendors('cook');
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'busboy')):
					// Set up data for view
					$data['type'] = 'busboy';
					$data['package'] = array(
						'Clean up your mess.',
						'Take the tip.'
						);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services('busboy');
					$data['vendors'] = 
						$this->Meal_model->get_vendors('busboy');
					break;
			}
		}
		else // no
		{
			// Set up new meal
			$this->Meal_model->insert_new_meal($user_id);
			
			// Set data to host to view that vendor type selection page
			$data['type'] = 'host';
			$data['package'] = array(
				'Seat you at your choice of table or booth.', 
				'Provide menus.'
				);
			$data['add_services'] = 
				$this->Meal_model->get_vendor_services('host');
			$data['vendors'] = 
				$this->Meal_model->get_vendors('host');
		}
		// Load view with vendor type data generated from above
		$this->load->view('select-vendor', $data);
	}
	
	// view vendor public listing using vendor id 
	function vendor($vendor_id='')
	{
		// redirect to meal index if no vendor id given
		if ($vendor_id == '')
		{
			redirect ('/meal');
		}
		
		$this->load->model('Meal_model');
		
		// get vendor data for id given
		if ($data = $this->Meal_model->get_vendor($vendor_id))
		{
			// load view for this vendor
			$this->load->view('view-vendor', $data) ;
		}
		else // vendor not found
		{
			redirect ('/meal');
		}
		
	}
	
	// make order, do transaction
	function order()
	{
		if (!$this->session->userdata('logged_in'))
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->library('form_validation');
		
		// rules in ../config/form_validation.php
		if ($this->form_validation->run() == FALSE) // validate vendor id
		{
			// someone is hacking the code, log this
			redirect('/meal');
		}
		else // vendor id validated 
		{
			$vendor_id = $this->input->post('vendor-id');
			
			$this->load->model('Meal_model');
			
			// check if they are trying to resubmit (i.e. reloading page)
			if ($this->Meal_model->is_new_order($vendor_id))
			{
				// make order
				if ($order_id = $this->Meal_model->make_order($vendor_id))
				{
					// make transaction
					if (!$this->Meal_model->make_transaction($vendor_id, $order_id))
					{
						// log this error. Probably delete order if this happens.
						echo "Transaction not successful.";
					}
				}
				else
				{
					// log this error
					echo "Order not successful.";
				}
			}
			
			// set up data to display
		
			$this->load->view('order');
		
		}
		
		
		
	}
}
// End File meal.php
// File Source /system/application/controllers/meal.php