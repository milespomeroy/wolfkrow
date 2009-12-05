<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login
 * handles homepage and login sessions
 **/
class Login extends Controller {

	/**
	 * Index 
	 * returns homepage view with login screen
	 **/
	function Index()
	{
		$this->load->view('homepage');
	}

	/**
	 * Signup
	 * signup form for new guests
	 **/
	function signup()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', 
			'</p>');
		
		// rules in ../config/form_validation.php
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('new-user');
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
			
			$user_data = array (
						'full_name' => $this->input->post('full-name'),
						'email' => $this->input->post('email'),
						'password' => $this->input->post('password'),
						'user_type' => 'guest'
					);
			
			if ($this->_create($account, $user_data)) 
			{
				// TODO: login and get sent guest landing page
				// could do some email activation stuff here but won't now
				redirect('/');
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
	
	/**
	 * _create
	 * private function _create(array1, array2)
	 * array1
	 *  (cc_number, cc_type, cc_security_code, billing_name, 
	 *  billing_address, cc_exp_month, cc_exp_year)
	 * array2
	 * 	(full_name, email, password, user_type)
	 *
	 * Creates a user account for guests
	 **/
	function _create($account, $user)
	{
		//Make sure account info was sent
		if(!$user OR !$account) {
			return false;
		}
		
		//Encrypt password
		$user['password'] = md5($user['password']);
	
		// Insert account into the database and get id
		$user['account_id'] = $this->login_model->insert_account($account);
		
		// insert user info into database and get id
		$user_id = $this->login_model->insert_user($user);
		
		//Automatically login to created account

		//Set session data
		$this->session->set_userdata(array('id' => $user_id,
			'email' => $user['email']));
	
		//Set logged_in to true
		$this->session->set_userdata('logged_in', true);			
	
		//Login was successful			
		return true;
	}

	/**
	 * _check_existing_email
	 * 
	 * callback function used in validating form
	 * 
	 * Checks if a certain email is already associated with a user account
	 * before allowing that user to create another one.
	 **/
	function _check_existing_email($email)
	{
		if ($this->login_model->get_num_by_email($email) > 0) 
		{
			//email already exists
			$this->form_validation->set_message('_check_existing_email', 
					"Account already exists for $email. Try <a href='/'>
					logging in</a>.");
			return false;
		} 
		else 
		{
			// new email
			return true;
		}
	}

}
// End File Login.php
// File Source /system/application/controllers/Login.php