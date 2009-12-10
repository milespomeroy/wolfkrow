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
	
	// demo_act()
	// demo of activation link normally emailed to vendors after an offer
	// has been made by the manager
	function demo_act()
	{
		$this->load->model('Vendor_model');
		
		// applications as an array of objects and place in $apps
		$data['apps'] = $this->Vendor_model->get_hired_apps();
		
		// activation link has the app_id and the md5 hash of the email of 
		// app_pass_phase concatenated
		$this->load->view('demo-activation', $data);
		
	}
	
	// activate(int, binary)
	// activate a vendor account that has been offered a hire by the manager
	//
	// @param1 'id' number in the vendor_applications table
	// @param2 md5 hash of email and app_pass_phrase concatenated
	function activate($app_id, $act_hash)
	{
		$this->load->model('Vendor_model');
		
		// check the verity of the hash, set $app to array of objects in 
		// vendors table for that id
		if ($app = $this->Vendor_model->get_application($app_id))
		{
			// do the hashes match?
			if ($act_hash == 
				md5($app->email . $this->config->item('app_pass_phrase')))
			{
				// congrats you can activate your account
				
				$this->load->library('form_validation');
				$this->form_validation->set_error_delimiters('<p class="error">', 
					'</p>');

				// rules in ../config/form_validation.php
				if ($this->form_validation->run('vendor_activate') == FALSE)
				{
					$this->load->view('vendor-activation', $app);
				}
				else // validation successful
				{

					// create data arrays for insertion
					// TODO: validation on credit card info
					$account = array (
						'cc_number' => $this->input->post('credit-card'),
						'cc_type' => $this->input->post('cc-type'),
						'cc_security_code' => $this->input->post('security-code'),
						'billing_name' => $this->input->post('name-card'),
						'billing_address' => $this->input->post('billing-add'),
						'cc_exp_month' => $this->input->post('exp-date-month'),
						'cc_exp_year' => $this->input->post('exp-date-year')
					);

					$user = array (
						'full_name' => $this->input->post('full-name'),
						'email' => $this->input->post('email'),
						'password' => $this->input->post('password'),
						'user_type' => 'vendor'
					);
							
					$vendor = array (
						'name' => $this->input->post('vendor-name'),
						'type_id' => $this->input->post('type-id'),
						'qualifications' => $this->input->post('qualifications'),
						'price' => $this->input->post('price')
					);

					if ($this->_create($account, $user, $vendor)) 
					{
						// mark activated date in vendor applications table
						$this->Vendor_model->mark_app_activated($app_id);
						// could do some email activation stuff here but won't now
						redirect('/vendor');
					} 
					else 
					{
						// problems inserting data into the database
						// TODO: put this in a log somewhere
						echo "I'm sorry we are experiencing problems with our
							database. Please try signing up again.";
					}
				}
			}
			else 
			{
				// hashes do not match
				redirect('/');
			}
		}
		else // application doesn't exist
		{
			redirect('/');
		}
	}

	function _create($account, $user, $vendor)
	{
		//Make sure account info was sent
		if(!$user OR !$account OR !$vendor) {
			return false;
		}
		
		$this->load->model('Vendor_model');
		
		//Encrypt password
		$user['password'] = md5($user['password']);
	
		// Insert account into the database and get id
		$user['account_id'] = $this->login_model->insert_account($account);
		
		// insert user info into database and get id
		$user['id'] = $this->login_model->insert_user($user);
		
		// insert vendor info into vendors table
		$vendor['user_id'] = $user['id'];
		$vendor_id = $this->Vendor_model->insert_vendor($vendor);
		
		// Upload image, TODO: show errors
		
		$config['upload_path'] = './images/';
		$config['allowed_types'] = 'jpg';
		$config['max_size']	= '1000';
		$config['max_width']  = '200';
		$config['max_height']  = '150';
		$config['file_name'] = $vendor_id;

		$this->load->library('upload', $config);

		$field_name = 'pic'; // field name of input file dialog in form

		$this->upload->do_upload($field_name);
				
		//Automatically login to created account
		//Remove the password field
		unset($user['password']);

		//Set session data
		$this->session->set_userdata($user);
			
		//Set logged_in to true
		$this->session->set_userdata('logged_in', true);			
	
		//Login was successful			
		return true;
	}
	
	function _check_existing_email($email)
	{
		if ($this->login_model->get_num_by_email($email) > 0) 
		{
			//email already exists		
			$this->form_validation->set_message('_check_existing_email', 
				"Account already exists for that $email. 
				Try <a href='/'>logging in</a>.");
			return false;
		} 
		else 
		{
			// new email
			return true;
		}
	}
}
// End File vendor.php
// File Source /system/application/controllers/vendor.php