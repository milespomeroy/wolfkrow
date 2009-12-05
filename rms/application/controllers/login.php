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
	 * signup form for new users
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
		else
		{	// TODO: input the rest of the data from the form
			if ($this->_create(
					$this->input->post('email'), 
					$this->input->post('password')
					)
			) {
				// TODO: login and get sent to your landing page
				redirect('/');
			} else {
				// problems inserting data into the database
				echo "I'm sorry we are experiencing problems with our
					database. Please try signing up again.";
			}
		}
		
	}
	
	/**
	 * _create
	 * private function
	 * Creates a user account for guests
	 **/
	function _create($email = '', $password = '')
	{
		//Make sure account info was sent
		if($email == '' OR $password == '') {
			return false;
		}
				
		//Encrypt password
		$password = md5($password);
	
		//Insert account into the database
		$data = array(
					'email' => $email,
					'password' => $password
				);
		$this->db->set($data); 
		if(!$this->db->insert('users')) {
			//There was a problem!
			return false;						
		}
		$user_id = $this->db->insert_id();
	
		//Automatically login to created account

		//Set session data
		$this->session->set_userdata(array('id' => $user_id,
			'email' => $email));
	
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
		$query = $this->db->get_where('users', array('email' => $email));
		
		if ($query->num_rows() > 0) {
			//email already exists
			$this->form_validation->set_message('_check_existing_email', 
					"Account already exists for $email. Try <a href='/'>
					logging in</a>.");
			return false;
		} else {
			// new email
			return true;
		}
	}

}
// End File Login.php
// File Source /system/application/controllers/Login.php