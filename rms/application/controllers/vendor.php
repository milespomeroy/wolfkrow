<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vendor
 *
 **/
class Vendor extends Controller {
	
	// index()
	// Vendor Dashboard
	function index()
	{
		// Check if logged in and of vendor user type
		if (!$this->_check_login())
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->model('Vendor_model');
		
		$v_user_id = $this->session->userdata('id');
		
		// need: vendor_name, vendor_id, orders, revenue
		$data = $this->Vendor_model->get_vendor($v_user_id);
		$data['orders'] = $this->Vendor_model->get_orders($data['vendor_id']);
		$data['revenue'] = $this->Vendor_model->get_revenue($v_user_id);
		
		$this->load->view('vendor-dashboard', $data);
		
	}
	
	// fill_orders()
	// used by the vendor dashboard to mark orders as fulfilled
	function fill_orders()
	{
		// Check if logged in and of vendor user type
		if (!$this->_check_login())
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->model('Vendor_model');
		
		// send array of order ids (if there are any selected)
		// mark as fulfilled and mark next order (if exists) as active
		if ($orders = $this->input->post('orders'))
		{
			$this->Vendor_model->mark_as_filled($orders);
		}
		
		redirect('/vendor');
	}
	
	// apply()
	// applications for becoming a vendor
	function apply()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', 
			'</p>');
				
		// rules in ../config/form_validation.php
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('vendor-app');
		}
		else // validation success, form submission go
		{
			$this->load->model('Vendor_model');
			
			// check for existing application TODO: give feedback
			if ($this->Vendor_model->app_exists($this->input->post('email')))
			{
				$this->load->view('vendor-app-submit');
			}
			else // new application
			{
				// set up array for insertion into vendor_applications table
				$v_app = array (
						'full_name' => $this->input->post('full-name'),
						'email' => $this->input->post('email'),
						'vendor_name' => $this->input->post('vendor-name'),
						'vendor_type_id' => $this->input->post('type-id'),
						'vendor_qualifications' => $this->input->post('qualifications')
					);
				
				if ($this->Vendor_model->insert_app($v_app))
				{
					$this->load->view('vendor-app-submit');
				}
				else
				{
					echo "Problem with the database. Go back and try again.";
				}
			}
		}
		
	}
	
	// _check_login()
	// internal function to check session data for being logged in and 
	// that the user type is vendor
	//
	// @return TRUE if both are right, FALSE if either are wrong
	function _check_login()
	{
		// Logged in?
		if (!$this->session->userdata('logged_in'))
		{
			return false;
		}
		
		// Check if vendor
		if ($this->session->userdata('user_type') != 'vendor')
		{
			return false;
		}
		
		// Yes logged in and yes you are a vendor
		return true;
	}

}
// End File vendor.php
// File Source /system/application/controllers/vendor.php