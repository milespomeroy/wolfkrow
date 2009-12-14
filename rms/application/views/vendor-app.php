<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Vendor Application</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1>Vendor Application</h1>
	
	<p>Interested in being a vendor at Wolfkrow Diner? Fill out the form below:</p>
	<?php echo validation_errors(); ?>

	<fieldset>
	<form action="" method="post">
		<label for="full-name">Full Name:</label>
		<input type="text" name="full-name" id="full-name" value="<?= 
			set_value('full-name')?>">
		
		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?= 
			set_value('email')?>">
		
		<hr>
		
		<label for="vendor-name">Vendor Name:</label>
		<input type="text" id="vendor-name" name="vendor-name" value="<?= 
			set_value('vendor-name')?>">
		
		<!--
			TODO Auto generate type list from database
		-->
		<label for="type-id">Type:</label>
		<select id="type-id" name="type-id">
			<option value="1" <?=set_select('type-id', '1')?>>Host</option>
			<option value="2" <?=set_select('type-id', '2')?>>Waiter</option>
			<option value="3" <?=set_select('type-id', '3')?>>Cook</option>
			<option value="4" <?=set_select('type-id', '4')?>>Busboy</option>
		</select>
		
		<label for="qualifications">Qualifications:</label>
		<textarea id="qualifications" name="qualifications" cols="30" rows="5"><?=set_value('qualifications')?></textarea>
				
		<input type="submit" name="submit" value="Apply">
	</form>
	</fieldset>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>