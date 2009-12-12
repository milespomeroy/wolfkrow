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
		
		$this->load->model('Meal_model');
				
		// Is there an unfinished meal for this user id?
		if ($meal_id = $this->Meal_model->get_unfinished_meal()) // yes
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
					$data['package'] = 
						$this->Meal_model->get_std_package($data['type']);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services($data['type']);
					$data['vendors'] = 
						$this->Meal_model->get_vendors($data['type']);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'waiter')):
					// Set up data for view
					$data['type'] = 'waiter';
					$data['package'] = 
						$this->Meal_model->get_std_package($data['type']);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services($data['type']);
					$data['vendors'] = 
						$this->Meal_model->get_vendors($data['type']);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'cook')):
					// Set up data for view
					$data['type'] = 'cook';
					$data['package'] = 
						$this->Meal_model->get_std_package($data['type']);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services($data['type']);
					$data['vendors'] = 
						$this->Meal_model->get_vendors($data['type']);
					break;
				case ($this->Meal_model->vendor_order_exists($meal_id, 'busboy')):
					// Set up data for view
					$data['type'] = 'busboy';
					$data['package'] = 
						$this->Meal_model->get_std_package($data['type']);
					$data['add_services'] = 
						$this->Meal_model->get_vendor_services($data['type']);
					$data['vendors'] = 
						$this->Meal_model->get_vendors($data['type']);
					break;
				default:
					$data['orders'] = $this->Meal_model->get_meal();
					return $this->load->view('unfinished-meal', $data);
			}
		}
		else // no unfinished meal found
		{
			// Set up new meal
			$this->Meal_model->insert_new_meal();
			
			// Set data to host to view that vendor type selection page
			$data['type'] = 'host';
			$data['package'] = 
				$this->Meal_model->get_std_package($data['type']);
			$data['add_services'] = 
				$this->Meal_model->get_vendor_services($data['type']);
			$data['vendors'] = 
				$this->Meal_model->get_vendors($data['type']);
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
			// someone is hacking, log this
			redirect('/meal');
		}
		else // vendor id validated 
		{
			$vendor_id = $this->input->post('vendor-id');
			
			$this->load->model('Meal_model');
			
			// check if they are trying to resubmit (i.e. reloading page)
			if (!($order_id = $this->Meal_model->get_order_id($vendor_id)))
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
			
			$data = $this->Meal_model->get_order_details($order_id);
		
			$this->load->view('order', $data);
		
		}
	}
	
	// fillem()
	// demo "feature" to mark all orders filled
	function fillem()
	{
		$this->load->model('Meal_model');
		$orders = $this->Meal_model->get_meal();
		
		// only fill the orders if you have all 4 selected
		if (count($orders) < 4)
		{
			redirect('/meal');
		}
		
		$this->Meal_model->fill_all_orders();
		redirect('/meal');
	}
}
// End File meal.php
// File Source /system/application/controllers/meal.php