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
	function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<p class="error">', 
			'</p>');
				
		// rules in ../config/form_validation.php
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('homepage');
		}
		else // validation success
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
									
			// Get user info as an array
			$row = $this->login_model->get_user($email);
			
			//Check against password
			if (md5($password) != $row['password']) 
			{
				// Wrong password
				// TODO: show some message for wrong password
				return false;
			}

			//Destroy old session
			$this->session->sess_destroy();

			//Create a fresh, brand new session
			$this->session->sess_create();

			//Remove the password field
			unset($row['password']);

			//Set session data
			$this->session->set_userdata($row);

			//Set logged_in to true
			$this->session->set_userdata(array('logged_in' => true));			

			// Login was successful	
			// Switchboard to send to page depending on user type
			switch ($row['user_type'])
			{
				case 'vendor':
					redirect('/vendor');
					break;
				case 'manager':
					redirect('/admin');
					break;
				default:
					redirect('/meal');
			}
	
		}
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
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
				// could do some email activation stuff here but won't now
				redirect('/meal');
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
		$user['id'] = $this->login_model->insert_user($user);
		
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
	
	/**
	 * _check_for_account
	 * 
	 * callback function used in validating signin
	 * 
	 * Checks if a certain email is already associated with a user account
	 * before logging in
	 **/
	function _check_for_account($email)
	{
		if ($this->login_model->get_num_by_email($email) > 0) 
		{
			// account exists		
			return true;
		} 
		else 
		{
			// no account
			$this->form_validation->set_message('_check_for_account', 
				"Account not found. <a href='/login/signup'>Create one.</a>");
			return false;
		}
	}

}
// End File Login.php
// File Source /system/application/controllers/Login.php