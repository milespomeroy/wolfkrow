<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Vendor
 *
 **/
class Vendor extends Controller {
	
	function index()
	{
		// Check if logged in
		if (!$this->session->userdata('logged_in'))
		{
			// not logged in. Na ah ah ah, you didn't say the magic word.
			redirect('/');
		}
		
		// Check if vendor
		if ($this->session->userdata('user_type') != 'vendor')
		{
			redirect('/');
		}
		
		$this->load->model('Vendor_model');
		
		// need: vendor_name, vendor_id, orders, revenue
		$data = $this->Vendor_model->get_vendor($this->session->userdata('id'));
		$data['orders'] = $this->Vendor_model->get_orders($data['vendor_id']);
		//$data['revenue'] = $this->Vendor_model->get_revenue();
		
		$this->load->view('vendor-dashboard', $data);
		
	}

}
// End File vendor.php
// File Source /system/application/controllers/vendor.php