<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin
 *
 **/
class Admin extends Controller {

	// index()
	// Manager's Dashboard
	function index()
	{
		// Check if logged in and of manager user type
		$this->_check_login();
		
		$this->load->view('admin-dash');
	}
	
	// applications()
	// review vendor applications
	function applications()
	{
		// Check if logged in and of manager user type
		$this->_check_login();
		
		$this->load->model('Admin_model');
		
		// array of vendor data objects from vendor_applications table
		$data['vendors'] = $this->Admin_model->get_vendor_apps();
		
		$this->load->view('review-apps', $data);
		
	}
	
	// offer()
	// post 'offer' (Hire/Deny) and 'app-id' application id number
	function offer()
	{
		// Check if logged in and of manager user type
		$this->_check_login();
		
		$this->load->model('Admin_model');
		
		$this->Admin_model->make_offer($this->input->post('offer'), 
			$this->input->post('app-id'));
		
		redirect('/admin/applications');
	}
	
	// fees()
	// edit amount that management keeps per transaction
	function fees()
	{
		// Check if logged in and of manager user type
		$this->_check_login();
		// get current fees as array of objects: vendor_type, percent_fee
		$this->load->model('Admin_model');
		$data['fees'] = $this->Admin_model->get_fees();
		
		$this->load->library('form_validation');
		
		// set rules for each vendor type
		foreach ($data['fees'] as $type)
		{
			$this->form_validation->set_rules($type->type_id, 
				ucwords($type->vendor_type), 'trim|required|numeric');
		}
		$this->form_validation->set_error_delimiters('<h3 class="error">', 
			'</h3>');

		if ($this->form_validation->run() == FALSE)
		{			
			$this->load->view('edit-fees', $data);
		}
		else
		{
			// set up array for set_fees function
			for ($type_id = 1; $type_id <= count($_POST); $type_id++)
			{
				$vendor_fees[] = array(
					'type_id' => $type_id, 
					'fee' => $this->input->post($type_id)
					);
			}
			
			// update the vendor_types table with changes
			$this->Admin_model->set_fees($vendor_fees);
			
			// make a note that the changes were made for the user
			$data['notice'] = 'Changes have been implemented.';
				
			// look up fees again now that changes have been made
			$data['fees'] = $this->Admin_model->get_fees();

			$this->load->view('edit-fees', $data);
		}
	}
	
	// _check_login()
	// internal function to check session data for being logged in and 
	// that the user type is manager
	//
	// redirects if not logged in or not a manager
	function _check_login()
	{
		// Logged in?
		if (!$this->session->userdata('logged_in'))
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		// Check if vendor
		if ($this->session->userdata('user_type') != 'manager')
		{
			redirect('/');
		}
		
	}

}
// End File admin.php
// File Source /system/application/controllers/admin.php