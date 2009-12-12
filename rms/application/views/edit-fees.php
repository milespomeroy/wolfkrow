<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Edit Transaction Fees</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	
	<h1>Edit Transaction Fees</h1>
	
	<ul>
		<li><a href="/admin">&laquo; Back to Dashboard</a></li>
	</ul>
	
	<p>Percentage paid for each transaction kept by management:</p>
	
	<fieldset>
		<?php echo validation_errors(); ?>

		<form action="" method="post">
			<?php foreach ($fees as $fee): ?>
			<label for="<?=$fee->type_id?>">
				<?=ucwords($fee->vendor_type)?>
			</label>
			<input type="text" name="<?=$fee->type_id?>" 
				id="<?=$fee->vendor_type?>" value="<?=$fee->percent_fee?>">
			<?php endforeach; ?>
			
			<?php if (isset($notice)): ?>
				<h3><?=$notice?></h3>
			<?php endif; ?>
			
			<input type="submit" value="Submit">
		</form>
	</fieldset>
	

	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>