<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>First Timer Sign up</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<h1>Thank you for choosing Wolfkrow Diner!</h1>
	
	<p>Fill out the form below to set up your account for your first visit.</p>
	
	<?php echo validation_errors(); ?>

	<form action="" method="post">
	<fieldset>
		
		<label for="full-name">Full Name:</label>
		<input type="text" name="full-name" id="full-name" value="<?= 
			set_value('full-name')?>">
		
		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?=
			set_value('email')?>">
		
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="<?=
			set_value('password')?>">
		
		<hr>
		
		<label for="credit-card">Credit Card #:</label>
		<input type="text" name="credit-card" id="credit-card" value="demo">
		
		<select id="cc-type" name="cc-type">
			<option value="visa">Visa</option>
			<option value="mastercard">MasterCard</option>
		</select>
		
		<label for="security-code">Security Code:</label>
		<input type="text" name="security-code" id="security-code" value="000">
		
		<label for="name-card">Name on Card:</label>
		<input type="text" name="name-card" id="name-card" value="demo name">
		
		<label for="billing-add">Billing Address:</label>
		<textarea name="billing-add" id="billing-add" cols="30" rows="5">123 address</textarea>
		
		<label for="exp-date-month">Expiration Date:</label>
		<select name="exp-date-month" id="exp-date-month">
			<option>12</option>
		</select>
		<select name="exp-date-year" id="exp-date-year">
			<option>2010</option>
		</select>
		
		<input type="submit" name="submit" value="Submit">
		
	</fieldset>
	</form>


	<div id="footer">
	&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles 
	Pomeroy
	</div>

</body>
</html>