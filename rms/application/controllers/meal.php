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
					// my form help includes a function to mark as checked the
					// the item with rating
					$this->load->helper('form');
					
					$data['orders'] = $this->Meal_model->get_meal();
					return $this->load->view('review-order', $data);
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
	// TODO: move to vendor controller?
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
				if (!($order_id = $this->Meal_model->make_order($vendor_id)))
				{
					// log this error
					echo "Order not successful.";
				}
			}
			
			// set up data to display
			
			$data = $this->Meal_model->get_order_details($order_id);
			$data['order_id'] = $order_id;
		
			$this->load->view('order', $data);
		
		}
	}
	
	// rate(int)
	//
	// @params vendor_id as a 3rd uri component and the post: newrate, order_id
	function rate()
	{
		if (!$this->session->userdata('logged_in'))
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->model('Vendor_model');
		
		$vendor_id = $this->input->post('vendor_id');
		$rating = $this->input->post('newrate');
		$order_id = $this->input->post('order_id');
		$user_id = $this->session->userdata('id');
		
		// check for something malicious
		if (!is_numeric($rating) OR !is_numeric($vendor_id) 
			OR !is_numeric($order_id))
		{
			// TODO: log error
			redirect('/');
		}
		
		// check for existing rating
		if ($this->Vendor_model->get_rating($order_id))
		{
			redirect('/meal');
			exit;
		}
		
		// set up data for insertion into ratings database
		$rate_date = date("Y-m-d H:i:s");
		$data = array(
				'vendor_id' => $vendor_id,
				'user_id' => $user_id,
				'order_id' => $order_id,
				'rating' => $rating,
				'rated_date' => $rate_date
			);
		
		$this->Vendor_model->insert_rating($data);
		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			redirect('/meal');
		}
	}
	
	// fillem()
	// demo "feature" to mark all orders filled
	function fillem()
	{
		$this->load->model('Meal_model');
		$this->load->model('Vendor_model');
		
		// get orders for active meal
		$orders = $this->Meal_model->get_meal();
		
		// only fill the orders if you have all 4 selected
		if (count($orders) < 4)
		{
			redirect('/meal');
		}
		
		// put just the order ids into an array to prep for function
		foreach ($orders as $order)
		{
			$order_ids[] = $order->order_id;
		}
		
		// do the filled magic, including transactions
		$this->Vendor_model->mark_as_filled($order_ids);
		
		redirect('/meal');
	}
}
// End File meal.php
// File Source /system/application/controllers/meal.php