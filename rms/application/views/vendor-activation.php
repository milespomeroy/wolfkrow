<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Vendor Application Activation</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<!-- Similar to the Edit Public listing page -->
	
	<h1>Activate Your Vendor Account</h1>
	
	<p>
		To activate your account, please review the information previously
		entered. The vendor info will appear in the public listing of vendors.
		Guests will make their choice of vendor based on this information.
	</p>
	
	<?php echo validation_errors(); ?>
	
	<?php if (isset($error)) // file upload errors
		{
	 		echo $error;
		}
	 ?>
	
	<fieldset>
	<form action="" method="post" enctype="multipart/form-data">
		<label for="full-name">Full Name:</label>
		<input type="text" name="full-name" id="full-name" value="<?= 
			set_value('full-name', $full_name)?>">
			
		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?=
			set_value('email', $email)?>">

		<label for="password">Choose Password:</label>
		<input type="password" name="password" id="password" value="<?=
			set_value('password')?>">
		
		<hr>
		
		<label for="vendor-name">Vendor Name:</label>
		<input type="text" name="vendor-name" id="vendor-name" value="<?=
			set_value('vendor-name', $vendor_name)?>">
		
		<label for="type-id">Type:</label>
		<select id="type-id" name="type-id">
			<option value="1" <?=set_select('type-id', '1')?> <?php if ($vendor_type_id == 1) {echo 'selected';} ?>>Host</option>
			<option value="2" <?=set_select('type-id', '2')?> <?php if ($vendor_type_id == 2) {echo 'selected';} ?>>Waiter</option>
			<option value="3" <?=set_select('type-id', '3')?> <?php if ($vendor_type_id == 3) {echo 'selected';} ?>>Cook</option>
			<option value="4" <?=set_select('type-id', '4')?> <?php if ($vendor_type_id == 4) {echo 'selected';} ?>>Busboy</option>
		</select>
		
		<label for="qualifications">Qualifications:</label>
		<textarea id="qualifications" name="qualifications" cols="30" rows="5"><?=set_value('qualifications', $vendor_qualifications)?></textarea>
				
		<label for="price">Set Price ($):</label>
		<input type="text" id="price" name="price" value="<?=
			set_value('price')?>">
		
		<label for="pic">Picture: (200x150)</label>
		<input type="file" name="pic" id="pic">
		
		<hr>
		
		<p><em>Credit Card info disabled for demo.</em></p>

		<label for="credit-card">Credit Card #:</label>
		<input type="text" name="credit-card" id="credit-card" value="demo" disabled>

		<select id="cc-type" name="cc-type" disabled>
			<option value="visa">Visa</option>
			<option value="mastercard">MasterCard</option>
		</select>

		<label for="security-code">Security Code:</label>
		<input type="text" name="security-code" id="security-code" value="000" disabled>

		<label for="name-card">Name on Card:</label>
		<input type="text" name="name-card" id="name-card" value="demo name" disabled>

		<label for="billing-add">Billing Address:</label>
		<textarea name="billing-add" id="billing-add" cols="30" rows="5" disabled>123 address</textarea>

		<label for="exp-date-month">Expiration Date:</label>
		<select name="exp-date-month" id="exp-date-month" disabled>
			<option>12</option>
		</select>
		<select name="exp-date-year" id="exp-date-year" disabled>
			<option>2010</option>
		</select>
	
		<input type="submit" name="submit" value="Activate">
		
		<!-- 
			You are now an active vendor for Wolfkrow Diner. Please report to 
			the manager for additional training.
		-->
	</form>
	</fieldset>
	
	<p class="small">
		Note: There will be a 10% instutional fee for each service provided.
	</p>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>