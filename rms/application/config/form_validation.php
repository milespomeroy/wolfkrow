<?php 

$config = array( 
	'login/signup' =>	array (	// TODO: Add more validation
		array (
			'field' => 'full-name',
			'label' => 'Full Name',
			'rules' => 'trim|required|max_length[50]|xss_clean'
		),
		array (
			'field'   => 'email', 
			'label'   => 'Email', 
			'rules'   => 'trim|required|max_length[50]|valid_email|callback__check_existing_email'
		),
		array (
			'field'   => 'password', 
			'label'   => 'Password', 
			'rules'   => 'trim|required'
		)
	),
	'login/index' => array (
		array (
			'field'   => 'email', 
			'label'   => 'Email', 
			'rules'   => 'trim|required|max_length[50]|valid_email|callback__check_for_account'
		),
		array (
			'field'   => 'password', 
			'label'   => 'Password', 
			'rules'   => 'trim|required'
		)
	),
	'meal/order' => array (
		array (
			'field' => 'vendor-id',
			'label' => 'Vendor Id',
			'rules' => 'required|numeric'
		)
	),
	'vendor/apply' => array (
		array (
			'field' => 'full-name',
			'label' => 'Full Name',
			'rules' => 'trim|required|max_length[50]'
		),
		array (
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email'
		),
		array (
			'field' => 'vendor-name',
			'label' => 'Vendor Name',
			'rules' => 'trim|required|max_length[50]'
		),
		array (
			'field' => 'type-id',
			'label' => 'Vendor Type',
			'rules' => 'required|is_natural'
		),
		array (
			'field' => 'qualifications',
			'label' => 'Qualifications',
			'rules' => 'trim|required'
		)
	)
);