<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vendor
 *
 **/
class Vendor extends Controller {
	
	function index()
	{
		// Check if logged in and of vendor user type
		if (!$this->_check_login())
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		$this->load->model('Vendor_model');
		
		// need: vendor_name, vendor_id, orders, revenue
		$data = $this->Vendor_model->get_vendor($this->session->userdata('id'));
		$data['orders'] = $this->Vendor_model->get_orders($data['vendor_id']);
		//$data['revenue'] = $this->Vendor_model->get_revenue();
		
		$this->load->view('vendor-dashboard', $data);
		
	}
	
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