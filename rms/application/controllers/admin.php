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
		if (!$this->_check_login())
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->view('admin-dash');
		
	}
	
	// applications()
	// review vendor applications
	function applications()
	{
		// Check if logged in and of vendor user type
		if (!$this->_check_login())
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->model('Admin_model');
		
		// array of vendor data objects from vendor_applications table
		$data['vendors'] = $this->Admin_model->get_vendor_apps();
		
		$this->load->view('review-apps', $data);
		
	}
	
	// _check_login()
	// internal function to check session data for being logged in and 
	// that the user type is manager
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
		if ($this->session->userdata('user_type') != 'manager')
		{
			return false;
		}
		
		// Yes logged in and yes you are a vendor
		return true;
	}

}
// End File admin.php
// File Source /system/application/controllers/admin.php