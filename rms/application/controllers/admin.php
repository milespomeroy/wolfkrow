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
		// Check if logged in and of vendor user type
		$this->_check_login();
		
		$this->load->view('admin-dash');
	}
	
	// applications()
	// review vendor applications
	function applications()
	{
		// Check if logged in and of vendor user type
		$this->_check_login();
		
		$this->load->model('Admin_model');
		
		// array of vendor data objects from vendor_applications table
		$data['vendors'] = $this->Admin_model->get_vendor_apps();
		
		$this->load->view('review-apps', $data);
		
	}
	
	function offer()
	{
		// Check if logged in and of vendor user type
		$this->_check_login();
		
		$this->load->model('Admin_model');
		
		$this->Admin_model->make_offer($this->input->post('offer'), 
			$this->input->post('app-id'));
		
		redirect('/admin/applications');
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