<?php 

$config = array( 'login/signup' =>	array (	// TODO: Add more validation
		array (
			'field' => 'full-name',
			'label' => 'Full Name',
			'rules' => 'required'
		),
		array (
			'field'   => 'email', 
			'label'   => 'Email', 
			'rules'   => 'required|callback__check_existing_email'
		),
		array (
			'field'   => 'password', 
			'label'   => 'Password', 
			'rules'   => 'required'
		)
	)
);