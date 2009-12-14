<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Review Vendor Applications</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1>Review Vendor Applications</h1>
	
	<ul>
		<li><a href="/admin">&laquo; Back to Dashboard</a></li>
	</ul>
	
	<p>Once a vendor is hired, an email will be send to them with an 
		account activation link. For the demo, an email 
		will not be sent, rather you can 
		<a href="/vendor/demo_act" class="error">View Activation Links</a>
		directly.
	</p>

	<?php if ($vendors): ?>
	<?php foreach ($vendors as $vendor): ?>
	<div class="vendor">
		<h3><?=$vendor->vendor_name?></h3>
		<h4><?=$vendor->full_name?></h4>
		<p><?=ucwords($vendor->vendor_type_name)?></p>
		<p>
			<?php echo nl2br($vendor->vendor_qualifications); ?>
		</p>
		
		<form action="/admin/offer" method="post">
			<input type="hidden" name="app-id" value="<?=$vendor->id?>">
			<input type="submit" name="offer" value="Hire">
			<input type="submit" name="offer" value="Deny">
		</form>
	</div>
	<?php endforeach; ?>
	<?php else: ?>
		<p><em>No vendor applications to review at this time.</em></p>
	<?php endif; ?>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>